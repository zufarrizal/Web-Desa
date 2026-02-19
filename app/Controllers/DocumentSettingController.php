<?php

namespace App\Controllers;

use App\Models\LetterSettingModel;

class DocumentSettingController extends BaseController
{
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
            'regency_name' => (string) $this->request->getPost('regency_name'),
            'subdistrict_name' => (string) $this->request->getPost('subdistrict_name'),
            'village_name' => (string) $this->request->getPost('village_name'),
            'office_address' => (string) $this->request->getPost('office_address'),
            'app_icon' => (string) ($this->request->getPost('app_icon') ?: 'home'),
            'signer_title' => (string) $this->request->getPost('signer_title'),
            'signer_name' => (string) $this->request->getPost('signer_name'),
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

            $imageMeta = @getimagesize($signatureFile->getTempName());
            if (! is_array($imageMeta) || count($imageMeta) < 2) {
                return redirect()->back()->withInput()->with('errors', ['File tanda tangan tidak valid.']);
            }

            $width = (int) $imageMeta[0];
            $height = (int) $imageMeta[1];
            if ($width < 800 || $height < 200) {
                return redirect()->back()->withInput()->with('errors', [
                    'Resolusi tanda tangan terlalu rendah. Minimal 800x200 piksel agar hasil print tidak pecah.',
                ]);
            }

            $uploadDir = FCPATH . 'uploads/signatures';
            if (! is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $newName = 'sign-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $signatureFile->getExtension();
            $signatureFile->move($uploadDir, $newName, true);
            $payload['signer_signature'] = 'uploads/signatures/' . $newName;

            if ($setting && ! empty($setting['signer_signature'])) {
                $this->removeSignatureFile((string) $setting['signer_signature']);
            }
        }

        if ($this->request->getPost('remove_signer_signature') === '1') {
            $payload['signer_signature'] = null;
            if ($setting && ! empty($setting['signer_signature'])) {
                $this->removeSignatureFile((string) $setting['signer_signature']);
            }
        }

        if ($setting) {
            $settingModel->update((int) $setting['id'], $payload);
        } else {
            $settingModel->insert($payload);
        }

        return redirect()->to('/documents/settings')->with('success', 'Pengaturan kop surat berhasil diperbarui.');
    }

    private function removeSignatureFile(string $path): void
    {
        $fullPath = FCPATH . ltrim($path, '/\\');
        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }
}
