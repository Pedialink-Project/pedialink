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
        $remainders = VaccinationReminder::query()->whereIn('child_id', $childIds);

        if (isset($filters['status'])) {
            $remainders = $remainders
                ->whereIn("status", $filters['status']);
        }

        $remainders = $remainders   
            ->orderBy("scheduled_date", "DESC")
            ->paginate(10)
            ->toArray();

        $resource = [];
        foreach ($remainders['items'] as $remainder) {
            $sheduledVaccine = $remainder->getScheduleVaccine();
            $schedule = $sheduledVaccine ? $sheduledVaccine->getSchedule() : null;
            $vaccine =  $sheduledVaccine->getVaccine();
            $vaccination = $remainder->getLinkedVaccination();
            $child = $remainder->getChild();
            $resource[] = [
                "id" => $remainder->id,
                "scheduled_date" => $remainder->scheduled_date,
                "child" => $child ? [
                    "id" => $child->id,
                    "name" => $child->name,
                ] : null,
                "sheduledVaccine" => $sheduledVaccine ? [
                    "dose_number" => $sheduledVaccine->name,
                    "additional_information" => $sheduledVaccine->additional_information,
                ] : null,
                "schedule" => $schedule ? [
                    "id" => $schedule->id,
                    "name" => $schedule->name,
                ] : null,
                "vaccine" => $vaccine ? [
                    "id" => $vaccine->id,
                    "name" => $vaccine->name,
                    "code" => $vaccine->code,
                ] : null,
                "status" => $remainder->status,
                "administered_at" => $vaccination ? $vaccination->administered_at : null,
                 "recorded_at" => $vaccination ? $vaccination->recorded_at : null,


            ];
        }

        $links = array_diff_key($remainders, ['items' => true]);

        return [$resource, $links];
    }
}
