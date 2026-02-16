<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Config\Database;

class LetterSettingSeeder extends Seeder
{
    public function run()
    {
        $db = Database::connect();
        $table = $db->table('letter_settings');

        $data = [
            'regency_name'            => 'Kabupaten Kebumen',
            'subdistrict_name'        => 'Kecamatan Kebumen',
            'village_name'            => 'Gemeksekti',
            'office_address'          => 'Jl. Raya Gemeksekti No. 1, Kebumen',
            'letterhead_address'      => 'Jl. Raya Gemeksekti No. 1, Kebumen',
            'app_icon'                => 'home',
            'signer_title'            => 'Kepala Desa',
            'signer_name'             => 'Sutrisno',
            'village_profile_title'   => 'Profil Desa Gemeksekti',
            'village_profile_content' => 'Desa Gemeksekti berkomitmen memberikan pelayanan administrasi yang cepat, transparan, dan mudah diakses oleh seluruh warga. Fokus pembangunan desa mencakup peningkatan kualitas layanan publik, pemberdayaan ekonomi warga, serta penguatan kegiatan sosial kemasyarakatan.',
            'announcement_title'      => 'Pengumuman Desa',
            'announcement_content'    => 'Informasi resmi dari pemerintah desa akan ditampilkan pada bagian Pengumuman. Warga diharapkan memantau halaman utama secara berkala untuk mendapatkan update kegiatan dan layanan terbaru.',
            'contact_person'          => 'Sekretariat Desa Gemeksekti',
            'contact_phone'           => '0287-123456',
            'contact_email'           => 'desa.gemeksekti@example.com',
            'contact_whatsapp'        => '6281234567890',
            'complaint_info'          => 'Pengaduan warga dapat diajukan melalui menu Pengaduan dengan melampirkan data pendukung. Aduan akan diproses sesuai antrean dan status tindak lanjut dapat dipantau langsung di dashboard.',
            'updated_at'              => date('Y-m-d H:i:s'),
        ];

        $existing = $table->get()->getFirstRow('array');

        if ($existing) {
            $table->where('id', (int) $existing['id'])->update($data);
            return;
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        $table->insert($data);
    }
}
