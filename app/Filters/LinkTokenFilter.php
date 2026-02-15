<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class LinkTokenFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (is_cli()) {
            return null;
        }

        if (! session()->get('logged_in')) {
            return null;
        }

        if (strtoupper($request->getMethod()) !== 'GET') {
            return null;
        }

        $expected = (string) session()->get('link_token');
        if ($expected === '') {
            $expected = bin2hex(random_bytes(24));
            session()->set('link_token', $expected);
        }

        $provided = (string) $request->getGet('_lt');
        if ($provided === '') {
            $uri = clone $request->getUri();
            $query = [];
            parse_str((string) $uri->getQuery(), $query);
            $query['_lt'] = $expected;
            $uri->setQuery(http_build_query($query));

            return redirect()->to((string) $uri);
        }

        if (! hash_equals($expected, $provided)) {
            return redirect()->to('/dashboard')->with('error', 'Token link tidak valid. Silakan coba kembali.');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}

