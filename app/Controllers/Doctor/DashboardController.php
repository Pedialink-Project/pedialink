<?php

namespace App\Controllers\Doctor;

use App\Services\Doctor\DashboardService;
use Library\Framework\Http\Request;

class DashboardController
{
    private DashboardService $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }

    public function index(Request $request)
    {
        $patientsCount = $this->dashboardService->getPatientsCount();
        $appointmentsCount = $this->dashboardService->getAppointmentsCount();
        $urgentCasesCount = $this->dashboardService->getUrgentCasesCount();

        $upcomingAppointments = $this->dashboardService->upcomingAppointments();
        return view("doctor/dashboard", [
            "patientsCount" => $patientsCount,
            "appointmentsCount" => $appointmentsCount,
            "urgentCasesCount" => $urgentCasesCount,
            "upcomingAppointments" => $upcomingAppointments
        ]);
    }
}