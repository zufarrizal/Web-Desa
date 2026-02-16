<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run()
    {
        helper('url');

        if (! $this->db->tableExists('articles')) {
            return;
        }

        $user = $this->db->table('users')->select('id')->orderBy('id', 'ASC')->get()->getRowArray();
        if (! $user) {
            return;
        }

        $table = $this->db->table('articles');
        $table->truncate();

        $authorId = (int) $user['id'];
        $now = time();
        $titles = [
            'Artikel: Cara Mengurus Surat Domisili',
            'Artikel: Panduan Lengkapi Profil Warga',
            'Artikel: Manfaat Pelayanan Online Desa',
            'Artikel: Alur Pengajuan Pengaduan Warga',
            'Artikel: Transparansi Program Desa',
            'Artikel: Tips Menjaga Kebersihan Lingkungan',
            'Artikel: Pentingnya Data Kependudukan Akurat',
            'Artikel: Panduan Mengurus Surat Keterangan Usaha',
            'Artikel: Peran Warga dalam Musyawarah Desa',
            'Artikel: Edukasi Cegah Stunting di Desa',
        ];

        foreach ($titles as $index => $title) {
            $publishedAt = date('Y-m-d H:i:s', $now - (($index + 1) * 3600));
            $slug = 'dummy-artikel-' . url_title($title, '-', true);
            $excerpt = "Dummy artikel: {$title}.";

            $table->insert([
                'user_id' => $authorId,
                'title' => $title,
                'post_type' => 'artikel',
                'slug' => $slug,
                'excerpt' => $excerpt,
                'image_path' => null,
                'content' => "{$title}\n\nIni adalah konten dummy untuk artikel desa. Data ini digunakan untuk demo tampilan halaman publik dan pengujian fitur posting.",
                'seo_title' => $title . ' | Portal Desa',
                'seo_description' => mb_strimwidth($excerpt, 0, 155, '...'),
                'seo_keywords' => 'artikel, desa, portal desa',
                'published_at' => $publishedAt,
                'created_at' => $publishedAt,
                'updated_at' => $publishedAt,
            ]);
        }
    }
}
