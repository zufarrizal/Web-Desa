<?php

namespace App\Database\Migrations;

use App\Models\DocumentRequestModel;
use CodeIgniter\Database\Migration;

class EncryptExistingDocumentRequestNik extends Migration
{
    public function up()
    {
        $model = new DocumentRequestModel();
        $rows = $model->findAll();

        if (! is_array($rows) || $rows === []) {
            return;
        }

        foreach ($rows as $row) {
            $id = (int) ($row['id'] ?? 0);
            if ($id < 1) {
                continue;
            }

            $model->update($id, [
                'nik' => (string) ($row['nik'] ?? ''),
            ]);
        }
    }

    public function down()
    {
        // No automatic decrypt rollback for security reasons.
    }
}

