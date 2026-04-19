<?php

namespace App\Services\Doctor;

use App\Helpers\Calculator;
use App\Models\Appointment;
use App\Models\Child;
use App\Models\ChildRecord;
use App\Models\Doctor;
use App\Models\Maternal;
use App\Models\MaternalRecord;
use Library\Framework\Database\QueryBuilder;

class DashboardService
{
    public function getPatientsCount()
    {
        return count(Maternal::query()->get()) + count(Child::query()->get());
    }

    public function getAppointmentsCount()
    {
        $appointments = Appointment::query()
            ->join('appointment_slots', 'appointments.slot_id', '=', 'appointment_slots.id')
            ->where("appointment_slots.doctor_id", "=", auth()->user()->id)
            ->whereIn("appointments.status", ["confirmed", "pending"])
            ->get();
        return count($appointments);
    }