<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DisableRecaptchaSeeder extends Seeder
{
    public function run()
    {
        if (! $this->db->tableExists('letter_settings')) {
            return;
        }

        $this->db->table('letter_settings')->update([
            'recaptcha_enabled' => 0,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}

