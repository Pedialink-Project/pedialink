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
        $childAppointments = $this->dashboardService->getLatestChildAppointmentsByParentId();
        $maternalAppointments = $this->dashboardService->getLatestMaternalAppointmentsByParentId();
        $childCount = $this->dashboardService->getChildrenCount();
        return view("parent/dashboard", ['events' => $events, 'appointmentCount' => $appointmentCount,'childAppointments' => $childAppointments, 'maternalAppointments' => $maternalAppointments, 'childCount' => $childCount ]);
    }
}