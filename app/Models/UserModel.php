<?php

namespace App\Models;

use CodeIgniter\Model;
use RuntimeException;

class UserModel extends Model
{
    private const ENC_PREFIX = 'enc::';

    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'name',
        'email',
        'no_kk',
        'nik',
        'birth_place',
        'birth_date',
        'gender',
        'religion',
        'occupation',
        'marital_status',
        'address',
        'rt',
        'rw',
        'village',
        'district',
        'city',
        'province',
        'citizenship',
        'password',
        'role',
        'last_login_at',
    ];
    protected $useTimestamps = true;

    protected $beforeInsert = ['encryptSensitiveBeforeInsert'];
    protected $beforeUpdate = ['encryptSensitiveBeforeUpdate'];
    protected $afterFind    = ['decryptSensitiveAfterFind'];

    /**
     * @var string[]
     */
    private array $encryptedFields = [
        'no_kk',
        'nik',
        'birth_place',
        'birth_date',
        'gender',
        'religion',
        'occupation',
        'marital_status',
        'address',
        'rt',
        'rw',
        'village',
        'district',
        'city',
        'province',
        'citizenship',
    ];

    protected function encryptSensitiveBeforeInsert(array $data): array
    {
        if (isset($data['data']) && is_array($data['data'])) {
            $data['data'] = $this->encryptSensitiveFields($data['data']);
        }

        return $data;
    }

    protected function encryptSensitiveBeforeUpdate(array $data): array
    {
        if (isset($data['data']) && is_array($data['data'])) {
            $data['data'] = $this->encryptSensitiveFields($data['data']);
        }

        return $data;
    }

    protected function decryptSensitiveAfterFind(array $data): array
    {
        if (! array_key_exists('data', $data) || $data['data'] === null) {
            return $data;
        }

        if (isset($data['method']) && $data['method'] === 'findColumn') {
            return $data;
        }

        if (is_array($data['data']) && $data['data'] !== []) {
            if (array_key_exists('id', $data['data'])) {
                $data['data'] = $this->decryptSensitiveFields($data['data']);
            } else {
                foreach ($data['data'] as $idx => $row) {
                    if (is_array($row)) {
                        $data['data'][$idx] = $this->decryptSensitiveFields($row);
                    }
                }
            }
        }

        return $data;
    }

    private function encryptSensitiveFields(array $payload): array
    {
        foreach ($this->encryptedFields as $field) {
            if (! array_key_exists($field, $payload)) {
                continue;
            }

            $value = $payload[$field];
            if ($value === null || $value === '') {
                continue;
            }

            $payload[$field] = $this->encryptFieldValue((string) $value);
        }

        return $payload;
    }

    private function decryptSensitiveFields(array $payload): array
    {
        foreach ($this->encryptedFields as $field) {
            if (! array_key_exists($field, $payload)) {
                continue;
            }

            $value = $payload[$field];
            if (! is_string($value) || $value === '') {
                continue;
            }

            $payload[$field] = $this->decryptFieldValue($value);
        }

        return $payload;
    }

    private function encryptFieldValue(string $plainText): string
    {
        if (str_starts_with($plainText, self::ENC_PREFIX)) {
            return $plainText;
        }

        $key = $this->getCipherKey();
        $iv = random_bytes(16);
        $cipher = openssl_encrypt($plainText, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
        if ($cipher === false) {
            throw new RuntimeException('Gagal mengenkripsi data sensitif user.');
        }

        $mac = hash_hmac('sha256', $iv . $cipher, $key, true);
        return self::ENC_PREFIX . base64_encode($iv . $mac . $cipher);
    }

    private function decryptFieldValue(string $storedValue): string
    {
        if (! str_starts_with($storedValue, self::ENC_PREFIX)) {
            return $storedValue;
        }

        $encoded = substr($storedValue, strlen(self::ENC_PREFIX));
        $raw = base64_decode($encoded, true);
        if (! is_string($raw) || strlen($raw) < 48) {
            return '';
        }

        $key = $this->getCipherKey();
        $iv = substr($raw, 0, 16);
        $mac = substr($raw, 16, 32);
        $cipher = substr($raw, 48);
        $calcMac = hash_hmac('sha256', $iv . $cipher, $key, true);

        if (! hash_equals($mac, $calcMac)) {
            return '';
        }

        $plain = openssl_decrypt($cipher, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
        return $plain === false ? '' : $plain;
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
