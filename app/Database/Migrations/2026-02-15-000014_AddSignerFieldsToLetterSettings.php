<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSignerFieldsToLetterSettings extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('signer_title', 'letter_settings')) {
            $this->db->query("ALTER TABLE `letter_settings` ADD `signer_title` VARCHAR(80) NULL AFTER `app_icon`");
        }

        if (! $this->db->fieldExists('signer_name', 'letter_settings')) {
            $this->db->query("ALTER TABLE `letter_settings` ADD `signer_name` VARCHAR(120) NULL AFTER `signer_title`");
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('signer_name', 'letter_settings')) {
            $this->forge->dropColumn('letter_settings', 'signer_name');
        }

        if ($this->db->fieldExists('signer_title', 'letter_settings')) {
            $this->forge->dropColumn('letter_settings', 'signer_title');
        }
    }
}

