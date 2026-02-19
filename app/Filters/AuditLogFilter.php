<?php

namespace App\Filters;

use App\Models\AuditLogModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Throwable;

class AuditLogFilter implements FilterInterface
{
    /**
     * @var string[]
     */
    private array $sensitiveKeys = [
        'password',
        'password_confirm',
        'csrf_test_name',
        'csrf_cookie_name',
        'csrf_token_name',
        'token',
        'token_hash',
        'reset_token',
        'honeypot',
        'signature',
        'signer_signature',
    ];

    public function before(RequestInterface $request, $arguments = null)
    {
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        if (is_cli() || ! $request instanceof IncomingRequest) {
            return null;
        }

        $method = strtoupper($request->getMethod());
        if (! in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return null;
        }

        $uriPath = trim((string) $request->getUri()->getPath(), '/');
        if ($uriPath === '' || str_starts_with($uriPath, 'assets/')) {
            return null;
        }

        try {
            $payload = $this->extractPayload($request, $method);
            $statusCode = (int) $response->getStatusCode();

            (new AuditLogModel())->insert([
                'user_id'     => session()->get('user_id') ? (int) session()->get('user_id') : null,
                'user_name'   => session()->get('user_name') ? (string) session()->get('user_name') : null,
                'user_role'   => session()->get('user_role') ? (string) session()->get('user_role') : null,
                'method'      => $method,
                'uri'         => '/' . $uriPath,
                'action'      => $this->buildActionLabel($method, $uriPath),
                'status_code' => $statusCode > 0 ? $statusCode : null,
                'ip_address'  => $request->getIPAddress(),
                'user_agent'  => $this->truncate((string) $request->getUserAgent(), 255),
                'payload'     => $payload,
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
        } catch (Throwable $e) {
            log_message('error', 'Audit log write failed: {message}', ['message' => $e->getMessage()]);
        }

        return null;
    }

    private function buildActionLabel(string $method, string $uriPath): string
    {
        $cleanPath = preg_replace('#/\d+#', '/{id}', $uriPath) ?? $uriPath;
        return $method . ' ' . $cleanPath;
    }

    private function extractPayload(IncomingRequest $request, string $method): ?string
    {
        $payload = [];
        if (in_array($method, ['POST', 'DELETE'], true)) {
            $payload = $request->getPost();
            if (! is_array($payload)) {
                $payload = [];
            }
        } else {
            $payload = $request->getRawInput();
            if (! is_array($payload)) {
                $payload = [];
            }
        }

        $payload = $this->sanitizeArray($payload);
        if ($payload === []) {
            return null;
        }

        $encoded = json_encode($payload, JSON_UNESCAPED_UNICODE);
        if (! is_string($encoded) || $encoded === '') {
            return null;
        }

        return $this->truncate($encoded, 6000);
    }

    /**
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    private function sanitizeArray(array $data): array
    {
        $clean = [];
        foreach ($data as $key => $value) {
            $keyName = is_string($key) ? $key : (string) $key;
            $keyLower = strtolower($keyName);
            if ($this->isSensitiveKey($keyLower)) {
                $clean[$keyName] = '[redacted]';
                continue;
            }

            if (is_array($value)) {
                $clean[$keyName] = $this->sanitizeArray($value);
                continue;
            }

            if (is_scalar($value) || $value === null) {
                $clean[$keyName] = $this->truncate((string) $value, 300);
                continue;
            }

            $clean[$keyName] = '[non-scalar]';
        }

        return $clean;
    }

    private function isSensitiveKey(string $key): bool
    {
        foreach ($this->sensitiveKeys as $item) {
            if ($key === $item || str_contains($key, $item)) {
                return true;
            }
        }

        return false;
    }

    private function truncate(string $value, int $maxLength): string
    {
        if (strlen($value) <= $maxLength) {
            return $value;
        }

        return substr($value, 0, $maxLength);
    }
}

