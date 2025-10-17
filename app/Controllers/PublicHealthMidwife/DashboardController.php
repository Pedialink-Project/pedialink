<?php

namespace App\Controllers\PublicHealthMidwife;

use Library\Framework\Http\Request;

class DashboardController
{
    public function index(Request $request)
    {
        return view("phm/dashboard");
    }
}