<?php

namespace App\Controllers\PublicHealthMidwife;

use App\Services\PublicHealthMidwife\CalendarService;
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
        $phmId = auth()->user()->id;
        $events = $this->calendarService->getPHMCalendarEvents($phmId);

        return view('phm/calendar', [
            'events' => $events,
        ]);
    }
}
