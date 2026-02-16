<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropLegacyTables extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('program_posts')) {
            $this->forge->dropTable('program_posts', true);
        }

        if ($this->db->tableExists('tasks')) {
            $this->forge->dropTable('tasks', true);
        }
    }

    public function down()
    {
        // Tabel legacy tidak dikembalikan.
    }
}
