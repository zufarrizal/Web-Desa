<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSignerSignatureToLetterSettings extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('signer_signature', 'letter_settings')) {
            $this->db->query("ALTER TABLE `letter_settings` ADD `signer_signature` VARCHAR(255) NULL AFTER `signer_name`");
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('signer_signature', 'letter_settings')) {
            $this->forge->dropColumn('letter_settings', 'signer_signature');
        }
    }
}

