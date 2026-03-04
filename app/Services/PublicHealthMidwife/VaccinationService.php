<?php

namespace App\Services\PublicHealthMidwife;

use App\Models\Vaccination;
use App\Models\VaccinationReminder;

class VaccinationService
{
    public function fetchVaccinationRecordsByChildId(int $childId, string $search = "", array $filters = []): array
    {
        $vaccinationRemainder = VaccinationReminder::query()
            ->where("child_id", "=", $childId);

        // if ($search) {
        //     $vaccinationRecords = $vaccinationRecords
        //         ->where(function ($query) use ($search) {
        //             $query->where("vaccine_name", "LIKE", "%$search%")
        //                 ->orWhere("notes", "LIKE", "%$search%");
        //         });
        // }

        if (isset($filters['status'])) {
            $vaccinationRemainder = $vaccinationRemainder
                ->whereIn("status", $filters['status']);
        }

        $vaccinationRemainder = $vaccinationRemainder
            ->orderBy("scheduled_date", "DESC")
            ->paginate(10)
            ->toArray();

        $resource = [];
        foreach ($vaccinationRemainder['items'] as $remainder) {
            $scheduleVaccine = $remainder->getScheduleVaccine();
            $schedule = $scheduleVaccine ? $scheduleVaccine->getSchedule() : null;
            $vaccine = $scheduleVaccine ? $scheduleVaccine->getVaccine() : null;
            $vaccination = $remainder->getLinkedVaccination();

            $recorded_age = calculateAge($remainder->getChild()->date_of_birth, new \DateTimeImmutable($remainder->scheduled_date));
            $resource[] = [
                "id" => $remainder->id,
                "vaccine" => $vaccine ? [
                    "id" => $vaccine->id,
                    "name" => $vaccine->name,
                    "code" => $vaccine->code,
                ] : null,
                "schedule_vaccine" => [
                    "dose_number" => $scheduleVaccine ? $scheduleVaccine->dose_number : null,
                    "additional_information" => $scheduleVaccine ? $scheduleVaccine->additional_information : null,
                ],
                "schedule" => $schedule ? [
                    "id" => $schedule->id,
                    "name" => $schedule->name,
                ] : null,
                "status" => $remainder->status,
                "recorded_age" => $recorded_age,
                "scheduled_date" => $remainder->scheduled_date,
                "administered_at" => $vaccination ?
                    (new \DateTime($vaccination->administered_at))
                        ->format('H:i')
                    : null,
                "recorded_at" => $vaccination ? $vaccination->recorded_at : null,
                // "notes" => $scheduleVaccine ? $scheduleVaccine->additional : "",
            ];
        }

        $links = array_diff_key($vaccinationRemainder, ['items' => true]);
        
        return [
            $resource,
            $links
        ];
    }
}