<?php

namespace App\Controllers\PublicHealthMidwife;

use Library\Framework\Http\Request;

class MaternalHealthController
{
    public function index(Request $request)
    {
        return view("phm/maternalhealth");
    }
}