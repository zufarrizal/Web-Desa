<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RequestThrottleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (is_cli()) {
            return null;
        }

        $ip = (string) $request->getIPAddress();
        $uri = (string) $request->getUri()->getPath();
        $throttler = service('throttler');

        // Global per-IP limit.
        $ipKey = preg_replace('/[^A-Za-z0-9_-]/', '_', $ip) ?? 'unknown_ip';
        if (! $throttler->check('req_ip_' . $ipKey, 240, MINUTE)) {
            return service('response')
                ->setStatusCode(429)
                ->setBody('Terlalu banyak request. Coba lagi beberapa saat.');
        }

        // Extra protection for repeated hits to same endpoint.
        if (! $throttler->check('req_ip_uri_' . $ipKey . '_' . md5($uri), 80, MINUTE)) {
            return service('response')
                ->setStatusCode(429)
                ->setBody('Akses terlalu cepat ke endpoint ini. Coba lagi beberapa saat.');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
