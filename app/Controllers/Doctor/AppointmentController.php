<?php

namespace App\Controllers\Doctor;

use Library\Framework\Http\Request;

class AppointmentController
{
    public function overview(Request $request)
    {
        return view("doctor/appointment/overview");
    }

    public function configure(Request $request)
    {
        return view("doctor/appointment/configure");
    }
}