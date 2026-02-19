<?php

namespace App\Controllers;

use App\Models\AuditLogModel;

class AuditLogController extends BaseController
{
    public function index()
    {
        if ((string) session()->get('user_role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki hak akses ke halaman audit log.');
        }

        $logs = (new AuditLogModel())
            ->orderBy('id', 'DESC')
            ->findAll(500);

        return view('audit_logs/index', [
            'logs' => $logs,
        ]);
    }
}

