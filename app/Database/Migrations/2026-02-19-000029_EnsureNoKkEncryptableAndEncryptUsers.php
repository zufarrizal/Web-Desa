<?php

namespace App\Database\Migrations;

use App\Models\UserModel;
use CodeIgniter\Database\Migration;

class EnsureNoKkEncryptableAndEncryptUsers extends Migration
{
    public function up()
    {
        if ($this->db->fieldExists('no_kk', 'users')) {
            $this->forge->modifyColumn('users', [
                'no_kk' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
            ]);
        }

        $userModel = new UserModel();
        $users = $userModel->findAll();

        if (! is_array($users) || $users === []) {
            return;
        }

        foreach ($users as $user) {
            $id = (int) ($user['id'] ?? 0);
            if ($id < 1) {
                continue;
            }

            // Re-save via model to guarantee encrypted storage for sensitive IDs.
            $payload = [
                'no_kk' => (string) ($user['no_kk'] ?? ''),
                'nik' => (string) ($user['nik'] ?? ''),
            ];

            $userModel->update($id, $payload);
        }
    }

    public function down()
    {
        // No automatic decrypt rollback for security reasons.
    }
}
