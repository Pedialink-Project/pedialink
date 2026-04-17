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
        $weeklyAppointmentData = $this->dashboardService->getWeeklyAppointmentData();
        $latestHealthRecords = $this->dashboardService->getLatestHealthRecords();
        $patientRiskData = $this->dashboardService->getPatientRiskOverviewData();
}
