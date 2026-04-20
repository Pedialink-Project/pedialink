<?php

namespace App\Controllers;

use App\Models\Child;
use App\Services\VaccinationService;
use Library\Framework\Http\Request;

class VaccinationController
{
    private VaccinationService $vaccinationService;

    public function __construct()
    {
        $this->vaccinationService = new VaccinationService();
    }
    public function childVaccinationCard(Request $request, int $id)
    {
        $child = Child::find($id);

        if (!$child) {
            return redirect(route("home"))
                ->withMessage(
                    "Child not found",
                    "Error",
                    "error"
                );
        }

        if (auth()->user()->isPublicHealthMidwife()) {
            // empty
        } else if (auth()->user()->isParent()) {
            $parents = $child->getParents();
            $allow = false;
            foreach ($parents as $parent) {
                if ($parent->id === auth()->user()->id) {
                    $allow = true;
                    break;
                }
            }
            if (!$allow) {
                return redirect(route("home"))
                    ->withMessage(
                        "You do not have permission to view this child's vaccination card",
                        "Error",
                        "error"
                    );
            }
        }

        [$vaccinations, $links] = $this->vaccinationService
            ->fetchVaccinationRecordsByChildId($id, "", [], true);

        return view("vaccination/childcard", [
            "id" => $id,
            "name" => $child?->name ?? "Unknown Child",
            "child" => $child,
            "vaccinations" => $vaccinations,
            "links" => $links,
            "backUrl" => "javascript:history.back()",
        ]);
    }
}