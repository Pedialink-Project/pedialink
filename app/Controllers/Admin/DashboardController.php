<?php

namespace App\Controllers\Admin;

use App\Services\Admin\DashboardService;

class DashboardController
{
    private DashboardService $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }
    
    public function index()
    {
        $childrenCount = $this->dashboardService->getTotalChildrenCount();
        $phmCount = $this->dashboardService->getActivePhmCount();
        $parentsCount = $this->dashboardService->getTotalParentsCount();
        $accessRequestsCount = $this->dashboardService->getTotalAccessRequestsCount();
        $linkageRequestsCount = $this->dashboardService->getTotalLinkageRequestsCount();
        $doctorsCount = $this->dashboardService->getActiveDoctorsCount();

        $vaccinationChartData = $this->dashboardService->getVaccinationChartData();

        return view("admin/dashboard", [
            "childrenCount" => $childrenCount,
            "phmCount" => $phmCount,
            "parentsCount" => $parentsCount,
            "accessRequestsCount" => $accessRequestsCount,
            "linkageRequestsCount" => $linkageRequestsCount,
            "doctorsCount" => $doctorsCount,
            "vaccinationChartData" => $vaccinationChartData,
        ]);
    }
}