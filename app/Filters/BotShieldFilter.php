<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class BotShieldFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! $request instanceof IncomingRequest || is_cli()) {
            return null;
        }

        if (strtoupper($request->getMethod()) !== 'POST') {
            return null;
        }

        // Honeypot trap for automated form submissions (public forms).
        // Skip this trap for authenticated area to avoid false positives
        // from browser/password-manager autofill on hidden fields.
        if (! session()->get('logged_in') && Services::honeypot()->hasContent($request)) {
            return service('response')
                ->setStatusCode(400)
                ->setBody('Aktivitas tidak valid terdeteksi. Silakan muat ulang halaman dan coba lagi.');
        }

        // Extra POST throttling to reduce spam floods on forms.
        $ip = (string) $request->getIPAddress();
        $ipKey = preg_replace('/[^A-Za-z0-9_-]/', '_', $ip) ?? 'unknown_ip';
        $uri = (string) $request->getUri()->getPath();
        $uriKey = md5($uri);
        $throttler = service('throttler');

        if (! $throttler->check('post_ip_' . $ipKey, 45, MINUTE)) {
            return service('response')
                ->setStatusCode(429)
                ->setBody('Terlalu banyak submit form. Coba lagi beberapa saat.');
        }

        if (! $throttler->check('post_ip_uri_' . $ipKey . '_' . $uriKey, 20, MINUTE)) {
            return service('response')
                ->setStatusCode(429)
                ->setBody('Form ini dikirim terlalu sering. Mohon tunggu sebentar.');
        }

        // Burst guard: block rapid-fire submissions in a short window.
        if (! $throttler->check('post_burst_' . $ipKey, 8, 10)) {
            return service('response')
                ->setStatusCode(429)
                ->setBody('Submit terlalu cepat. Mohon jeda beberapa detik sebelum mencoba lagi.');
        }

        // Duplicate payload guard: block repeated identical submits to same endpoint.
        $cache = cache();
        $sessionId = session_id();
        $clientKey = $sessionId !== '' ? $sessionId : $ipKey;
        $dedupeKey = 'post_dedupe_' . md5($clientKey . '|' . $uri);
        $payloadHash = $this->buildPayloadHash($request);
        $lastPayloadHash = (string) ($cache->get($dedupeKey) ?? '');
        if ($payloadHash !== '' && $payloadHash === $lastPayloadHash) {
            return service('response')
                ->setStatusCode(429)
                ->setBody('Data yang sama terdeteksi dikirim berulang. Mohon tunggu sebentar.');
        }
        if ($payloadHash !== '') {
            // Short TTL to stop spam loops but still allow normal repeat actions later.
            $cache->save($dedupeKey, $payloadHash, 15);
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }

    private function buildPayloadHash(IncomingRequest $request): string
    {
        $post = $request->getPost();
        if (! is_array($post) || $post === []) {
            return '';
        }

        $security = config('Security');
        $csrfTokenName = is_object($security) && property_exists($security, 'tokenName')
            ? (string) $security->tokenName
            : 'csrf_test_name';

        unset($post[$csrfTokenName], $post['honeypot'], $post['_method']);
        if ($post === []) {
            return '';
        }

        ksort($post);
        $encoded = json_encode($post, JSON_UNESCAPED_UNICODE);
        if (! is_string($encoded) || $encoded === '') {
            return '';
        }

        return hash('sha256', $encoded);
    }
}
