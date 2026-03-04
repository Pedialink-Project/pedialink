<?php

namespace App\Controllers\PublicHealthMidwife;

use App\Models\Child;
use App\Models\Vaccination;
use App\Models\VaccinationReminder;
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

    public function markAsCompleted(Request $request, int $id, int $recordId)
    {
        $child = Child::find($id);
        if (!$child) {
            return redirect(route("phm.child.vaccinations", ["id" => $id]))
                ->withMessage(
                    "An error occured while marking the vaccination record as completed. Please try again.",
                    "Error",
                    "error"
                );
        }

        $vaccinationReminder = VaccinationReminder::find($recordId);
        if (!$vaccinationReminder || $vaccinationReminder->child_id !== $id) {
            return redirect(route("phm.child.vaccinations", ["id" => $id]))
                ->withMessage(
                    "An error occured while marking the vaccination record as completed. Please try again.",
                    "Error",
                    "error"
                );
        }

        $vaccination = new Vaccination();
        $vaccination->child_id = $id;
        $vaccination->schedule_vaccine_id = $vaccinationReminder->schedule_vaccine_id;
        $vaccination->administered_at = (new \DateTime())->format("Y-m-d H:i:sP");
        $vaccination->recorded_at = (new \DateTime())->format("Y-m-d H:i:sP");
        $success = $vaccination->save();

        if ($success) {
            $vaccinationReminder->status = "complete";
            $vaccinationReminder->save();
        }

        return redirect(route("phm.child.vaccinations", ["id" => $id]))
            ->withMessage(
                "Vaccination record marked as completed successfully.",
                "Success",
                "success"
            );
    }
}