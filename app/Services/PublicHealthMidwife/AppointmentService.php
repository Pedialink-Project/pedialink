<?php

namespace App\Services\PublicHealthMidwife;

use App\Models\Appointment;

class AppointmentService
{
    public function getAppointmentData(string $search = "", array $filters = [])
    {
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

        $appointments = $appointments
            ->join('appointment_slots', 'appointments.slot_id', '=', 'appointment_slots.id')
            ->orderBy("appointment_slots.slot_date", "DESC")
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