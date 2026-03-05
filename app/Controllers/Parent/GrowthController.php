<?php

namespace App\Controllers\Parent;

use App\Services\Parent\GrowthService;

class GrowthController
{
    private $growthService;

    public function __construct()
    {
        $this->growthService = new GrowthService();
    }

    public function index()
    {
        $parentId = auth()->user()->id;

        $growthData = $this->growthService->getGrowthData($parentId);
        $childrenList = $this->growthService->getLinkedChildrenListByParentId($parentId);


        return view("parent/growth-tracking", [
            "growthData" => $growthData,
            "childrenList" => $childrenList
        ]);
    }
}
