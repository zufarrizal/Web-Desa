<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController
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

    private function baseRules(int $id = 0): array
    {
        $emailRule = $id > 0
            ? 'required|valid_email|is_unique[users.email,id,' . $id . ']'
            : 'required|valid_email|is_unique[users.email]';

        return [
            'name'           => 'required|min_length[3]',
            'email'          => $emailRule,
            'role'           => 'required|in_list[admin,user]',
            'no_kk'          => 'required|min_length[8]',
            'nik'            => 'required|min_length[8]',
            'birth_place'    => 'required',
            'birth_date'     => 'required|regex_match[/^\d{4}-\d{2}-\d{2}$/]',
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
    }

    private function collectProfilePayload(): array
    {
        return [
            'name'           => $this->toCapitalizedCase((string) $this->request->getPost('name')),
            'email'          => (string) $this->request->getPost('email'),
            'role'           => (string) $this->request->getPost('role'),
            'no_kk'          => (string) $this->request->getPost('no_kk'),
            'nik'            => (string) $this->request->getPost('nik'),
            'birth_place'    => $this->toCapitalizedCase((string) $this->request->getPost('birth_place')),
            'birth_date'     => (string) $this->request->getPost('birth_date'),
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
        ];
    }

    public function index()
    {
        $userModel = new UserModel();

        return view('users/index', [
            'users' => $userModel->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function create()
    {
        return view('users/form', [
            'mode' => 'create',
            'user' => null,
        ]);
    }

    public function store()
    {
        $rules = $this->baseRules();
        $rules['password'] = 'required|min_length[6]';

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $payload = $this->collectProfilePayload();
        $payload['password'] = password_hash((string) $this->request->getPost('password'), PASSWORD_BCRYPT);
        $userModel->insert($payload);

        return redirect()->to('/users')->with('success', 'User berhasil dibuat.');
    }

    public function edit(int $id)
    {
        $userModel = new UserModel();
        $user      = $userModel->find($id);

        if (! $user) {
            return redirect()->to('/users')->with('error', 'User tidak ditemukan.');
        }

        return view('users/form', [
            'mode' => 'edit',
            'user' => $user,
        ]);
    }

    public function update(int $id)
    {
        $userModel = new UserModel();
        $user      = $userModel->find($id);

        if (! $user) {
            return redirect()->to('/users')->with('error', 'User tidak ditemukan.');
        }

        $rules = $this->baseRules($id);

        $password = (string) $this->request->getPost('password');
        if ($password !== '') {
            $rules['password'] = 'min_length[6]';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $payload = $this->collectProfilePayload();

        if ($password !== '') {
            $payload['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $userModel->update($id, $payload);

        return redirect()->to('/users')->with('success', 'User berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        if ((int) session()->get('user_id') === $id) {
            return redirect()->to('/users')->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        $userModel = new UserModel();
        $user      = $userModel->find($id);

        if (! $user) {
            return redirect()->to('/users')->with('error', 'User tidak ditemukan.');
        }

        $userModel->delete($id);

        return redirect()->to('/users')->with('success', 'User berhasil dihapus.');
    }
}
