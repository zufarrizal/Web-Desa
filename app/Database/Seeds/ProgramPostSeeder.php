<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProgramPostSeeder extends Seeder
{
    public function run()
    {
        if (! $this->db->tableExists('programs')
            || ! $this->db->tableExists('articles')
            || ! $this->db->tableExists('activities')
            || ! $this->db->tableExists('announcements')) {
            return;
        }

        $programTable = $this->db->table('programs');
        $articleTable = $this->db->table('articles');
        $activityTable = $this->db->table('activities');
        $announcementTable = $this->db->table('announcements');

        // Reset data supaya seed ulang selalu konsisten.
        $programTable->truncate();
        $articleTable->truncate();
        $activityTable->truncate();
        $announcementTable->truncate();

        $user  = $this->db->table('users')->select('id')->orderBy('id', 'ASC')->get()->getRowArray();
        if (! $user) {
            return;
        }

        $authorId = (int) $user['id'];
        $now      = time();

        $seedMap = [
            'program' => [
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
            ],
            'artikel' => [
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
            ],
            'kegiatan' => [
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
            ],
            'pengumuman' => [
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
            ],
        ];

        $typeTableMap = [
            'program' => $programTable,
            'artikel' => $articleTable,
            'kegiatan' => $activityTable,
            'pengumuman' => $announcementTable,
        ];

        $index = 0;
        foreach ($seedMap as $type => $titles) {
            $table = $typeTableMap[$type] ?? null;
            if (! $table) {
                continue;
            }

            foreach ($titles as $title) {
                $index++;
                $slug = 'dummy-' . $type . '-' . url_title($title, '-', true);
                $publishedAt = date('Y-m-d H:i:s', $now - ($index * 3600));
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
}
