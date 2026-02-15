<?php

namespace App\Controllers;

use App\Models\ComplaintModel;

class ComplaintController extends BaseController
{
    public function index()
    {
        $role   = (string) session()->get('user_role');
        $userId = (int) session()->get('user_id');
        $model  = new ComplaintModel();

        $builder = $model->select('complaints.*, users.name as user_name')
            ->join('users', 'users.id = complaints.user_id', 'left')
            ->orderBy('complaints.id', 'DESC');

        if ($role !== 'admin') {
            $builder->where('complaints.user_id', $userId);
        }

        return view('complaints/index', [
            'complaints' => $builder->findAll(),
            'role'       => $role,
        ]);
    }

    public function create()
    {
        return view('complaints/form', [
            'mode'      => 'create',
            'complaint' => null,
            'role'      => (string) session()->get('user_role'),
        ]);
    }

    public function store()
    {
        $rules = [
            'title'    => 'required|min_length[5]',
            'content'  => 'required|min_length[10]',
            'location' => 'permit_empty|max_length[191]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new ComplaintModel();
        $model->insert([
            'user_id'   => (int) session()->get('user_id'),
            'title'     => (string) $this->request->getPost('title'),
            'content'   => (string) $this->request->getPost('content'),
            'location'  => (string) $this->request->getPost('location'),
            'status'    => 'baru',
            'response'  => null,
        ]);

        return redirect()->to('/complaints')->with('success', 'Pengaduan berhasil dikirim.');
    }

    public function edit(int $id)
    {
        $complaint = $this->findAuthorizedComplaint($id);
        if (! $complaint) {
            return redirect()->to('/complaints')->with('error', 'Data tidak ditemukan atau tidak diizinkan.');
        }

        return view('complaints/form', [
            'mode'      => 'edit',
            'complaint' => $complaint,
            'role'      => (string) session()->get('user_role'),
        ]);
    }

    public function update(int $id)
    {
        $complaint = $this->findAuthorizedComplaint($id);
        if (! $complaint) {
            return redirect()->to('/complaints')->with('error', 'Data tidak ditemukan atau tidak diizinkan.');
        }

        $role = (string) session()->get('user_role');
        $rules = [
            'title'    => 'required|min_length[5]',
            'content'  => 'required|min_length[10]',
            'location' => 'permit_empty|max_length[191]',
        ];

        if ($role === 'admin') {
            $rules['status'] = 'required|in_list[baru,ditindaklanjuti,selesai,ditolak]';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $payload = [
            'title'    => (string) $this->request->getPost('title'),
            'content'  => (string) $this->request->getPost('content'),
            'location' => (string) $this->request->getPost('location'),
        ];

        if ($role === 'admin') {
            $payload['status']   = (string) $this->request->getPost('status');
            $payload['response'] = (string) $this->request->getPost('response');
        }

        $model = new ComplaintModel();
        $model->update($id, $payload);

        return redirect()->to('/complaints')->with('success', 'Pengaduan berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $complaint = $this->findAuthorizedComplaint($id);
        if (! $complaint) {
            return redirect()->to('/complaints')->with('error', 'Data tidak ditemukan atau tidak diizinkan.');
        }

        $model = new ComplaintModel();
        $model->delete($id);

        return redirect()->to('/complaints')->with('success', 'Pengaduan berhasil dihapus.');
    }

    private function findAuthorizedComplaint(int $id): ?array
    {
        $model     = new ComplaintModel();
        $complaint = $model->find($id);

        if (! $complaint) {
            return null;
        }

        if ((string) session()->get('user_role') !== 'admin' && (int) $complaint['user_id'] !== (int) session()->get('user_id')) {
            return null;
        }

        return $complaint;
    }
}
