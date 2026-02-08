<?php

namespace App\Controllers\Parent;
use App\Services\EventService;

class DashboardController
{

 private $eventService;

    public function __construct()
    {
        $this->eventService = new EventService();
    }
    public function index()
    {

        $events = $this->eventService->getDashboardEvents(3);
        return view("parent/dashboard", ['events' => $events]);
    }
}