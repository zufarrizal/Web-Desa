<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddImagePathToComplaints extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('image_path', 'complaints')) {
            $this->forge->addColumn('complaints', [
                'image_path' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                    'after'      => 'location',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('image_path', 'complaints')) {
            $this->forge->dropColumn('complaints', 'image_path');
        }
    }
}

