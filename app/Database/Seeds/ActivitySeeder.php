<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run()
    {
        helper('url');

        if (! $this->db->tableExists('activities')) {
            return;
        }

        $user = $this->db->table('users')->select('id')->orderBy('id', 'ASC')->get()->getRowArray();
        if (! $user) {
            return;
        }

        $table = $this->db->table('activities');
        $table->truncate();

        $authorId = (int) $user['id'];
        $now = time();
        $titles = [
            'Kegiatan Gotong Royong Bersih Desa',
            'Kegiatan Posyandu Bulanan Balita',
            'Kegiatan Pelatihan UMKM Pemuda',
            'Kegiatan Musyawarah Perencanaan Desa',
            'Kegiatan Sosialisasi Layanan Administrasi',
            'Kegiatan Senam Sehat Lansia',
            'Kegiatan Donor Darah Desa',
            'Kegiatan Pelatihan Pertanian Organik',
            'Kegiatan Lomba Kebersihan Antar RT',
            'Kegiatan Edukasi Mitigasi Bencana',
        ];

        foreach ($titles as $index => $title) {
            $publishedAt = date('Y-m-d H:i:s', $now - (($index + 1) * 3600));
            $slug = 'dummy-kegiatan-' . url_title($title, '-', true);
            $excerpt = "Dummy kegiatan: {$title}.";

            $table->insert([
                'user_id' => $authorId,
                'title' => $title,
                'post_type' => 'kegiatan',
                'slug' => $slug,
                'excerpt' => $excerpt,
                'image_path' => null,
                'content' => "{$title}\n\nIni adalah konten dummy untuk kegiatan desa. Data ini digunakan untuk demo tampilan halaman publik dan pengujian fitur posting.",
                'seo_title' => $title . ' | Portal Desa',
                'seo_description' => mb_strimwidth($excerpt, 0, 155, '...'),
                'seo_keywords' => 'kegiatan, desa, portal desa',
                'published_at' => $publishedAt,
                'created_at' => $publishedAt,
                'updated_at' => $publishedAt,
            ]);
        }
    }
}
