<?php

namespace App\Services\Parent;

use App\Helpers\Calculator;
use App\Models\Appointment;
use App\Models\Maternal;
use App\Models\ParentChild;
use App\Models\VaccinationReminder;
use App\Rules\TextRule;

class VaccinationService
{
    public function getChildVaccinationByParentId($parentId, string $search, array $filters = [])
    {
        $childIds = ParentChild::query()->where("parent_id", '=', $parentId)->pluck("child_id");
        $vaccinations = VaccinationReminder::query()->whereIn('child_id', $childIds);

        if (isset($filters['status'])) {
            $vaccinations = $vaccinations
                ->whereIn("status", $filters['status']);
        }

        $vaccinations = $vaccinations
            ->orderBy("scheduled_date", "DESC")
            ->paginate(10)
            ->toArray();

        $resource = [];
        foreach ($vaccinations['items'] as $vaccination) {
            $sheduledVaccine = $vaccination->getScheduleVaccine();
            $vaccine =  $sheduledVaccine->getVaccine() ;
            $child = $vaccination->getChild();
            $resource[] = [
                "id" => $vaccination->id,
                "scheduled_date" => $vaccination->scheduled_date,
                "child" => $child ? [
                    "id" => $child->id,
                    "name" => $child->name,
                ] : null,
                "sheduledVaccine" => $sheduledVaccine ? [
                    "dose_number" => $sheduledVaccine->name,
                    "min_age_days" => $sheduledVaccine->min_age_days,
                    "due_age_days" => $sheduledVaccine->due_age_days,
                    "min_age_gap_days" => $sheduledVaccine->min_age_gap_days,
                    "additional_information" => $sheduledVaccine->additional_information,
                ] : null,
                "vaccine" =>$vaccine ? [
                    "id" => $vaccine->id,
                    "name" => $vaccine->name,
                    "code" => $vaccine->code,
                ] : null,
                "status" => $vaccination->status
            ];
        }

                $links = array_diff_key($vaccinations, ['items' => true]);

                return [$resource, $links];

    }
}
