<?php

namespace App\Controllers\PublicHealthMidwife;

use Library\Framework\Http\Request;

class AppointmentRequestController
{
    public function index(Request $request)
    {
        return view("phm/appointments-requests");
    }
}