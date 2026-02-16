<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ComplaintSeeder extends Seeder
{
    public function run()
    {
        // Reset data supaya seed selalu konsisten.
        $this->db->table('complaints')->truncate();

        $user = $this->db->table('users')
            ->select('id')
            ->where('role', 'user')
            ->orderBy('id', 'ASC')
            ->get()
            ->getRowArray();

        if (! $user) {
            $user = $this->db->table('users')->select('id')->orderBy('id', 'ASC')->get()->getRowArray();
        }

        if (! $user) {
            return;
        }

        $userId = (int) $user['id'];
        $now    = time();
        $hasImagePath = $this->db->fieldExists('image_path', 'complaints');
        $rows   = [
            [
                'title' => 'Lampu Jalan Padam',
                'content' => 'Lampu jalan di RT 02 RW 01 padam sejak tiga hari terakhir dan area menjadi gelap saat malam.',
                'location' => 'RT 02 RW 01',
                'status' => 'baru',
                'response' => null,
            ],
            [
                'title' => 'Jalan Rusak Berlubang',
                'content' => 'Ada lubang cukup dalam di badan jalan yang berbahaya untuk motor dan sepeda.',
                'location' => 'Jalan Mawar',
                'status' => 'ditindaklanjuti',
                'response' => 'Sudah kami survei dan masuk jadwal perbaikan pekan ini.',
            ],
            [
                'title' => 'Saluran Drainase Tersumbat',
                'content' => 'Saat hujan, air meluap karena drainase tersumbat sampah dan lumpur.',
                'location' => 'Dusun Tengah',
                'status' => 'ditolak',
                'response' => 'Lokasi berada di jalur kewenangan kabupaten, sedang diteruskan ke dinas terkait.',
            ],
            [
                'title' => 'Sampah Menumpuk',
                'content' => 'TPS belakang balai desa penuh dan menimbulkan bau menyengat.',
                'location' => 'Belakang Balai Desa',
                'status' => 'selesai',
                'response' => 'Sudah diangkut petugas kebersihan pada pagi hari ini.',
            ],
            [
                'title' => 'Air Bersih Tidak Lancar',
                'content' => 'Debit air bersih kecil pada jam pagi dan sore sehingga warga kesulitan.',
                'location' => 'RT 03 RW 02',
                'status' => 'ditindaklanjuti',
                'response' => 'Tim teknis sedang pengecekan pipa distribusi utama.',
            ],
        ];

        foreach ($rows as $i => $row) {
            $createdAt = date('Y-m-d H:i:s', $now - (($i + 1) * 3600));
            $payload = [
                'user_id'    => $userId,
                'title'      => $row['title'],
                'content'    => $row['content'],
                'location'   => $row['location'],
                'status'     => $row['status'],
                'response'   => $row['response'],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];

            if ($hasImagePath) {
                $payload['image_path'] = null;
            }

            $this->db->table('complaints')->insert($payload);
        }
    }
}
