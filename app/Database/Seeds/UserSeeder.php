<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $now  = date('Y-m-d H:i:s');
        $data = [
            'name'          => 'Administrator',
            'email'         => 'admin@example.com',
            'password'      => password_hash('Admin123!', PASSWORD_BCRYPT),
            'role'          => 'admin',
            'updated_at'    => $now,
            'last_login_at' => null,
        ];

        $existing = $this->db->table('users')->where('email', 'admin@example.com')->get()->getRowArray();
        if ($existing) {
            $this->db->table('users')->where('id', $existing['id'])->update($data);
            return;
        }

        $data['created_at'] = $now;
        $this->db->table('users')->insert($data);
    }
}
