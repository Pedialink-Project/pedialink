<?php

namespace App\Services\Doctor;

use App\Models\Appointment;

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

            if ($doctor == null || ($doctor && $doctor->id !== auth()->user()?->id)) {
                continue; // Skip appointments that don't belong to the logged-in doctor
            }

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
}