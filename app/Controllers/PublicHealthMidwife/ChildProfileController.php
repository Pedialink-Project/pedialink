<?php

namespace App\Controllers\PublicHealthMidwife;

use Library\Framework\Http\Request;

class ChildProfileController
{
    public function index(Request $request)
    {
        return view("phm/childprofiles");
    }
}