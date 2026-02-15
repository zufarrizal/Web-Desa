<?php

namespace App\Controllers;

use App\Models\ComplaintModel;
use App\Models\DocumentRequestModel;
use App\Models\UserModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $role      = (string) session()->get('user_role');
        $userId    = (int) session()->get('user_id');
        $userModel = new UserModel();
        $documentModel = new DocumentRequestModel();
        $complaintModel = new ComplaintModel();

        $documentCount = $role === 'admin'
            ? $documentModel->countAllResults()
            : $documentModel->where('user_id', $userId)->countAllResults();

        $complaintCount = $role === 'admin'
            ? $complaintModel->countAllResults()
            : $complaintModel->where('user_id', $userId)->countAllResults();

        $userCount = $userModel->countAllResults();

        return view('dashboard/index', [
            'name'           => session()->get('user_name'),
            'email'          => session()->get('user_email'),
            'role'           => $role,
            'documentCount'  => $documentCount,
            'complaintCount' => $complaintCount,
            'userCount'      => $userCount,
        ]);
    }
}
