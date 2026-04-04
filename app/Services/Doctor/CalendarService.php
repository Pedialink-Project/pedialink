<?php

namespace App\Services\Doctor;

use App\Models\Appointment;

class CalendarService
{
    public function getDoctorCalendarEvents(int $doctorId): array
    {
        return $this->getAppointmentEvents($doctorId);
    }

    private function getAppointmentEvents(int $doctorId): array
    {
        $appointments = Appointment::query()
            ->join('appointment_slots as s', 's.id', '=', 'appointments.slot_id')
            ->where('s.doctor_id', '=', $doctorId)
            ->orderBy('s.slot_date', 'ASC')
            ->get();

        $events = [];

        foreach ($appointments as $appointment) {
            $slot = $appointment->getSlot();
            if (!$slot || !$slot->slot_date) {
                continue;
            }

            $child = $appointment->getChild();
            $maternal = $appointment->getMaternal();

            if ($child) {
                $events[] = [
                    'date' => $slot->slot_date,
                    'type' => 'appointment',
                    'title' => 'Child Appointment',
                    'color' => 'linear-gradient(90deg,#28bdf8,#3b82f6)',
                    'items' => [[
                        'child' => $child->name,
                        'time' => $slot->start_time,
                        'status' => ucfirst((string)$appointment->status),
                    ]],
                ];
            }

            if ($maternal) {
                $events[] = [
                    'date' => $slot->slot_date,
                    'type' => 'maternal',
                    'title' => 'Maternal Appointment',
                    'color' => 'linear-gradient(90deg,#f5a623,#f97316)',
                    'items' => [[
                        'maternal' => $maternal->getUser() ? $maternal->getUser()->name : 'Maternal',
                        'time' => $slot->start_time,
                        'status' => ucfirst((string)$appointment->status),
                    ]],
                ];
            }
        }

        return $events;
    }
}
