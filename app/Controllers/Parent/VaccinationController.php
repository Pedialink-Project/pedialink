<?php

namespace App\Controllers\Parent;

use App\Services\Parent\VaccinationService;
use Library\Framework\Http\Request;

class VaccinationController
{

    private VaccinationService $vaccinationService;

    public function __construct()
    {
        $this->vaccinationService = new VaccinationService();
    }
    public function index(Request $request)
    {
        $parentId = auth()->user()->id;
        [$timelineGroups, $statusTotals, $totalRecords] = $this->vaccinationService->getParentVaccinationOverview($parentId);
        $childrenList = $this->vaccinationService->getLinkedChildrenListByParentId($parentId);

        return view("parent/vaccination", [
            "timelineGroups" => $timelineGroups,
            "statusTotals" => $statusTotals,
            "totalRecords" => $totalRecords,
            "childrenList" => $childrenList
        ]);
    }
   
}
