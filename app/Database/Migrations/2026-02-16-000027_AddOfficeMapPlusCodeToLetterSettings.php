<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddOfficeMapPlusCodeToLetterSettings extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('letter_settings')) {
            return;
        }

        if (! $this->db->fieldExists('office_map_plus_code', 'letter_settings')) {
            $this->forge->addColumn('letter_settings', [
                'office_map_plus_code' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 80,
                    'null'       => true,
                ],
            ]);
        }
    }

    public function down()
    {
        if (! $this->db->tableExists('letter_settings')) {
            return;
        }

        if ($this->db->fieldExists('office_map_plus_code', 'letter_settings')) {
            $this->forge->dropColumn('letter_settings', 'office_map_plus_code');
        }
    }
}
