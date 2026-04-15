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

        $search = $request->query("search", "");
        $filters = $request->query("filters", []);
        $parentId = auth()->user()->id;
        $overview = $this->vaccinationService->getParentVaccinationOverview($parentId, $search, $filters);
        $childrenList = $this->vaccinationService->getLinkedChildrenListByParentId($parentId);

        return view("parent/vaccination", [
            "vaccinations" => $overview['vaccinations'],
            "links" => $overview['links'],
            "childrenList" => $childrenList,
            "timelineGroups" => $overview['timelineGroups'],
            "statusTotals" => $overview['statusTotals'],
            "totalRecords" => $overview['totalRecords']
        ]);
    }
   
}
