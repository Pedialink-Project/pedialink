<?php

namespace App\Controllers\Parent;

use App\Services\Parent\CalendarService;
use Library\Framework\Http\Request;

class CalendarController
{
    private CalendarService $calendarService;

    public function __construct()
    {
        $this->calendarService = new CalendarService();
    }

    public function index(Request $request)
    {
        $parentId = auth()->user()->id;
        $events = $this->calendarService->getParentCalendarEvents($parentId);

        return view("parent/calendar", [
            "events" => $events
        ]);
    }
}
