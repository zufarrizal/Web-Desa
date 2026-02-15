<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EnsureAppIconInLetterSettings extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('app_icon', 'letter_settings')) {
            $this->db->query("ALTER TABLE `letter_settings` ADD `app_icon` VARCHAR(50) NULL AFTER `office_address`");
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('app_icon', 'letter_settings')) {
            $this->forge->dropColumn('letter_settings', 'app_icon');
        }
    }
}
