<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddOfficeCoordinatesToLetterSettings extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('letter_settings')) {
            return;
        }

        $fields = [];

        if (! $this->db->fieldExists('office_latitude', 'letter_settings')) {
            $fields['office_latitude'] = [
                'type'       => 'DECIMAL',
                'constraint' => '10,7',
                'null'       => true,
            ];
        }

        if (! $this->db->fieldExists('office_longitude', 'letter_settings')) {
            $fields['office_longitude'] = [
                'type'       => 'DECIMAL',
                'constraint' => '10,7',
                'null'       => true,
            ];
        }

        if ($fields !== []) {
            $this->forge->addColumn('letter_settings', $fields);
        }
    }

    public function down()
    {
        if (! $this->db->tableExists('letter_settings')) {
            return;
        }

        foreach (['office_latitude', 'office_longitude'] as $column) {
            if ($this->db->fieldExists($column, 'letter_settings')) {
                $this->forge->dropColumn('letter_settings', $column);
            }
        }
    }
}
