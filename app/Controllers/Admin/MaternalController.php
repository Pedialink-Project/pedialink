<?php

namespace App\Controllers\Admin;

class MaternalController
{
    public function overview()
    {
        return view("admin/maternal/overview");
    }

    public function accessRequests()
    {
        return view("admin/maternal/access");
    }
}