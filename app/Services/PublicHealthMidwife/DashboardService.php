<?php

namespace App\Services\PublicHealthMidwife;

use App\Models\Appointment;
use App\Models\Child;
use App\Models\PublicHealthMidwife;
use App\Models\VaccinationReminder;
use Library\Framework\Database\QueryBuilder;

class DashboardService
{
    public function getLinkedChildrenCount()
    {
        $currentUser = auth()->user();
        if ($currentUser) {
            $phm = PublicHealthMidwife::find($currentUser->id);

            $linkedChildren = Child::query()
                ->where("phm_id", "=", $phm->id)
                ->get();

            return count($linkedChildren);
        }
    }

    public function getAppointmentsCount()
    {
        $currentUser = auth()->user();
        if ($currentUser) {
            $phm = PublicHealthMidwife::find($currentUser->id);

            $linkedChildren = Child::query()
                ->select("id")
                ->where("phm_id", "=", $phm->id)
                ->get();

            $childIds = array_map(function ($child) {
                return $child->id;
            }, $linkedChildren);


            $appointment = Appointment::query()
                ->whereIn("child_id", $childIds)
                ->whereIn("status", ["confirmed", "pending"])
                ->get();

            return count($appointment);
        }
    }

    public function getUpcomingVaccinationsCount()
    {
        $currentUser = auth()->user();
        if ($currentUser) {
            $phm = PublicHealthMidwife::find($currentUser->id);

            $linkedChildren = Child::query()
                ->select("id")
                ->where("phm_id", "=", $phm->id)
                ->get();

            $childIds = array_map(function ($child) {
                return $child->id;
            }, $linkedChildren);

            $upcomingVaccinations = Child::query()
                ->join("vaccination_reminders as vr", "vr.child_id", "=", "children.id")
                ->whereIn("children.id", $childIds)
                ->where("vr.status", "=", "pending")
                ->get();

            return count($upcomingVaccinations);
        }
    }

    public function upcomingAppointments()
    {
        $currentUser = auth()->user();
        if ($currentUser) {
            $phm = PublicHealthMidwife::find($currentUser->id);

            $linkedChildren = Child::query()
                ->select("id")
                ->where("phm_id", "=", $phm->id)
                ->get();

            $childIds = array_map(function ($child) {
                return $child->id;
            }, $linkedChildren);

            $appointments = Appointment::query()
                ->whereIn("child_id", $childIds)
                ->whereIn("status", ["confirmed", "pending"])
                ->orderBy("id", "ASC")
                ->paginate(3)
                ->toArray();

            $resource = [];

            foreach ($appointments['items'] as $appointment) {
                $child = $appointment->getChild();
                $slot = $appointment->getSlot();
                $doctor = $slot ? $slot->getDoctor() : null;
                $resource[] = [
                    "id" => $appointment->id,
                    "child_name" => $child ? $child->name : null,
                    "slot_date" => $slot ? $slot->slot_date : null,
                    "start_time" => $slot ? $slot->start_time : null,
                    "end_time" => $slot ? $slot->end_time : null,
                    "status" => $appointment->status,
                    "reason" => $appointment->reason,
                    "doctor_name" => $doctor ? $doctor->getUser()->name : null,
                ];
            }

            return $resource;
        }
    }

    public function upcomingVaccinations()
    {
        $currentUser = auth()->user();
        if ($currentUser) {
            $phm = PublicHealthMidwife::find($currentUser->id);

            $linkedChildren = Child::query()
                ->select("id")
                ->where("phm_id", "=", $phm->id)
                ->get();

            $childIds = array_map(function ($child) {
                return $child->id;
            }, $linkedChildren);

            $upcomingVaccinations = VaccinationReminder::query()
                ->join("children", "vaccination_reminders.child_id", "=", "children.id")
                ->whereIn("children.id", $childIds)
                ->where("vaccination_reminders.status", "=", "pending")
                ->orderBy("vaccination_reminders.scheduled_date", "ASC")
                ->paginate(3)
                ->toArray();

            $resource = [];
            foreach ($upcomingVaccinations['items'] as $item) {
                $child = Child::find($item->child_id);
                $scheduleVaccine = $item->getScheduleVaccine();
                $vaccine = $scheduleVaccine ? $scheduleVaccine->getVaccine() : null;
                $resource[] = [
                    "id" => $item->id,
                    "child_name" => $child ? $child->name : null,
                    "scheduled_date" => $item->scheduled_date,
                    "vaccine_code" => $vaccine ? $vaccine->code : null,
                ];
            }

            return $resource;
        }
    }

    public function maternalRiskData(): array
    {
        $currentUser = auth()->user();
        if (!$currentUser) {
            return [
                'labels' => ['18 - 25', '25 - 30', '30 - 40', '40 - 50', '50+'],
                'good' => [0, 0, 0, 0, 0],
                'at_risk' => [0, 0, 0, 0, 0],
                'critical' => [0, 0, 0, 0, 0],
            ];
        }

        $sql = "
            SELECT
                CASE
                    WHEN EXTRACT(YEAR FROM AGE(p.date_of_birth)) >= 18 AND EXTRACT(YEAR FROM AGE(p.date_of_birth)) < 25 THEN '18 - 25'
                    WHEN EXTRACT(YEAR FROM AGE(p.date_of_birth)) >= 25 AND EXTRACT(YEAR FROM AGE(p.date_of_birth)) < 30 THEN '25 - 30'
                    WHEN EXTRACT(YEAR FROM AGE(p.date_of_birth)) >= 30 AND EXTRACT(YEAR FROM AGE(p.date_of_birth)) < 40 THEN '30 - 40'
                    WHEN EXTRACT(YEAR FROM AGE(p.date_of_birth)) >= 40 AND EXTRACT(YEAR FROM AGE(p.date_of_birth)) < 50 THEN '40 - 50'
                    ELSE '50+'
                END as age_group,
                mr.health_status,
                COUNT(*) as count
            FROM maternal_records mr
            JOIN parents p ON mr.parent_id = p.id
            JOIN maternal m ON m.parent_id = p.id
            JOIN maternal_access_requests mar ON mar.maternal_id = p.id
            WHERE mar.staff_id = :staff_id
            AND m.type = 'antenatal'
            GROUP BY age_group, mr.health_status
            ORDER BY age_group, mr.health_status
        ";

        $results = QueryBuilder::rawGet($sql, ['staff_id' => $currentUser->id]);

        // Initialize data structure
        $labels = ['18 - 25', '25 - 30', '30 - 40', '40 - 50', '50+'];
        $data = [
            'labels' => $labels,
            'good' => array_fill(0, 5, 0),
            'at_risk' => array_fill(0, 5, 0),
            'critical' => array_fill(0, 5, 0),
        ];

        // Map age groups to indices
        $ageGroupIndex = array_flip($labels);

        // Populate data from results
        foreach ($results as $row) {
            $ageGroup = $row['age_group'];
            $healthStatus = $row['health_status'];
            $count = (int) $row['count'];

            if (isset($ageGroupIndex[$ageGroup]) && isset($data[$healthStatus])) {
                $index = $ageGroupIndex[$ageGroup];
                $data[$healthStatus][$index] = $count;
            }
        }

        return $data;
    }
}