<?php

namespace App\Controllers\Doctor;

use Library\Framework\Http\Request;

class ChildProfileController
{
    public function index(Request $request)
    {
        return view("doctor/childprofile");
    }
}