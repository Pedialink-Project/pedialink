<?php

namespace App\Controllers\Doctor;

use Library\Framework\Http\Request;

class SettingsController
{
    public function index(Request $request)
    {
        return view("doctor/setting");
    }
}