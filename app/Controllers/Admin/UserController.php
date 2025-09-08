<?php

namespace App\Controllers\Admin;

class UserController
{
    public function overview()
    {
        return view('admin/user/overview');
    }

    public function parentAccountApproval()
    {
        return view('admin/user/parent');
    }

    public function admin()
    {
        return view('admin/user/admin');
    }
}