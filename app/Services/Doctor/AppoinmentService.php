<?php

namespace App\Services\Doctor;

use App\Helpers\AppointmentConfigurationHelper;
use App\Helpers\Calculator;
use App\Helpers\IntToDayName;
use App\Helpers\Validator;
use App\Models\Appointment;
use App\Models\ClinicWeeklyAvailability;
use App\Models\DoctorWeeklyAvailability;

class AppointmentService
{
    public function getAppointmentOverviewData(
        string $search,
        array $filters = [],
        $history = false,
        array $info = [
            'type' => 'child',
            'id' => null
        ]
    )
    {
        $appointments = Appointment::query();

        if (isset($filters['status'])) {
            $appointments = $appointments
                ->whereIn("appointments.status", $filters['status']);
        }

        $appointments
            ->join('appointment_slots', 'appointments.slot_id', '=', 'appointment_slots.id')
            ->whereNotNull("appointment_slots.doctor_id")
            ->where("appointment_slots.doctor_id", "=", auth()->user()?->id);

        if ($history) {
            $appointments
                ->whereIn("appointments.status", ["confirmed", "attended", "no-show", "cancelled"])
                ->where($info['type'] === 'child' ? "appointments.child_id" : "appointments.maternal_id", "=", $info['id'])
                ->orderBy("appointment_slots.slot_date", "DESC")
                ->orderBy("appointment_slots.start_time", "DESC");
        } else {