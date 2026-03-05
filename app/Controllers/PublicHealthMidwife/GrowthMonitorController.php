<?php

namespace App\Controllers\PublicHealthMidwife;

use Library\Framework\Http\Request;
use App\Services\PublicHealthMidwife\GrowthService;

class GrowthMonitorController
{
    private $growthService;

    public function __construct()
    {
        $this->growthService = new GrowthService();
    }

    public function index(Request $request)
    {
        $children = $this->growthService->getChildrenByPhmId(auth()->id());
        $growthData = $this->growthService->getAllChildrenGrowthData(auth()->id());
        return view("phm/growthmonitoring", ['childrenList' => $children, 'growthData' => $growthData]);
    }

    public function childGrowthIndex(Request $request, int $id)
    {

        $growthData = $this->growthService->getChildGrowthDataByChildId($id);

        $child = $this->growthService->getChildById($id);
        return view("phm/onechildgrowthmonitoring", [
            "child" => $child,
            "growthData" => $growthData
        ]);
    }
}
