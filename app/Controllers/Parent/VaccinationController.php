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

        [$vaccinations, $links] = $this->vaccinationService->getChildVaccinationByParentId($parentId, $search, $filters);


        return view("parent/vaccination", [
            "vaccinations" => $vaccinations,
            "links" => $links
        ]);
    }
}
