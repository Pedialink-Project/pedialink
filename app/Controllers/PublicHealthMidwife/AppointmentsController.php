<?php

namespace App\Controllers\PublicHealthMidwife;

use Library\Framework\Http\Request;

class AppointmentsController
{
    public function index(Request $request)
    {
        return view("phm/appointments");
    }
}