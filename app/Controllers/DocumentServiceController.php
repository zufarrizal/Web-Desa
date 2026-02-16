<?php

namespace App\Controllers;

use App\Models\DocumentRequestModel;
use App\Models\LetterSettingModel;
use App\Models\UserModel;

class DocumentServiceController extends BaseController
{
    private const DOC_TYPES = [
        'domisili'         => 'Surat Keterangan Domisili',
        'sku'              => 'Surat Keterangan Usaha (SKU)',
        'sktm'             => 'Surat Keterangan Tidak Mampu (SKTM)',
        'belum-menikah'    => 'Surat Keterangan Belum Menikah',
        'kematian'         => 'Surat Keterangan Kematian',
        'kelahiran'        => 'Surat Keterangan Kelahiran',
        'pengantar-skck'   => 'Surat Pengantar SKCK',
        'pengantar-ktp-kk' => 'Surat Pengantar Pembuatan KTP / KK',
        'dukcapil-kk'      => 'Pembuatan atau perubahan Kartu Keluarga (KK)',
        'dukcapil-ektp'    => 'Perekaman atau pencetakan e-KTP',
        'dukcapil-akta-lahir' => 'Akta Kelahiran',
        'dukcapil-akta-mati'  => 'Akta Kematian',
        'dukcapil-akta-kawin-cerai' => 'Akta Perkawinan / Perceraian',
        'tanah-skt'        => 'Surat Keterangan Tanah (SKT)',
        'tanah-riwayat'    => 'Surat Riwayat Tanah',
        'tanah-pengantar-bpn' => 'Surat Pengantar Sertifikat Tanah (ke BPN)',
        'tanah-penguasaan-fisik' => 'Surat Pernyataan Penguasaan Fisik Tanah',
        'izin-mikro'       => 'Rekomendasi Izin Usaha Mikro',
        'izin-keramaian'   => 'Rekomendasi Izin Keramaian',
        'izin-proposal'    => 'Rekomendasi Proposal Bantuan',
        'sosial-bpjs'      => 'Pengantar BPJS / JKN',
        'sosial-bansos'    => 'Rekomendasi bantuan sosial (PKH, BLT, dll.)',
        'sosial-sekolah'   => 'Surat pengantar sekolah / beasiswa',
    ];

    private const DOC_GROUPS = [
        'Surat Keterangan Umum' => [
            'domisili',
            'sku',
            'sktm',
            'belum-menikah',
            'kematian',
            'kelahiran',
            'pengantar-skck',
            'pengantar-ktp-kk',
        ],
        'Administrasi Kependudukan (Pengantar ke Dukcapil)' => [
            'dukcapil-kk',
            'dukcapil-ektp',
            'dukcapil-akta-lahir',
            'dukcapil-akta-mati',
            'dukcapil-akta-kawin-cerai',
        ],
        'Dokumen Pertanahan' => [
            'tanah-skt',
            'tanah-riwayat',
            'tanah-pengantar-bpn',
            'tanah-penguasaan-fisik',
        ],
        'Perizinan Sederhana' => [
            'izin-mikro',
            'izin-keramaian',
            'izin-proposal',
        ],
        'Administrasi Sosial dan Bantuan' => [
            'sosial-bpjs',
            'sosial-bansos',
            'sosial-sekolah',
        ],
    ];

    public function index()
    {
        $role    = (string) session()->get('user_role');
        $userId  = (int) session()->get('user_id');
        $model   = new DocumentRequestModel();
        $builder = $model->select('document_requests.*, users.name as user_name')
            ->join('users', 'users.id = document_requests.user_id', 'left');

        if ($role === 'admin') {
            $builder
                ->orderBy("CASE WHEN document_requests.status = 'selesai' THEN 1 ELSE 0 END", 'ASC', false)
                ->orderBy('document_requests.created_at', 'ASC')
                ->orderBy('document_requests.id', 'ASC');
        } else {
            $builder->where('document_requests.user_id', $userId);
            $builder->orderBy('document_requests.id', 'DESC');
        }

        return view('documents/index', [
            'role'      => $role,
            'requests'  => $builder->findAll(),
            'docGroups' => $this->groupedDocTypes(),
            'hasProfile'=> $this->hasRequiredProfile($this->currentUser()),
        ]);
    }

