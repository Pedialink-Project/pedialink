<?php

namespace App\Services\Admin;

use App\Helpers\IntToDayName;
use App\Models\Appointment;
use App\Models\ClinicWeeklyAvailability;
use InfiniteIterator;

class AppointmentService
{
    public function getAppointmentOverviewData(string $search, array $filters = [])
    {
        $appointments = Appointment::query();

            if (isset($filters['status'])) {
                $appointments = $appointments
                    ->whereIn("status", $filters['status']);
            }

        $appointments = $appointments
            ->orderBy("id", "ASC")
            ->paginate(10)
            ->toArray();

        $resource = [];
        foreach ($appointments['items'] as $appointment) {
            $slot = $appointment->getSlot();
            $doctor = $slot->getDoctor();
            $maternal = $appointment->getMaternal();
            $child = $appointment->getChild();
            $resource[] = [
                "id" => $appointment->id,
                "slot_date" => $slot->slot_date,
                "start_time" => $slot->start_time,
                "end_time" => $slot->end_time,
                "doctor" => $doctor ? [
                    "id" => $doctor->id,
                    "name" => $doctor->getUser()->name
                ] : null,
                "child" => $child ? [
                    "id" => $child->id,
                    "name" => $child->name,
                    "division" => $child->getArea()->code,
                ] : null,
                "maternal" => $maternal ? [
                    "id" => $maternal->id,
                    "name" => $maternal->getUser()->name,
                    "division" => $maternal->getParent()->getArea()->code,
                ] : null,
                "reason" => $appointment->reason,
                "status" => $appointment->status
            ];
        }

        $links = array_diff_key($appointments, ['items' => true]);

        return [
            $resource,
            $links
        ];
    }

    public function getAppointmentConfigurationData(string $search, array $filters = [])
    {
        $clinicWeeklyAvailability = ClinicWeeklyAvailability::query();

        // if ($search) {
        //     if (preg_match("/^\d+$/", $search)) {
        //         $clinicWeeklyAvailability = $clinicWeeklyAvailability
        //             ->where("weekday", "=", $search);
        //     }
        // }

        if (isset($filters['status'])) {
            $filterStatus = [1, 0];
            
            foreach ($filters as $filterKey => $filterValue) {
                if ($filterKey === "status") {
                    $filterStatus = array_map(function($status) {
                        return $status === "active" ? 1 : 0;
                    }, $filterValue);
                }
            }

            $clinicWeeklyAvailability = $clinicWeeklyAvailability
                ->whereIn("active", $filterStatus);
        }

        $clinicWeeklyAvailability = $clinicWeeklyAvailability->get();

        $resource = [];

        foreach ($clinicWeeklyAvailability as $availability) {
            $resource[] = [
                "id" => $availability->id,
                "weekday" => IntToDayName::convert($availability->weekday),
                "active" => $availability->active,
                "start_time" => $availability->start_time,
                "end_time" => $availability->end_time,
                "slot_length_minutes" => $availability->slot_length_minutes
            ];
        }

        return $resource;
    }
}