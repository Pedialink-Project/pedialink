<?php

namespace App\Controllers\PublicHealthMidwife;

use App\Models\ParentM;
use Library\Framework\Http\Request;
use App\Models\Child;
use App\Services\maternalrecordService;

class DashboardController
{
    private maternalrecordService $maternalrecordService;

    public function __construct()
    {
        $this->maternalrecordService = new maternalrecordService();
    }

    public function index(Request $request)
    {
        $phmId = auth()->id();

        $children = Child::query()->where('phm_id', '=', $phmId)->get();
        $linkedChildrenCount = count($children);

        // Total maternal profiles from parents table
        $maternalprofileCount = count(ParentM::query()->get());

        // Placeholder counts until appointments/vaccinations are fully modeled
        $appointmentsCount = 0;
        $vaccinationsCount = 0;

        // Get antenatal risk data grouped by age
        $riskChartData = $this->maternalrecordService->getAntenatalRiskByAgeGroup();
        
        // Vaccination chart data defaults to empty for now
        $vaccinationChartData = [0, 0, 0];

        return view("phm/dashboard", [
            'linkedChildrenCount' => $linkedChildrenCount,
            'maternalprofileCount' => $maternalprofileCount,
            'appointmentsCount' => $appointmentsCount,
            'vaccinationsCount' => $vaccinationsCount,
            'riskChartData' => $riskChartData,
            'vaccinationChartData' => $vaccinationChartData,
            'appointments' => [],
            'vaccinations' => [],
        ]);
    }
}