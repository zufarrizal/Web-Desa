<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run()
    {
        helper('url');

        if (! $this->db->tableExists('programs')) {
            return;
        }

        $user = $this->db->table('users')->select('id')->orderBy('id', 'ASC')->get()->getRowArray();
        if (! $user) {
            return;
        }

        $table = $this->db->table('programs');
        $table->truncate();

        $authorId = (int) $user['id'];
        $now = time();
        $titles = [
            'Program Ketahanan Pangan Desa',
            'Program Peningkatan Jalan Lingkungan',
            'Program Sanitasi dan Air Bersih',
            'Program Pemberdayaan UMKM Desa',
            'Program Digitalisasi Pelayanan Desa',
            'Program Renovasi Posyandu',
            'Program Beasiswa Anak Desa',
            'Program Pelatihan Literasi Digital',
            'Program Penguatan Karang Taruna',
            'Program Pengelolaan Sampah Terpadu',
        ];

        foreach ($titles as $index => $title) {
            $publishedAt = date('Y-m-d H:i:s', $now - (($index + 1) * 3600));
            $slug = 'dummy-program-' . url_title($title, '-', true);
            $excerpt = "Dummy program: {$title}.";

            $table->insert([
                'user_id' => $authorId,
                'title' => $title,
                'post_type' => 'program',
                'slug' => $slug,
                'excerpt' => $excerpt,
                'image_path' => null,
                'content' => "{$title}\n\nIni adalah konten dummy untuk program desa. Data ini digunakan untuk demo tampilan halaman publik dan pengujian fitur posting.",
                'seo_title' => $title . ' | Portal Desa',
                'seo_description' => mb_strimwidth($excerpt, 0, 155, '...'),
                'seo_keywords' => 'program, desa, portal desa',
                'published_at' => $publishedAt,
                'created_at' => $publishedAt,
                'updated_at' => $publishedAt,
            ]);
        }
    }
}
