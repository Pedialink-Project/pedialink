<?php

namespace App\Controllers\Doctor;

use Library\Framework\Http\Request;

class DashboardController
{
    public function index(Request $request)
    {
        return view("doctor/dashboard");
    }
}