<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EnsureRegionFieldsInLetterSettings extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('regency_name', 'letter_settings')) {
            $this->db->query("ALTER TABLE `letter_settings` ADD `regency_name` VARCHAR(120) NULL AFTER `id`");
        }

        if (! $this->db->fieldExists('subdistrict_name', 'letter_settings')) {
            $this->db->query("ALTER TABLE `letter_settings` ADD `subdistrict_name` VARCHAR(120) NULL AFTER `regency_name`");
        }

        if (! $this->db->fieldExists('office_address', 'letter_settings')) {
            $this->db->query("ALTER TABLE `letter_settings` ADD `office_address` TEXT NULL AFTER `village_name`");
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('office_address', 'letter_settings')) {
            $this->forge->dropColumn('letter_settings', 'office_address');
        }
        if ($this->db->fieldExists('subdistrict_name', 'letter_settings')) {
            $this->forge->dropColumn('letter_settings', 'subdistrict_name');
        }
        if ($this->db->fieldExists('regency_name', 'letter_settings')) {
            $this->forge->dropColumn('letter_settings', 'regency_name');
        }
    }
}
