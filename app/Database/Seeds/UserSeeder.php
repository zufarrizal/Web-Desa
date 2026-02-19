<?php

namespace App\Database\Seeds;

use App\Models\UserModel;
use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $users = [
            [
                'name'          => 'Administrator',
                'email'         => 'admin@example.com',
                'password'      => password_hash('Admin123!', PASSWORD_BCRYPT),
                'role'          => 'admin',
                'no_kk'         => '3174010101010001',
                'nik'           => '3174010101010001',
                'birth_place'   => 'Jakarta',
                'birth_date'    => '1990-01-01',
                'gender'        => 'Laki-laki',
                'religion'      => 'Islam',
                'occupation'    => 'Perangkat Desa',
                'marital_status'=> 'Menikah',
                'address'       => 'Jl. Raya Desa No. 1',
                'rt'            => '001',
                'rw'            => '001',
                'village'       => 'Desa Maju',
                'district'      => 'Kecamatan Sejahtera',
                'city'          => 'Kabupaten Contoh',
                'province'      => 'Jawa Barat',
                'citizenship'   => 'WNI',
                'updated_at'    => $now,
                'last_login_at' => null,
            ],
            [
                'name'          => 'Warga Demo',
                'email'         => 'warga@example.com',
                'password'      => password_hash('Warga123!', PASSWORD_BCRYPT),
                'role'          => 'user',
                'no_kk'         => '3174010101010002',
                'nik'           => '3174010101010002',
                'birth_place'   => 'Bandung',
                'birth_date'    => '1995-05-10',
                'gender'        => 'Perempuan',
                'religion'      => 'Islam',
                'occupation'    => 'Karyawan',
                'marital_status'=> 'Belum Menikah',
                'address'       => 'Jl. Melati No. 12',
                'rt'            => '002',
                'rw'            => '003',
                'village'       => 'Desa Maju',
                'district'      => 'Kecamatan Sejahtera',
                'city'          => 'Kabupaten Contoh',
                'province'      => 'Jawa Barat',
                'citizenship'   => 'WNI',
                'updated_at'    => $now,
                'last_login_at' => null,
            ],
        ];

        $userModel = new UserModel();
        foreach ($users as $data) {
            $existing = $userModel->where('email', $data['email'])->first();
            if ($existing) {
                $userModel->update((int) $existing['id'], $data);
                continue;
            }

            $data['created_at'] = $now;
            $userModel->insert($data);
        }
    }
}
