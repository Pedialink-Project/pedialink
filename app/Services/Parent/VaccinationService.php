<?php

namespace App\Services\Parent;

use App\Helpers\Calculator;
use App\Models\Appointment;
use App\Models\Maternal;
use App\Models\ParentChild;
use App\Models\VaccinationReminder;
use App\Rules\TextRule;
use Library\Framework\Database\Paginator;

class VaccinationService
{
    public function getChildVaccinationByParentId($parentId, string $search, array $filters = [])
    {
        $childIds = ParentChild::query()->where("parent_id", '=', $parentId)->pluck("child_id");
        $query = VaccinationReminder::query()
            ->whereIn('child_id', $childIds)
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
            $remainders = (new Paginator($pageItems, count($filteredReminders), $perPage, $page))->toArray();
        } else {
            $remainders = $query->paginate(10)->toArray();
        }

        $resource = [];
        foreach ($remainders['items'] as $remainder) {
            $sheduledVaccine = $remainder->getScheduleVaccine();
            $schedule = $sheduledVaccine ? $sheduledVaccine->getSchedule() : null;
            $vaccine =  $sheduledVaccine->getVaccine();
            $vaccination = $remainder->getLinkedVaccination();
            $child = $remainder->getChild();
            $status = $remainder->getComputedStatus();
            $resource[] = [
                "id" => $remainder->id,
                "scheduled_date" => $remainder->scheduled_date,
                "child" => $child ? [
                    "id" => $child->id,
                    "name" => $child->name,
                ] : null,
                "sheduled_vaccine" => $sheduledVaccine ? [
                    "dose_number" => $sheduledVaccine->dose_number,
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
                "status" => $status,
                "administered_at" => $vaccination ? $vaccination->administered_at : null,
                "recorded_at" => $vaccination ? $vaccination->recorded_at : null,


            ];
        }

        $links = array_diff_key($remainders, ['items' => true]);

        return [$resource, $links];
    }
}
