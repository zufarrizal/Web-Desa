<?php

namespace App\Controllers;

use App\Models\UserModel;

class ProfileController extends BaseController
{
    private function toCapitalizedCase(string $value): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }

        if (function_exists('mb_convert_case')) {
            return mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
        }

        return ucwords(strtolower($value));
    }

    public function edit()
    {
        $userModel = new UserModel();
        $user      = $userModel->find((int) session()->get('user_id'));

        if (! $user) {
            return redirect()->to('/dashboard')->with('error', 'Profil tidak ditemukan.');
        }

        return view('profile/edit', ['user' => $user]);
    }

    public function update()
    {
        $userId    = (int) session()->get('user_id');
        $userModel = new UserModel();
        $user      = $userModel->find($userId);

        if (! $user) {
            return redirect()->to('/dashboard')->with('error', 'Profil tidak ditemukan.');
        }

        $rules = [
            'name'           => 'required|min_length[3]',
            'email'          => 'required|valid_email|is_unique[users.email,id,' . $userId . ']',
            'no_kk'          => 'permit_empty|min_length[8]',
            'nik'            => 'required|min_length[8]',
            'birth_place'    => 'required',
            'birth_date'     => 'required|regex_match[/^\d{2}\/\d{2}\/\d{4}$/]',
            'gender'         => 'required|in_list[Laki-laki,Perempuan]',
            'religion'       => 'permit_empty',
            'occupation'     => 'required',
            'marital_status' => 'permit_empty',
            'address'        => 'required',
            'rt'             => 'permit_empty|max_length[5]',
            'rw'             => 'permit_empty|max_length[5]',
            'village'        => 'required',
            'district'       => 'required',
            'city'           => 'required',
            'province'       => 'permit_empty',
            'citizenship'    => 'permit_empty',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $birthDateInput = (string) $this->request->getPost('birth_date');
        $birthDateDb = $this->toDatabaseDate($birthDateInput);
        if ($birthDateDb === null) {
            return redirect()->back()->withInput()->with('errors', [
                'birth_date' => 'Format tanggal lahir harus dd/mm/yyyy.',
            ]);
        }

        $userModel->update($userId, [
            'name'           => $this->toCapitalizedCase((string) $this->request->getPost('name')),
            'email'          => (string) $this->request->getPost('email'),
            'no_kk'          => (string) $this->request->getPost('no_kk'),
            'nik'            => (string) $this->request->getPost('nik'),
            'birth_place'    => $this->toCapitalizedCase((string) $this->request->getPost('birth_place')),
            'birth_date'     => $birthDateDb,
            'gender'         => (string) $this->request->getPost('gender'),
            'religion'       => $this->toCapitalizedCase((string) $this->request->getPost('religion')),
            'occupation'     => $this->toCapitalizedCase((string) $this->request->getPost('occupation')),
            'marital_status' => $this->toCapitalizedCase((string) $this->request->getPost('marital_status')),
            'address'        => $this->toCapitalizedCase((string) $this->request->getPost('address')),
            'rt'             => (string) $this->request->getPost('rt'),
            'rw'             => (string) $this->request->getPost('rw'),
            'village'        => $this->toCapitalizedCase((string) $this->request->getPost('village')),
            'district'       => $this->toCapitalizedCase((string) $this->request->getPost('district')),
            'city'           => $this->toCapitalizedCase((string) $this->request->getPost('city')),
            'province'       => $this->toCapitalizedCase((string) $this->request->getPost('province')),
            'citizenship'    => (string) ($this->request->getPost('citizenship') ?: 'WNI'),
        ]);

        return redirect()->to('/profile')->with('success', 'Profil berhasil diperbarui.');
    }

    private function toDatabaseDate(string $dateInput): ?string
    {
        $dateInput = trim($dateInput);
        if ($dateInput === '') {
            return null;
        }

        $dt = \DateTime::createFromFormat('d/m/Y', $dateInput);
        if (! $dt || $dt->format('d/m/Y') !== $dateInput) {
            return null;
        }

        return $dt->format('Y-m-d');
    }
}
