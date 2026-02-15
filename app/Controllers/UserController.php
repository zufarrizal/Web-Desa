<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController
{
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
        $rules = [
            'name'     => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[admin,user]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $userModel->insert([
            'name'     => (string) $this->request->getPost('name'),
            'email'    => (string) $this->request->getPost('email'),
            'password' => password_hash((string) $this->request->getPost('password'), PASSWORD_BCRYPT),
            'role'     => (string) $this->request->getPost('role'),
        ]);

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

        $rules = [
            'name'  => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[users.email,id,' . $id . ']',
            'role'  => 'required|in_list[admin,user]',
        ];

        $password = (string) $this->request->getPost('password');
        if ($password !== '') {
            $rules['password'] = 'min_length[6]';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $payload = [
            'name'  => (string) $this->request->getPost('name'),
            'email' => (string) $this->request->getPost('email'),
            'role'  => (string) $this->request->getPost('role'),
        ];

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