    public function generate(string $type)
    {
        $docType = self::DOC_TYPES[$type] ?? null;
        if (! $docType) {
            return redirect()->to('/documents')->with('error', 'Jenis surat tidak dikenali.');
        }

        $user = $this->currentUser();
        if (! $this->hasRequiredProfile($user)) {
            return redirect()->to('/profile')->with('error', 'Lengkapi profil terlebih dahulu sebelum membuat surat.');
        }

        $model = new DocumentRequestModel();
        $id = $model->insert([
            'user_id'       => (int) session()->get('user_id'),
            'citizen_name'  => $user['name'],
            'nik'           => $user['nik'],
            'document_type' => $docType,
            'description'   => '',
            'status'        => 'diajukan',
            'admin_notes'   => null,
        ], true);

        return redirect()->to('/documents/preview/' . $id)->with('success', 'Surat berhasil dibuat dari data profil.');
    }

    public function createManual(string $type)
    {
        $docType = self::DOC_TYPES[$type] ?? null;
        if (! $docType) {
            return redirect()->to('/documents')->with('error', 'Jenis surat tidak dikenali.');
        }

        return view('documents/manual_form', [
            'docTypeKey' => $type,
            'docTypeLabel' => $docType,
        ]);
    }

    public function storeManual(string $type)
    {
        $docType = self::DOC_TYPES[$type] ?? null;
        if (! $docType) {
            return redirect()->to('/documents')->with('error', 'Jenis surat tidak dikenali.');
        }

        $rules = [
            'name' => 'required|min_length[3]|max_length[120]',
            'nik' => 'required|min_length[8]|max_length[30]',
            'birth_place' => 'required|min_length[3]|max_length[120]',
            'birth_date' => 'required|valid_date',
            'gender' => 'required|in_list[Laki-laki,Perempuan]',
            'occupation' => 'required|min_length[3]|max_length[120]',
            'address' => 'required|min_length[5]',
            'village' => 'required|min_length[3]|max_length[120]',
            'district' => 'required|min_length[3]|max_length[120]',
            'city' => 'required|min_length[3]|max_length[120]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $manualCitizen = [
            'name' => (string) $this->request->getPost('name'),
            'nik' => (string) $this->request->getPost('nik'),
            'birth_place' => (string) $this->request->getPost('birth_place'),
            'birth_date' => (string) $this->request->getPost('birth_date'),
            'gender' => (string) $this->request->getPost('gender'),
            'occupation' => (string) $this->request->getPost('occupation'),
            'address' => (string) $this->request->getPost('address'),
            'village' => (string) $this->request->getPost('village'),
            'district' => (string) $this->request->getPost('district'),
            'city' => (string) $this->request->getPost('city'),
        ];

        $model = new DocumentRequestModel();
        $id = $model->insert([
            'user_id' => (int) session()->get('user_id'),
            'citizen_name' => $manualCitizen['name'],
            'nik' => $manualCitizen['nik'],
            'document_type' => $docType,
            'description' => json_encode([
                'manual_input' => true,
                'citizen' => $manualCitizen,
            ], JSON_UNESCAPED_UNICODE),
            'status' => 'diajukan',
            'admin_notes' => null,
        ], true);

        return redirect()->to('/documents/preview/' . $id)->with('success', 'Surat manual berhasil dibuat.');
    }

    public function preview(int $id)
    {
        $payload = $this->getLetterPayload($id);
        if (! $payload) {
            return redirect()->to('/documents')->with('error', 'Data surat tidak ditemukan atau tidak diizinkan.');
        }
        return view('documents/preview', $payload);
    }

    public function print(int $id)
    {
        $payload = $this->getLetterPayload($id);
        if (! $payload) {
            return redirect()->to('/documents')->with('error', 'Data surat tidak ditemukan atau tidak diizinkan.');
        }

        return view('documents/print', $payload);
    }

