<?php

namespace App\Controllers\Parent;

use Library\Framework\Http\Request;

class CalendarController
{

    public function index(Request $request)
    {
        return view("parent/calendar");
    }
}
