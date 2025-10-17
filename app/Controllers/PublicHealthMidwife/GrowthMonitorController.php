<?php

namespace App\Controllers\PublicHealthMidwife;

use Library\Framework\Http\Request;

class GrowthMonitorController
{
    public function index(Request $request)
    {
        return view("phm/growthmonitoring");
    }
}