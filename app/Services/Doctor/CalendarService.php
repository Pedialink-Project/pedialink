<?php

namespace App\Services\Doctor;

use App\Helpers\Calculator;
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