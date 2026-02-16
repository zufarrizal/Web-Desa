<?php

namespace App\Controllers;

use App\Models\LetterSettingModel;

class HomeSettingController extends BaseController
{
    public function edit()
    {
        if ((string) session()->get('user_role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Hanya admin yang bisa mengatur halaman utama.');
        }

        $setting = (new LetterSettingModel())->first();

        return view('settings/home', [
            'setting' => $setting,
        ]);
    }

    public function update()
    {
        if ((string) session()->get('user_role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Hanya admin yang bisa mengatur halaman utama.');
        }

        $rules = [
            'village_profile_title' => 'permit_empty|max_length[150]',
            'contact_person' => 'permit_empty|max_length[120]',
            'contact_phone' => 'permit_empty|max_length[40]',
            'contact_email' => 'permit_empty|valid_email|max_length[120]',
            'contact_whatsapp' => 'permit_empty|max_length[40]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new LetterSettingModel();
        $row   = $model->first();

        $payload = [
            'village_profile_title'   => (string) $this->request->getPost('village_profile_title'),
            'village_profile_content' => (string) $this->request->getPost('village_profile_content'),
            'contact_person'          => (string) $this->request->getPost('contact_person'),
            'contact_phone'           => (string) $this->request->getPost('contact_phone'),
            'contact_email'           => (string) $this->request->getPost('contact_email'),
            'contact_whatsapp'        => (string) $this->request->getPost('contact_whatsapp'),
            'complaint_info'          => (string) $this->request->getPost('complaint_info'),
        ];

        if ($row) {
            $model->update((int) $row['id'], $payload);
        } else {
            // Buat record awal agar setting homepage tetap tersimpan walau kop surat belum diisi.
            $payload = array_merge([
                'regency_name' => 'Nama Kabupaten',
                'subdistrict_name' => 'Nama Kecamatan',
                'village_name' => 'Nama Desa',
                'office_address' => '[Nama Jalan/Alamat Lengkap Kantor Desa]',
                'letterhead_address' => '[Nama Jalan/Alamat Lengkap Kantor Desa]',
                'app_icon' => 'home',
                'signer_title' => 'Kepala Desa',
                'signer_name' => 'Nama Kepala Desa',
            ], $payload);
            $model->insert($payload);
        }

        return redirect()->to('/settings/home')->with('success', 'Pengaturan halaman utama berhasil diperbarui.');
    }
}
