<?php

namespace App\Controllers\PublicHealthMidwife;

use Library\Framework\Http\Request;

class VaccinationController
{
    public function index(Request $request)
    {
        return view("phm/vaccination");
    }
}