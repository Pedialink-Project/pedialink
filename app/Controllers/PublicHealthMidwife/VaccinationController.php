<?php

namespace App\Controllers\PublicHealthMidwife;

use App\Models\Child;
use App\Services\PublicHealthMidwife\VaccinationService;
use Library\Framework\Http\Request;

class VaccinationController
{
    private VaccinationService $vaccinationService;

    public function __construct()
    {
        $this->vaccinationService = new VaccinationService();
    }

    public function childVaccinationRecords(Request $request, int $id)
    {
        $search = $request->query("search", "");
        $filters = $request->query("filters", []);
        [$vaccinations, $links] = $this->vaccinationService->fetchVaccinationRecordsByChildId(
            $id,
            $search,
            $filters
        );

        $child = Child::find($id);
        return view("phm/vaccinationrecord", [
            "id" => $id,
            "name" => $child->name,
            "vaccinations" => $vaccinations,
            "links" => $links,
        ]);
    }
}