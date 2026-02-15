<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProgramPostSeeder extends Seeder
{
    public function run()
    {
        $table = $this->db->table('program_posts');
        $user  = $this->db->table('users')->select('id')->orderBy('id', 'ASC')->get()->getRowArray();
        if (! $user) {
            return;
        }

        $authorId = (int) $user['id'];
        $now      = time();

        $rows = [
            ['Program Ketahanan Pangan Desa', 'dummy-program-ketahanan-pangan-desa', 'program'],
            ['Program Peningkatan Jalan Lingkungan', 'dummy-program-peningkatan-jalan-lingkungan', 'program'],
            ['Program Sanitasi dan Air Bersih', 'dummy-program-sanitasi-dan-air-bersih', 'program'],
            ['Program Pemberdayaan UMKM Desa', 'dummy-program-pemberdayaan-umkm-desa', 'program'],
            ['Program Digitalisasi Pelayanan Desa', 'dummy-program-digitalisasi-pelayanan-desa', 'program'],

            ['Artikel: Cara Mengurus Surat Domisili', 'dummy-artikel-cara-mengurus-surat-domisili', 'artikel'],
            ['Artikel: Panduan Lengkapi Profil Warga', 'dummy-artikel-panduan-lengkapi-profil-warga', 'artikel'],
            ['Artikel: Manfaat Pelayanan Online Desa', 'dummy-artikel-manfaat-pelayanan-online-desa', 'artikel'],
            ['Artikel: Alur Pengajuan Pengaduan Warga', 'dummy-artikel-alur-pengajuan-pengaduan-warga', 'artikel'],
            ['Artikel: Transparansi Program Desa', 'dummy-artikel-transparansi-program-desa', 'artikel'],

            ['Kegiatan Gotong Royong Bersih Desa', 'dummy-kegiatan-gotong-royong-bersih-desa', 'kegiatan'],
            ['Kegiatan Posyandu Bulanan Balita', 'dummy-kegiatan-posyandu-bulanan-balita', 'kegiatan'],
            ['Kegiatan Pelatihan UMKM Pemuda', 'dummy-kegiatan-pelatihan-umkm-pemuda', 'kegiatan'],
            ['Kegiatan Musyawarah Perencanaan Desa', 'dummy-kegiatan-musyawarah-perencanaan-desa', 'kegiatan'],
            ['Kegiatan Sosialisasi Layanan Administrasi', 'dummy-kegiatan-sosialisasi-layanan-administrasi', 'kegiatan'],
        ];

        foreach ($rows as $i => $row) {
            [$title, $slug, $type] = $row;
            $exists = $table->where('slug', $slug)->get()->getRowArray();
            if ($exists) {
                continue;
            }

            $publishedAt = date('Y-m-d H:i:s', $now - (($i + 1) * 3600));
            $excerpt = "Dummy {$type}: {$title}.";
            $content = "{$title}\n\nIni adalah konten dummy untuk {$type} desa. Data ini digunakan untuk kebutuhan demo tampilan halaman publik, SEO, dan pengujian fitur posting.";

            $table->insert([
                'user_id'          => $authorId,
                'title'            => $title,
                'post_type'        => $type,
                'slug'             => $slug,
                'excerpt'          => $excerpt,
                'image_path'       => null,
                'content'          => $content,
                'seo_title'        => $title . ' | Portal Desa',
                'seo_description'  => mb_strimwidth($excerpt, 0, 155, '...'),
                'seo_keywords'     => "{$type}, desa, portal desa",
                'published_at'     => $publishedAt,
                'created_at'       => $publishedAt,
                'updated_at'       => $publishedAt,
            ]);
        }
    }
}
