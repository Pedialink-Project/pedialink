<?php

namespace App\Controllers\Doctor;

use App\Services\Doctor\CalendarService;
use Library\Framework\Http\Request;

class CalendarController
{
    private CalendarService $calendarService;

    public function __construct()
    {
        $this->calendarService = new CalendarService();
    }

}