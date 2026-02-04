<?php

namespace App\Services\Admin;

use App\Models\ScheduledVaccine;
use App\Models\Vaccine;

class ManageScheduleService
{
    public function getScheduleVaccineData(int $id, string $search)
    {
        # NOTE: implement search later
        $scheduledVaccines = ScheduledVaccine::query()
            ->where("schedule_id", "=", $id)
            ->orderBy("id", "ASC")
            ->paginate(10)
            ->toArray();

        $resource = [];

        foreach ($scheduledVaccines['items'] as $scheduledVaccine) {
            $vaccine = $scheduledVaccine->getVaccine();
            $schedule = $scheduledVaccine->getSchedule();

            $resource[] = [
                "id" => $scheduledVaccine->id,
                "vaccine" => [
                    "name" => $vaccine->name,
                    "code" => $vaccine->code,
                ],
                "schedule" => [
                    "id" => $schedule->id,
                    "name" => $schedule->name
                ],
                "dose_number" => $scheduledVaccine->dose_number,
                "min_age_days" => $scheduledVaccine->min_age_days,
                "due_age_days" => $scheduledVaccine->due_age_days,
                "min_age_gap_days" => $scheduledVaccine->min_age_gap_days,
                "additional_information" => $scheduledVaccine->additional_information
            ];
        }

        $links = array_diff_key($scheduledVaccines, ['items' => true]);

        return [$resource, $links];
    }
}