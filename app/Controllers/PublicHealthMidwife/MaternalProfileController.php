<?php

namespace App\Controllers\PublicHealthMidwife;

use Library\Framework\Http\Request;

class MaternalProfileController
{
    public function index(Request $request)
    {
        return view("phm/maternalprofiles");
    }
}