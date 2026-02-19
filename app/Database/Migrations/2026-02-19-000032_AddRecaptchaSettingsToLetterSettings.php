<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRecaptchaSettingsToLetterSettings extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('letter_settings')) {
            return;
        }

        $fields = [];
        if (! $this->db->fieldExists('recaptcha_enabled', 'letter_settings')) {
            $fields['recaptcha_enabled'] = [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'null' => false,
            ];
        }
        if (! $this->db->fieldExists('recaptcha_site_key', 'letter_settings')) {
            $fields['recaptcha_site_key'] = [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ];
        }
        if (! $this->db->fieldExists('recaptcha_secret_key', 'letter_settings')) {
            $fields['recaptcha_secret_key'] = [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
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

        if ($this->db->fieldExists('recaptcha_secret_key', 'letter_settings')) {
            $this->forge->dropColumn('letter_settings', 'recaptcha_secret_key');
        }
        if ($this->db->fieldExists('recaptcha_site_key', 'letter_settings')) {
            $this->forge->dropColumn('letter_settings', 'recaptcha_site_key');
        }
        if ($this->db->fieldExists('recaptcha_enabled', 'letter_settings')) {
            $this->forge->dropColumn('letter_settings', 'recaptcha_enabled');
        }
    }
}

