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


    public function getParentVaccinationOverview(int $parentId): array
    {
        [$vaccinations, $links] = $this->getChildVaccinationByParentId($parentId, "", []);

        $groupedRecords = [];
        $statusTotals = [
            'complete' => 0,
            'pending' => 0,
            'overdue' => 0,
        ];

        foreach ($vaccinations as $record) {
            $status = strtolower((string) ($record['status'] ?? 'pending'));
            if (!isset($statusTotals[$status])) {
                $statusTotals[$status] = 0;
            }
            $statusTotals[$status]++;

            $scheduledDateKey = !empty($record['scheduled_date'])
                ? (new \DateTimeImmutable((string) $record['scheduled_date']))->format('Y-m-d')
                : 'unscheduled';

            $groupName = $scheduledDateKey === 'unscheduled'
                ? 'Unscheduled'
                : (new \DateTimeImmutable((string) $record['scheduled_date']))->format('F j, Y');

            $groupedRecords[$scheduledDateKey]['name'] = $groupName;
            $groupedRecords[$scheduledDateKey]['items'][] = $record;
        }

        foreach ($groupedRecords as &$group) {
            $items = $group['items'] ?? [];
            usort($items, function ($left, $right) {
                $leftDate = !empty($left['scheduled_date']) ? strtotime((string) $left['scheduled_date']) : 0;
                $rightDate = !empty($right['scheduled_date']) ? strtotime((string) $right['scheduled_date']) : 0;

                if ($leftDate === $rightDate) {
                    return strcmp((string) ($left['vaccine']['code'] ?? ''), (string) ($right['vaccine']['code'] ?? ''));
                }

                return $leftDate <=> $rightDate;
            });
            $group['items'] = $items;
        }
        unset($items);
        unset($group);

        uksort($groupedRecords, function ($left, $right) use ($groupedRecords) {
            $leftItems = $groupedRecords[$left]['items'] ?? [];
            $rightItems = $groupedRecords[$right]['items'] ?? [];

            $leftDate = 0;
            foreach ($leftItems as $item) {
                if (!empty($item['scheduled_date'])) {
                    $leftDate = strtotime((string) $item['scheduled_date']);
                    break;
                }
            }

            $rightDate = 0;
            foreach ($rightItems as $item) {
                if (!empty($item['scheduled_date'])) {
                    $rightDate = strtotime((string) $item['scheduled_date']);
                    break;
                }
            }

            if ($leftDate === $rightDate) {
                return strcasecmp($left, $right);
            }

            return $leftDate <=> $rightDate;
        });

        $timelineGroups = [];
        foreach ($groupedRecords as $groupKey => $group) {
            $items = $group['items'] ?? [];
            $groupName = $group['name'] ?? 'Unscheduled';
            $groupComplete = 0;
            $groupPending = 0;
            $groupOverdue = 0;

            foreach ($items as $item) {
                $itemStatus = strtolower((string) ($item['status'] ?? 'pending'));
                if ($itemStatus === 'complete') {
                    $groupComplete++;
                } elseif ($itemStatus === 'pending') {
                    $groupPending++;
                } elseif ($itemStatus === 'overdue') {
                    $groupOverdue++;
                }
            }

            $groupCount = count($items);
            $groupBadgeType = 'purple';
            $groupStatusLabel = 'Upcoming';

            if ($groupOverdue > 0) {
                $groupBadgeType = 'red';
                $groupStatusLabel = 'Action needed';
            } elseif ($groupComplete === $groupCount && $groupCount > 0) {
                $groupBadgeType = 'green';
                $groupStatusLabel = 'Completed';
            } elseif ($groupPending > 0) {
                $groupBadgeType = 'purple';
                $groupStatusLabel = 'Scheduled';
            }

            $timelineGroups[] = [
                'name' => $groupName,
                'items' => $items,
                'count' => $groupCount,
                'badgeType' => $groupBadgeType,
                'statusLabel' => $groupStatusLabel,
            ];
        }

        $totalRecords = count($vaccinations);


        return [$timelineGroups, $statusTotals, $totalRecords];
    }


    public function getLinkedChildrenListByParentId(int $parentId)
    {
        $childrenParent = ParentChild::query()->where('parent_id', '=', $parentId)->get();

        $resource = [];
        foreach ($childrenParent as $childParent) {
            $child = $childParent->getChild();
            $resource[] = [
                'id' => $child->id,
                'name' => $child->name,
            ];
        }

        return $resource;
    }
}
