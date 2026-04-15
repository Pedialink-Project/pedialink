<?php

namespace App\Services\PublicHealthMidwife;

use App\Helpers\Calculator;
use App\Models\Appointment;

class AppointmentService
{
    public function getAppointmentData(
        string $search = "",
        array $filters = [],
        $history = false,
        array $info = [
            'type' => 'child',
            'id' => null
        ]
    ) {
        $appointments = Appointment::query();

        // if ($search != '') {
        //     $appointments = $appointments
        //         ->join(''
        //         ->whereHas("getMaternal.getUser", function ($query) use ($search) {
        //             $query->where("name", "LIKE", "%$search%");
        //         })
        //         ->orWhereHas("getChild", function ($query) use ($search) {
        //             $query->where("name", "LIKE", "%$search%");
        //         });
        // }

        if (isset($filters['status'])) {
            $appointments = $appointments
                ->whereIn("appointments.status", $filters['status']);
        }

        $appointments
            ->join('appointment_slots', 'appointments.slot_id', '=', 'appointment_slots.id');

        if ($history) {
            $appointments
                ->whereIn("appointments.status", ["confirmed", "attended", "no-show", "cancelled"])
                ->where($info['type'] === 'child' ? "appointments.child_id" : "appointments.maternal_id", "=", $info['id'])
                ->orderBy("appointment_slots.slot_date", "DESC")
                ->orderBy("appointment_slots.start_time", "DESC");
        } else {
            $today = new \DateTime();

            // Clone today and subtract 5 days
            $startDate = (clone $today)->modify('-5 days')->format('Y-m-d');

            // Clone today and add 14 days
            $endDate = (clone $today)->modify('+14 days')->format('Y-m-d');

            $appointments
                ->where("appointment_slots.slot_date", ">=", $startDate)
                ->where("appointment_slots.slot_date", "<=", $endDate)
                ->orderBy("appointment_slots.slot_date", "DESC")
                ->orderBy("appointment_slots.start_time", "ASC");
        }

        $appointments = $appointments
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
                "start_time" => Calculator::formatTimeToAmPm($slot->start_time),
                "end_time" => Calculator::formatTimeToAmPm($slot->end_time),
                "doctor" => $doctor ? [
                    "id" => $doctor->id,
                    "name" => $doctor->getUser()->name,
                ] : null,
                "child" => $child ? [
                    "id" => $child->id,
                    "name" => $child->name,
                    "division" => $child->getArea()->code,
                    "age" => calculateAge($child->date_of_birth),
                ] : null,
                "maternal" => $maternal ? [
                    "id" => $maternal->id,
                    "name" => $maternal->getUser()->name,
                    "division" => $maternal->getParent()->getArea()->code,
                    "age" => calculateAge($maternal->getParent()->date_of_birth),
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
