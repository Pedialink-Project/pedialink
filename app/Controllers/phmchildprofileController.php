<?php

namespace App\Controllers;

use Library\Framework\Http\Request;

class phmchildprofileController
{
    public function index(Request $request)
    {
        return view("phm/childprofiles");
    }
}