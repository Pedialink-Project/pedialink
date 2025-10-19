<?php

namespace App\Controllers\Doctor;

use Library\Framework\Http\Request;

class AppointmentController
{
    public function index(Request $request)
    {
        return view("doctor/appointment");
    }
}