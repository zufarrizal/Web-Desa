<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MakeDocumentRequestNikEncryptable extends Migration
{
    public function up()
    {
        if ($this->db->fieldExists('nik', 'document_requests')) {
            $this->forge->modifyColumn('document_requests', [
                'nik' => [
                    'type' => 'TEXT',
                    'null' => false,
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('nik', 'document_requests')) {
            $this->forge->modifyColumn('document_requests', [
                'nik' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 30,
                    'null'       => false,
                ],
            ]);
        }
    }
}

