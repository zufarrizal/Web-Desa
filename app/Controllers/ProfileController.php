<?php

namespace App\Controllers;

use App\Models\UserModel;

class ProfileController extends BaseController
{
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
            'nik'            => 'required|min_length[8]',
            'birth_place'    => 'required',
            'birth_date'     => 'required|valid_date[Y-m-d]',
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

        $userModel->update($userId, [
            'name'           => (string) $this->request->getPost('name'),
            'email'          => (string) $this->request->getPost('email'),
            'nik'            => (string) $this->request->getPost('nik'),
            'birth_place'    => (string) $this->request->getPost('birth_place'),
            'birth_date'     => (string) $this->request->getPost('birth_date'),
            'gender'         => (string) $this->request->getPost('gender'),
            'religion'       => (string) $this->request->getPost('religion'),
            'occupation'     => (string) $this->request->getPost('occupation'),
            'marital_status' => (string) $this->request->getPost('marital_status'),
            'address'        => (string) $this->request->getPost('address'),
            'rt'             => (string) $this->request->getPost('rt'),
            'rw'             => (string) $this->request->getPost('rw'),
            'village'        => (string) $this->request->getPost('village'),
            'district'       => (string) $this->request->getPost('district'),
            'city'           => (string) $this->request->getPost('city'),
            'province'       => (string) $this->request->getPost('province'),
            'citizenship'    => (string) ($this->request->getPost('citizenship') ?: 'WNI'),
        ]);

        return redirect()->to('/profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
