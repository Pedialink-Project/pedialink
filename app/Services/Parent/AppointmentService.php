<?php

namespace App\Services\Parent;

use App\Helpers\Calculator;
use App\Models\Appointment;
use App\Models\Maternal;
use App\Models\ParentChild;

class AppointmentService
{


    public function getChildAppointmentByParentId($parentId, string $search, array $filters = [] )
    {

        $childIds = ParentChild::query()->where("parent_id", '=', $parentId)->pluck("child_id");
        $appointments = Appointment::query()->whereIn('child_id', $childIds);

        if (isset($filters['status'])) {
            $appointments = $appointments
                ->whereIn("appointments.status", $filters['status']);
        }

        $appointments = $appointments
            ->join("appointment_slots as s", "s.id", "=", "appointments.slot_id")
            ->orderBy("s.slot_date", "DESC")
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
                    "name" => $doctor->getUser()->name
                ] : null,
                "child" => $child ? [
                    "id" => $child->id,
                    "name" => $child->name,
                ] : null,
                "maternal" => $maternal ? [
                    "id" => $maternal->id,
                    "name" => $maternal->getUser()->name,
                ] : null,
                "reason" => $appointment->reason,
                "notes" => $appointment->notes,
                "status" => $appointment->status
            ];
        }

        $links = array_diff_key($appointments, ['items' => true]);

        return [
            $resource,
            $links
        ];
    }

    public function getParentAppointmentByParentId($parentId, string $search, array $filters = [])
    {
        $maternalIds = Maternal::query()->where("parent_id", '=', $parentId)->pluck("id");
        $appointments = Appointment::query()->whereIn('maternal_id', $maternalIds);

        if (isset($filters['status'])) {
            $appointments = $appointments
                ->whereIn("appointments.status", $filters['status']);
        }

        $appointments = $appointments
            ->join("appointment_slots as s", "s.id", "=", "appointments.slot_id")
            ->orderBy("s.slot_date", "DESC")
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
                ] : null,
                "maternal" => $maternal ? [
                    "id" => $maternal->id,
                    "name" => $maternal->getUser()->name,
                ] : null,
                "reason" => $appointment->reason,
                "notes" => $appointment->notes,
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
