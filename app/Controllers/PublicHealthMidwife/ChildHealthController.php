<?php

namespace App\Controllers\PublicHealthMidwife;

use Library\Framework\Http\Request;

class ChildHealthController
{
    public function index(Request $request)
    {
        return view("phm/childhealth");
    }
}