    public function setStatus(int $id)
    {
        if ((string) session()->get('user_role') !== 'admin') {
            return redirect()->to('/documents')->with('error', 'Hanya admin yang bisa mengubah status surat.');
        }

        $request = $this->findAuthorizedRequest($id);
        if (! $request) {
            return redirect()->to('/documents')->with('error', 'Data surat tidak ditemukan.');
        }

        $rules = [
            'status' => 'required|in_list[diajukan,diproses,selesai,ditolak]',
            'admin_notes' => 'permit_empty',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/documents')->with('error', 'Status surat tidak valid.');
        }

        $model = new DocumentRequestModel();
        $model->update($id, [
            'status' => (string) $this->request->getPost('status'),
            'admin_notes' => (string) $this->request->getPost('admin_notes'),
        ]);

        return redirect()->to('/documents')->with('success', 'Status surat berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $request = $this->findAuthorizedRequest($id);
        if (! $request) {
            return redirect()->to('/documents')->with('error', 'Data surat tidak ditemukan atau tidak diizinkan.');
        }

        $model = new DocumentRequestModel();
        $model->delete($id);

        return redirect()->to('/documents')->with('success', 'Data surat berhasil dihapus.');
    }

    public function settings()
    {
        if ((string) session()->get('user_role') !== 'admin') {
            return redirect()->to('/documents')->with('error', 'Hanya admin yang bisa mengatur kop surat.');
        }

        $settingModel = new LetterSettingModel();
        $setting      = $settingModel->first();

        return view('documents/settings', ['setting' => $setting]);
    }

    public function updateSettings()
    {
        if ((string) session()->get('user_role') !== 'admin') {
            return redirect()->to('/documents')->with('error', 'Hanya admin yang bisa mengatur kop surat.');
        }

        $rules = [
            'regency_name' => 'required|min_length[3]',
            'subdistrict_name' => 'required|min_length[3]',
            'village_name' => 'required|min_length[3]',
            'office_address' => 'required|min_length[5]',
            'app_icon' => 'permit_empty|alpha_dash|max_length[50]',
            'signer_title' => 'required|min_length[3]|max_length[80]',
            'signer_name' => 'required|min_length[3]|max_length[120]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $settingModel = new LetterSettingModel();
        $setting      = $settingModel->first();
        $payload      = [
            'regency_name'      => (string) $this->request->getPost('regency_name'),
            'subdistrict_name'  => (string) $this->request->getPost('subdistrict_name'),
            'village_name'       => (string) $this->request->getPost('village_name'),
            'office_address'     => (string) $this->request->getPost('office_address'),
            'app_icon'           => (string) ($this->request->getPost('app_icon') ?: 'home'),
            'signer_title'       => (string) $this->request->getPost('signer_title'),
            'signer_name'        => (string) $this->request->getPost('signer_name'),
            'letterhead_address' => (string) $this->request->getPost('office_address'),
        ];
        $signatureFile = $this->request->getFile('signer_signature');
        if ($signatureFile && $signatureFile->isValid() && ! $signatureFile->hasMoved()) {
            $allowedMime = ['image/png', 'image/jpeg'];
            if (! in_array((string) $signatureFile->getMimeType(), $allowedMime, true)) {
                return redirect()->back()->withInput()->with('errors', ['File tanda tangan harus PNG/JPG/JPEG.']);
            }

            if ((int) $signatureFile->getSizeByUnit('kb') > 2048) {
                return redirect()->back()->withInput()->with('errors', ['Ukuran file tanda tangan maksimal 2MB.']);
            }

            $uploadDir = FCPATH . 'uploads/signatures';
            if (! is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $newName = 'sign-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $signatureFile->getExtension();
            $signatureFile->move($uploadDir, $newName, true);
            $payload['signer_signature'] = 'uploads/signatures/' . $newName;

            if ($setting && ! empty($setting['signer_signature'])) {
                $oldPath = FCPATH . ltrim((string) $setting['signer_signature'], '/\\');
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }
        }

        if ($this->request->getPost('remove_signer_signature') === '1') {
            $payload['signer_signature'] = null;
            if ($setting && ! empty($setting['signer_signature'])) {
                $oldPath = FCPATH . ltrim((string) $setting['signer_signature'], '/\\');
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }
        }

        if ($setting) {
            $settingModel->update((int) $setting['id'], $payload);
        } else {
            $settingModel->insert($payload);
        }

        return redirect()->to('/documents/settings')->with('success', 'Pengaturan kop surat berhasil diperbarui.');
    }

    private function findAuthorizedRequest(int $id): ?array
    {
        $model   = new DocumentRequestModel();
        $request = $model->find($id);
        if (! $request) {
            return null;
        }

        if ((string) session()->get('user_role') !== 'admin' && (int) $request['user_id'] !== (int) session()->get('user_id')) {
            return null;
        }

        return $request;
    }

    private function currentUser(): array
    {
        $userModel = new UserModel();
        return (array) $userModel->find((int) session()->get('user_id'));
    }

    private function hasRequiredProfile(array $user): bool
    {
        $required = [
            'name', 'nik', 'birth_place', 'birth_date', 'gender', 'occupation', 'address', 'village', 'district', 'city',
        ];

        foreach ($required as $key) {
            if (! isset($user[$key]) || trim((string) $user[$key]) === '') {
                return false;
            }
        }

        return true;
    }

    private function letterBody(string $documentType, array $citizen): string
    {
        $name = $citizen['name'] ?? '-';
        $nik  = $citizen['nik'] ?? '-';
        $birth = ($citizen['birth_place'] ?? '-') . ', ' . ($citizen['birth_date'] ?? '-');
        $address = trim((string) ($citizen['address'] ?? '-'));
        $job = $citizen['occupation'] ?? '-';

        $map = [
            'Surat Keterangan Domisili' => "Berdasarkan penelitian administrasi kependudukan desa, nama tersebut di atas benar berdomisili di wilayah desa ini.\n\nSurat keterangan domisili ini diberikan untuk keperluan administrasi yang sah.",
            'Surat Keterangan Usaha (SKU)' => "Nama tersebut di atas benar memiliki/menjalankan usaha di wilayah desa ini.\n\nData pokok usaha:\n- Pemilik usaha : {$name}\n- Alamat usaha  : {$address}\n\nSurat ini diberikan untuk keperluan pengurusan administrasi usaha.",
            'Surat Keterangan Tidak Mampu (SKTM)' => "Berdasarkan data sosial desa, nama tersebut di atas termasuk keluarga tidak mampu dan layak memperoleh keterangan administrasi.\n\nSurat ini dipergunakan sebagai syarat pengurusan bantuan/layanan sosial yang berlaku.",
            'Surat Keterangan Belum Menikah' => "Sepanjang pengetahuan pemerintah desa dan berdasarkan data administrasi, nama tersebut di atas sampai saat surat ini diterbitkan berstatus belum menikah.\n\nSurat ini diberikan untuk keperluan administrasi yang sah.",
            'Surat Keterangan Kematian' => "Menerangkan bahwa telah terjadi peristiwa kematian warga dengan identitas sebagaimana tersebut di atas.\n\nSurat keterangan ini dipergunakan untuk pengurusan administrasi kependudukan dan keperluan lain yang berkaitan.",
            'Surat Keterangan Kelahiran' => "Menerangkan bahwa keluarga dari warga yang bersangkutan telah melaporkan peristiwa kelahiran untuk keperluan administrasi.\n\nSurat ini dipergunakan sebagai kelengkapan pengurusan administrasi kelahiran.",
            'Surat Pengantar SKCK' => "Nama tersebut di atas benar warga desa ini dan berdasarkan data yang ada tidak sedang tercatat dalam perkara pidana di tingkat pemerintahan desa.\n\nSurat pengantar ini dipergunakan untuk kelengkapan pengurusan SKCK pada instansi berwenang.",
            'Surat Pengantar Pembuatan KTP / KK' => "Nama tersebut di atas benar warga desa ini.\n\nSurat pengantar ini diberikan untuk kelengkapan pengurusan pembuatan/perubahan KTP dan/atau KK pada Dinas Kependudukan dan Pencatatan Sipil.",
            'Pembuatan atau perubahan Kartu Keluarga (KK)' => "Nama tersebut di atas benar warga desa ini dan mengajukan pelayanan pembuatan/perubahan Kartu Keluarga (KK).\n\nSurat ini sebagai pengantar administrasi ke Dukcapil.",
            'Perekaman atau pencetakan e-KTP' => "Nama tersebut di atas benar warga desa ini dan mengajukan perekaman/pencetakan e-KTP.\n\nSurat ini sebagai pengantar administrasi ke Dukcapil.",
            'Akta Kelahiran' => "Nama tersebut di atas mengajukan pengurusan Akta Kelahiran.\n\nSurat pengantar ini dipergunakan sebagai kelengkapan administrasi pada Dukcapil.",
            'Akta Kematian' => "Nama tersebut di atas mengajukan pengurusan Akta Kematian.\n\nSurat pengantar ini dipergunakan sebagai kelengkapan administrasi pada Dukcapil.",
            'Akta Perkawinan / Perceraian' => "Nama tersebut di atas mengajukan pengurusan Akta Perkawinan/Perceraian.\n\nSurat pengantar ini dipergunakan sebagai kelengkapan administrasi pada Dukcapil.",
            'Surat Keterangan Tanah (SKT)' => "Nama tersebut di atas mengajukan permohonan penerbitan Surat Keterangan Tanah (SKT) atas bidang tanah yang dikuasai.\n\nSurat ini diberikan untuk keperluan administrasi pertanahan.",
            'Surat Riwayat Tanah' => "Nama tersebut di atas mengajukan permohonan surat riwayat tanah.\n\nSurat ini dipergunakan untuk melengkapi administrasi pertanahan sesuai ketentuan yang berlaku.",
            'Surat Pengantar Sertifikat Tanah (ke BPN)' => "Nama tersebut di atas benar warga desa ini dan mengajukan pengurusan sertifikat tanah.\n\nSurat ini diberikan sebagai pengantar pengajuan ke Kantor Pertanahan/BPN.",
            'Surat Pernyataan Penguasaan Fisik Tanah' => "Nama tersebut di atas membuat pernyataan penguasaan fisik tanah di wilayah desa ini.\n\nSurat ini dipergunakan sebagai dokumen administrasi pendukung urusan pertanahan.",
            'Rekomendasi Izin Usaha Mikro' => "Nama tersebut di atas benar menjalankan usaha mikro di wilayah desa ini.\n\nPemerintah desa memberikan rekomendasi administrasi untuk proses izin usaha mikro pada instansi berwenang.",
            'Rekomendasi Izin Keramaian' => "Nama tersebut di atas mengajukan rekomendasi izin keramaian untuk kegiatan yang diselenggarakan.\n\nSurat ini dipergunakan untuk kelengkapan perizinan pada instansi berwenang.",
            'Rekomendasi Proposal Bantuan' => "Nama tersebut di atas mengajukan rekomendasi proposal bantuan.\n\nPemerintah desa menerangkan bahwa pengajuan tersebut benar adanya untuk diproses sesuai ketentuan.",
            'Pengantar BPJS / JKN' => "Nama tersebut di atas mengajukan pengurusan kepesertaan/pembaruan data BPJS/JKN.\n\nSurat pengantar ini dipergunakan sebagai kelengkapan administrasi pada instansi terkait.",
            'Rekomendasi bantuan sosial (PKH, BLT, dll.)' => "Berdasarkan data sosial yang tersedia, nama tersebut di atas diajukan dalam proses rekomendasi bantuan sosial (PKH/BLT/dan sejenisnya).\n\nSurat ini dipergunakan untuk kelengkapan verifikasi administrasi bantuan sosial.",
            'Surat pengantar sekolah / beasiswa' => "Nama tersebut di atas mengajukan surat pengantar untuk keperluan administrasi sekolah/beasiswa.\n\nSurat ini dipergunakan sebagai kelengkapan berkas pada instansi pendidikan/lembaga terkait.",
        ];

        return $map[$documentType] ?? "Menerangkan bahwa {$name} (NIK {$nik}) merupakan warga desa ini.";
    }

    private function getLetterPayload(int $id): ?array
    {
        $request = $this->findAuthorizedRequest($id);
        if (! $request) {
            return null;
        }

        $manualCitizen = $this->extractManualCitizen($request);
        if ($manualCitizen !== null) {
            $citizen = $manualCitizen;
        } else {
            $userModel = new UserModel();
            $user = $userModel->find((int) $request['user_id']);
            if (! $user) {
                return null;
            }
            $citizen = $user;
        }

        $settingModel = new LetterSettingModel();
        $setting      = $settingModel->first();
        if (! $setting) {
            $setting = [
                'regency_name'      => 'Nama Kabupaten',
                'subdistrict_name'  => 'Nama Kecamatan',
                'village_name'       => 'Nama Desa',
                'office_address'     => 'Jl. [Nama Jalan/Alamat Lengkap Kantor Desa]',
                'app_icon'           => 'home',
                'signer_title'       => 'Kepala Desa',
                'signer_name'        => 'Nama Kepala Desa',
                'signer_signature'   => null,
                'letterhead_address' => 'Jl. [Nama Jalan/Alamat Lengkap Kantor Desa]',
            ];
        } elseif (! isset($setting['office_address']) || trim((string) $setting['office_address']) === '') {
            $setting['office_address'] = (string) ($setting['letterhead_address'] ?? '');
            $setting['app_icon']       = (string) ($setting['app_icon'] ?? 'home');
            $setting['signer_title']   = (string) ($setting['signer_title'] ?? 'Kepala Desa');
            $setting['signer_name']    = (string) ($setting['signer_name'] ?? 'Nama Kepala Desa');
            $setting['signer_signature'] = (string) ($setting['signer_signature'] ?? '');
        } else {
            $setting['signer_title'] = (string) ($setting['signer_title'] ?? 'Kepala Desa');
            $setting['signer_name']  = (string) ($setting['signer_name'] ?? 'Nama Kepala Desa');
            $setting['signer_signature'] = (string) ($setting['signer_signature'] ?? '');
        }

        return [
            'request'    => $request,
            'citizen'    => $citizen,
            'setting'    => $setting,
            'letterBody' => $this->letterBody($request['document_type'], $citizen),
            'letterNumber' => $this->generateLetterNumber($request),
        ];
    }

    private function extractManualCitizen(array $request): ?array
    {
        $description = (string) ($request['description'] ?? '');
        if ($description === '') {
            return null;
        }

        $parsed = json_decode($description, true);
        if (! is_array($parsed)) {
            return null;
        }

        if (($parsed['manual_input'] ?? false) !== true || ! isset($parsed['citizen']) || ! is_array($parsed['citizen'])) {
            return null;
        }

        return $parsed['citizen'];
    }

    private function generateLetterNumber(array $request): string
    {
        $documentType = (string) ($request['document_type'] ?? '');
        $codeMap = [
            'Surat Keterangan Domisili' => 'SKD',
            'Surat Keterangan Usaha (SKU)' => 'SKU',
            'Surat Keterangan Tidak Mampu (SKTM)' => 'SKTM',
            'Surat Keterangan Belum Menikah' => 'SKBM',
            'Surat Keterangan Kematian' => 'SKK',
            'Surat Keterangan Kelahiran' => 'SKL',
            'Surat Pengantar SKCK' => 'PSKCK',
            'Surat Pengantar Pembuatan KTP / KK' => 'PKTPKK',
            'Pembuatan atau perubahan Kartu Keluarga (KK)' => 'DCKK',
            'Perekaman atau pencetakan e-KTP' => 'DCEKTP',
            'Akta Kelahiran' => 'DCAKL',
            'Akta Kematian' => 'DCAKM',
            'Akta Perkawinan / Perceraian' => 'DCAKP',
            'Surat Keterangan Tanah (SKT)' => 'SKT',
            'Surat Riwayat Tanah' => 'SRT',
            'Surat Pengantar Sertifikat Tanah (ke BPN)' => 'PSBPN',
            'Surat Pernyataan Penguasaan Fisik Tanah' => 'SPPFT',
            'Rekomendasi Izin Usaha Mikro' => 'RIUM',
            'Rekomendasi Izin Keramaian' => 'RIK',
            'Rekomendasi Proposal Bantuan' => 'RPB',
            'Pengantar BPJS / JKN' => 'PBPJS',
            'Rekomendasi bantuan sosial (PKH, BLT, dll.)' => 'RBANSOS',
            'Surat pengantar sekolah / beasiswa' => 'PSB',
        ];

        $code = $codeMap[$documentType] ?? 'SURAT';

        $year = date('Y');
        $month = (int) date('n');
        if (! empty($request['created_at'])) {
            $ts = strtotime((string) $request['created_at']);
            if ($ts !== false) {
                $year = date('Y', $ts);
                $month = (int) date('n', $ts);
            }
        }

        // Reset nomor urut setiap bulan (per jenis surat).
        $sequence = (new DocumentRequestModel())
            ->where('document_type', $documentType)
            ->where('id <=', (int) ($request['id'] ?? 0))
            ->where('YEAR(created_at) = ' . (int) $year, null, false)
            ->where('MONTH(created_at) = ' . $month, null, false)
            ->countAllResults();

        if ($sequence < 1) {
            $sequence = 1;
        }

        $number = str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
        $monthRoman = $this->monthToRoman($month);

        return $code . '-' . $number . '/' . $monthRoman . '/' . $year;
    }

    private function monthToRoman(int $month): string
    {
        $map = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        ];

        return $map[$month] ?? (string) $month;
    }

    private function groupedDocTypes(): array
    {
        $groups = [];
        foreach (self::DOC_GROUPS as $groupName => $keys) {
            $items = [];
            foreach ($keys as $key) {
                if (isset(self::DOC_TYPES[$key])) {
                    $items[$key] = self::DOC_TYPES[$key];
                }
            }

            if ($items !== []) {
                $groups[$groupName] = $items;
            }
        }

        return $groups;
    }
}
