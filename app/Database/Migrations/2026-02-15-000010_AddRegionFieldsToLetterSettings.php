<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRegionFieldsToLetterSettings extends Migration
{
    public function up()
    {
        $fields = [
            'regency_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
                'null'       => true,
                'after'      => 'id',
            ],
            'subdistrict_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
                'null'       => true,
                'after'      => 'regency_name',
            ],
            'office_address' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'village_name',
            ],
        ];

        foreach ($fields as $name => $definition) {
            if (! $this->db->fieldExists($name, 'letter_settings')) {
                $this->forge->addColumn('letter_settings', [$name => $definition]);
            }
        }
    }

    public function down()
    {
        $columns = ['regency_name', 'subdistrict_name', 'office_address'];

        foreach ($columns as $column) {
            if ($this->db->fieldExists($column, 'letter_settings')) {
                $this->forge->dropColumn('letter_settings', $column);
            }
        }
    }
}
