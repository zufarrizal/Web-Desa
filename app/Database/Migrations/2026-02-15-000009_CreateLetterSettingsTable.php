<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLetterSettingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'village_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
            ],
            'letterhead_address' => [
                'type' => 'TEXT',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('letter_settings');
    }

    public function down()
    {
        $this->forge->dropTable('letter_settings');
    }
}
