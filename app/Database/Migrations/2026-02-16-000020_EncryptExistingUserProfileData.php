<?php

namespace App\Database\Migrations;

use App\Models\UserModel;
use CodeIgniter\Database\Migration;

class EncryptExistingUserProfileData extends Migration
{
    public function up()
    {
        $userModel = new UserModel();
        $users = $userModel->findAll();

        if (! is_array($users) || $users === []) {
            return;
        }

        $fields = [
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

        foreach ($users as $user) {
            $id = (int) ($user['id'] ?? 0);
            if ($id < 1) {
                continue;
            }

            $payload = [];
            foreach ($fields as $field) {
                if (array_key_exists($field, $user)) {
                    $payload[$field] = $user[$field];
                }
            }

            if ($payload !== []) {
                // Will trigger UserModel beforeUpdate callback to encrypt fields.
                $userModel->update($id, $payload);
            }
        }
    }

    public function down()
    {
        // No automatic decrypt rollback for security reasons.
    }
}

