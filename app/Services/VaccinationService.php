<?php

namespace App\Services;

use App\Models\Vaccination;
use App\Models\VaccinationReminder;
use Library\Framework\Database\Paginator;

class VaccinationService
{
    public function fetchVaccinationRecordsByChildId(int $childId, string $search = "", array $filters = []): array
    {
        $query = VaccinationReminder::query()
            ->where("child_id", "=", $childId)
            ->orderBy("scheduled_date", "DESC");

        if (isset($filters['status']) && !empty($filters['status'])) {
            $allReminders = $query->get();
            $allowedStatuses = array_map('strtolower', $filters['status']);
            $filteredReminders = array_values(array_filter(
                $allReminders,
                fn($reminder) => in_array($reminder->getComputedStatus(), $allowedStatuses, true)
            ));

            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $page = max(1, $page);
            $perPage = 10;
            $offset = ($page - 1) * $perPage;
            $pageItems = array_slice($filteredReminders, $offset, $perPage);
            $vaccinationRemainder = (new Paginator($pageItems, count($filteredReminders), $perPage, $page))->toArray();
        } else {
            $vaccinationRemainder = $query->paginate(10)->toArray();
        }

        $resource = [];
        foreach ($vaccinationRemainder['items'] as $remainder) {
            $scheduleVaccine = $remainder->getScheduleVaccine();
            $schedule = $scheduleVaccine ? $scheduleVaccine->getSchedule() : null;
            $vaccine = $scheduleVaccine ? $scheduleVaccine->getVaccine() : null;
            $vaccination = $remainder->getLinkedVaccination();
            $status = $remainder->getComputedStatus();

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
                "status" => $status,
                "recorded_age" => $recorded_age,
                "scheduled_date" => $remainder->scheduled_date,
                "administered_at" => $vaccination ?
                    (new \DateTime($vaccination->administered_at))
                        ->setTimezone(new \DateTimeZone('Asia/Colombo'))
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