<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MakeUserProfileFieldsEncryptable extends Migration
{
    public function up()
    {
        $textFields = [
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

        foreach ($textFields as $field) {
            if ($this->db->fieldExists($field, 'users')) {
                $this->forge->modifyColumn('users', [
                    $field => [
                        'type' => 'TEXT',
                        'null' => true,
                    ],
                ]);
            }
        }
    }

    public function down()
    {
        // Non-destructive rollback intentionally omitted because
        // encrypted data may exceed previous VARCHAR/DATE limits.
    }
}

