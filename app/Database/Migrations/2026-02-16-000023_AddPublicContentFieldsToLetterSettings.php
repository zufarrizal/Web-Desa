<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPublicContentFieldsToLetterSettings extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('letter_settings')) {
            return;
        }

        $fields = [];
        $toAdd = [
            'village_profile_title'   => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'village_profile_content' => ['type' => 'TEXT', 'null' => true],
            'announcement_title'      => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'announcement_content'    => ['type' => 'TEXT', 'null' => true],
            'contact_person'          => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'contact_phone'           => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true],
            'contact_email'           => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'contact_whatsapp'        => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true],
            'complaint_info'          => ['type' => 'TEXT', 'null' => true],
        ];

        foreach ($toAdd as $name => $def) {
            if (! $this->db->fieldExists($name, 'letter_settings')) {
                $fields[$name] = $def;
            }
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

        $columns = [
            'village_profile_title',
            'village_profile_content',
            'announcement_title',
            'announcement_content',
            'contact_person',
            'contact_phone',
            'contact_email',
            'contact_whatsapp',
            'complaint_info',
        ];

        foreach ($columns as $column) {
            if ($this->db->fieldExists($column, 'letter_settings')) {
                $this->forge->dropColumn('letter_settings', $column);
            }
        }
    }
}

