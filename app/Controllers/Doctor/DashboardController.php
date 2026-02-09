<?php

namespace App\Controllers\Doctor;

use Library\Framework\Http\Request;
use App\Services\Doctor\DashboardService;

class DashboardController
{
    private DashboardService $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }

    public function index(Request $request)
    {
        // Get current doctor ID (adjust according to your auth system)
        $doctorId = auth()->user()?->id; // Example: assuming $request->user() returns the logged-in doctor

        // Get chart data for children and maternal records
        $childrenChartData = $this->dashboardService->getChildHealthStatusCounts($doctorId);
        $maternalChartData  = $this->dashboardService->getMaternalHealthStatusCounts();

        // Pass data to the dashboard view
        return view("doctor/dashboard", [
            'childrenChartData' => $childrenChartData,
            'maternalChartData' => $maternalChartData,
        ]);
    }
}