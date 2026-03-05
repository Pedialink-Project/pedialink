<?php

namespace App\Controllers\PublicHealthMidwife;

use App\Services\PublicHealthMidwife\DashboardService;
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
        $linkedChildrenCount = $this->dashboardService->getLinkedChildrenCount();
        $appointmentsCount = $this->dashboardService->getAppointmentsCount();
        $upcomingVaccinationsCount = $this->dashboardService->getUpcomingVaccinationsCount();
        $appointments = $this->dashboardService->upcomingAppointments();
        $vaccinations = $this->dashboardService->upcomingVaccinations();
        $maternalRiskData = $this->dashboardService->maternalRiskData();
        return view("phm/dashboard", [
            "linkedChildrenCount" => $linkedChildrenCount,
            "appointmentsCount" => $appointmentsCount,
            "upcomingVaccinationsCount" => $upcomingVaccinationsCount,
            "appointments" => $appointments,
            "vaccinations" => $vaccinations,
            "maternalRiskData" => $maternalRiskData
        ]);
    }
}