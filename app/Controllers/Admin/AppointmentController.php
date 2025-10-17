<?php

namespace App\Controllers\Admin;

use Library\Framework\Http\Request;

class AppointmentController
{
    public function index(Request $request)
    {
        return view("admin/appointment");
    }

}