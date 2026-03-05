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
        return view("phm/growthmonitoring",['children' => $children]);

    }

    public function childGrowthIndex(Request $request, int $id)
    {
        return view("phm/growthmonitoring", [
            "id" => $id,
        ]);
    }
}