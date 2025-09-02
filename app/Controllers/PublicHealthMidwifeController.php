<?php

namespace App\Controllers;

use Library\Framework\Http\Request;

class PublicHealthMidwifeController
{
    public function dashboard(Request $request)
    {
        return view("phm/dashboard");
    }
}