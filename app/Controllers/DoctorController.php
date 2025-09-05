<?php

namespace App\Controllers;

use Library\Framework\Http\Request;

class DoctorController
{
    public function dashboard(Request $request)
    {
        return view("doctor/dashboard");
    }
}