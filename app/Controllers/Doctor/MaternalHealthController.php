<?php

namespace App\Controllers\Doctor;

use Library\Framework\Http\Request;

class MaternalHealthController
{
    public function index(Request $request)
    {
        return view("doctor/maternalhealth");
    }
}