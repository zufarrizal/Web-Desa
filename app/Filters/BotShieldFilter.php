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

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
