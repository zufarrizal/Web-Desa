<?php

namespace App\Controllers;

class AdminNotificationController extends BaseController
{
    public function openUsers()
    {
        if ((string) session()->get('user_role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }

        session()->set('admin_notifications_users_seen_at', date('Y-m-d H:i:s'));

        return redirect()->to('/users');
    }

    public function clear()
    {
        if ((string) session()->get('user_role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }

        session()->set('admin_notifications_seen_at', date('Y-m-d H:i:s'));

        return redirect()->back()->with('success', 'Notifikasi admin berhasil dihapus.');
    }
}
