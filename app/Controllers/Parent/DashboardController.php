<?php

namespace App\Controllers\Parent;
use App\Services\Parent\DashboardService;
class DashboardController
{

 private $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }
    public function index()
    {

        $events = $this->dashboardService->getEventsData();
        $appointmentCount = $this->dashboardService->getAppointmentCount();
        $appointments = $this->dashboardService->getLatestChildAppointmentsByParentId();
        return view("parent/dashboard", ['events' => $events, 'appointmentCount' => $appointmentCount,'appointments' => $appointments]);
    }
}