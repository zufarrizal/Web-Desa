<?php

namespace App\Models;

use CodeIgniter\Model;
use RuntimeException;

class DocumentRequestModel extends Model
{
    private const ENC_PREFIX = 'enc::';

    protected $table         = 'document_requests';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'user_id',
        'citizen_name',
        'nik',
        'document_type',
        'description',
        'status',
        'admin_notes',
    ];
    protected $useTimestamps = true;

    protected $beforeInsert = ['encryptNikBeforeInsert'];
    protected $beforeUpdate = ['encryptNikBeforeUpdate'];
    protected $afterFind    = ['decryptNikAfterFind'];

    protected function encryptNikBeforeInsert(array $data): array
    {
        if (isset($data['data']) && is_array($data['data'])) {
            $data['data'] = $this->encryptNikField($data['data']);
        }

        return $data;
    }

    protected function encryptNikBeforeUpdate(array $data): array
    {
        if (isset($data['data']) && is_array($data['data'])) {
            $data['data'] = $this->encryptNikField($data['data']);
        }

        return $data;
    }

    protected function decryptNikAfterFind(array $data): array
    {
        if (! array_key_exists('data', $data) || $data['data'] === null) {
            return $data;
        }

        if (isset($data['method']) && $data['method'] === 'findColumn') {
            return $data;
        }

        if (is_array($data['data']) && $data['data'] !== []) {
            if (array_key_exists('id', $data['data'])) {
                $data['data'] = $this->decryptNikField($data['data']);
            } else {
                foreach ($data['data'] as $idx => $row) {
                    if (is_array($row)) {
                        $data['data'][$idx] = $this->decryptNikField($row);
                    }
                }
            }
        }

        return $data;
    }

    private function encryptNikField(array $payload): array
    {
        if (! array_key_exists('nik', $payload)) {
            return $payload;
        }

        $value = $payload['nik'];
        if ($value === null || $value === '') {
            return $payload;
        }

        $plainText = (string) $value;
        if (str_starts_with($plainText, self::ENC_PREFIX)) {
            return $payload;
        }

        $key = $this->getCipherKey();
        $iv = random_bytes(16);
        $cipher = openssl_encrypt($plainText, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
        if ($cipher === false) {
            throw new RuntimeException('Gagal mengenkripsi NIK dokumen.');
        }

        $mac = hash_hmac('sha256', $iv . $cipher, $key, true);
        $payload['nik'] = self::ENC_PREFIX . base64_encode($iv . $mac . $cipher);

        return $payload;
    }

    private function decryptNikField(array $payload): array
    {
        if (! array_key_exists('nik', $payload)) {
            return $payload;
        }

        $value = $payload['nik'];
        if (! is_string($value) || $value === '' || ! str_starts_with($value, self::ENC_PREFIX)) {
            return $payload;
        }

        $encoded = substr($value, strlen(self::ENC_PREFIX));
        $raw = base64_decode($encoded, true);
        if (! is_string($raw) || strlen($raw) < 48) {
            $payload['nik'] = '';
            return $payload;
        }

        $key = $this->getCipherKey();
        $iv = substr($raw, 0, 16);
        $mac = substr($raw, 16, 32);
        $cipher = substr($raw, 48);
        $calcMac = hash_hmac('sha256', $iv . $cipher, $key, true);
        if (! hash_equals($mac, $calcMac)) {
            $payload['nik'] = '';
            return $payload;
        }

        $plain = openssl_decrypt($cipher, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
        $payload['nik'] = $plain === false ? '' : $plain;

        return $payload;
    }

    private function getCipherKey(): string
    {
        $sourceKey = (string) (
            env('app.profileDataKey')
            ?: env('encryption.key')
            ?: ''
        );

        if ($sourceKey === '') {
            throw new RuntimeException('Kunci enkripsi belum diset. Set app.profileDataKey di .env.');
        }

        return hash('sha256', $sourceKey, true);
    }
}
