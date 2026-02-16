<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run()
    {
        helper('url');

        if (! $this->db->tableExists('announcements')) {
            return;
        }

        $user = $this->db->table('users')->select('id')->orderBy('id', 'ASC')->get()->getRowArray();
        if (! $user) {
            return;
        }

        $table = $this->db->table('announcements');
        $table->truncate();

        $authorId = (int) $user['id'];
        $now = time();
        $titles = [
            'Pengumuman Jadwal Pelayanan Akhir Pekan',
            'Pengumuman Pembayaran Iuran Kebersihan',
            'Pengumuman Jadwal Posyandu Bulan Ini',
            'Pengumuman Penyaluran Bantuan Sosial',
            'Pengumuman Rapat Warga Dusun Utara',
            'Pengumuman Pendaftaran Kegiatan Karang Taruna',
            'Pengumuman Imbauan Keamanan Lingkungan',
            'Pengumuman Perbaikan Jalan Desa',
            'Pengumuman Jadwal Vaksinasi Massal',
            'Pengumuman Libur Pelayanan Administrasi',
        ];

        foreach ($titles as $index => $title) {
            $publishedAt = date('Y-m-d H:i:s', $now - (($index + 1) * 3600));
            $slug = 'dummy-pengumuman-' . url_title($title, '-', true);
            $excerpt = "Dummy pengumuman: {$title}.";

            $table->insert([
                'user_id' => $authorId,
                'title' => $title,
                'post_type' => 'pengumuman',
                'slug' => $slug,
                'excerpt' => $excerpt,
                'image_path' => null,
                'content' => "{$title}\n\nIni adalah konten dummy untuk pengumuman desa. Data ini digunakan untuk demo tampilan halaman publik dan pengujian fitur posting.",
                'seo_title' => $title . ' | Portal Desa',
                'seo_description' => mb_strimwidth($excerpt, 0, 155, '...'),
                'seo_keywords' => 'pengumuman, desa, portal desa',
                'published_at' => $publishedAt,
                'created_at' => $publishedAt,
                'updated_at' => $publishedAt,
            ]);
        }
    }
}
