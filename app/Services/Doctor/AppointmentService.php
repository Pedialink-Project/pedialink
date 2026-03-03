<?php

namespace App\Services\Doctor;

use App\Helpers\AppointmentConfigurationHelper;
use App\Helpers\IntToDayName;
use App\Helpers\Validator;
use App\Models\Appointment;
use App\Models\ClinicWeeklyAvailability;
use App\Models\DoctorWeeklyAvailability;

class AppointmentService
{
    public function getAppointmentOverviewData(string $search, array $filters = [])
    {
        $appointments = Appointment::query();

            if (isset($filters['status'])) {
                $appointments = $appointments
                    ->whereIn("appointments.status", $filters['status']);
            }

        $appointments = $appointments
            ->join('appointment_slots', 'appointments.slot_id', '=', 'appointment_slots.id')
            ->whereNotNull("appointment_slots.doctor_id")
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

    public function getAvailableWeekdays(): array
    {
        $availableWeekdays = [];
        for ($i = 0; $i < 7; $i++) {
            $availableWeekdays[] = [
                "value" => $i,
                "name" => IntToDayName::convert($i)
            ];
        }

        $doctorWeeklyAvailability = DoctorWeeklyAvailability::query()
            ->select("weekday")
            ->where("doctor_id", "=", auth()->user()?->id)
            ->get();

        $doctorWeekdays = [];
        foreach ($doctorWeeklyAvailability as $availability) {
            $doctorWeekdays[] = $availability->weekday;
        }

        foreach ($availableWeekdays as $key => $weekday) {
            if (in_array($weekday['value'], $doctorWeekdays)) {
                unset($availableWeekdays[$key]);
            }
        }

        return array_values($availableWeekdays);
    }

    public function getAvailableClinicWeekdays()
    {
        $clinicWeeklyAvailability = ClinicWeeklyAvailability::query()
            ->where("active", "=", 1)
            ->orderBy('weekday', 'ASC')
            ->get();

        $weekday = [];

        foreach ($clinicWeeklyAvailability as $availability) {
            $weekday[] = [
                "value" => $availability->weekday,
                "name" => IntToDayName::convert($availability->weekday),
            ];
        }

        return $weekday;
    }

    public function getAppointmentConfigurationData(string $search, array $filters)
    {
        $clinicWeeklyAvailability = DoctorWeeklyAvailability::query();

        if ($search !== "") {
            $weekday = AppointmentConfigurationHelper::weekdaySearch($search);
            if ($weekday !== -1) {
                $clinicWeeklyAvailability = $clinicWeeklyAvailability
                    ->where("weekday", "=", $weekday);
            }
        }

        if (isset($filters['status'])) {
            $value = AppointmentConfigurationHelper::statusFilter($filters['status']);
            $clinicWeeklyAvailability = $clinicWeeklyAvailability
                ->whereIn("active", $value);
        }

        $clinicWeeklyAvailability = $clinicWeeklyAvailability
            ->where("doctor_id", "=", auth()->user()?->id)
            ->orderBy('weekday', 'ASC')
            ->get();

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

    private function validateWeekday(string $weekday)
    {
        if (!Validator::validateFieldExistence($weekday)) {
            return "Weekday is required";
        }

        $weekday = $weekday !== '' ? (int)$weekday : -1;

        if (IntToDayName::convert($weekday) === "Unknown") {
            return "Invalid weekday";
        }

        $doctorAvailability = DoctorWeeklyAvailability::query()
            ->where("weekday", "=", $weekday)
            ->where("doctor_id", "=", auth()->user()?->id)
            ->first();

        if ($doctorAvailability) {
            return "Weekday is already configured";
        }

        $clinicAvailability = ClinicWeeklyAvailability::query()
            ->where("weekday", "=", $weekday)
            ->first();

        if (!$clinicAvailability) {
            return "Weekday is not available according to clinic configuration";
        }

        return null;
    }

    private function validateStartAndEndTime(string $startTime, string $endTime)
    {
        $error = null;
        if (!Validator::validateFieldExistence($startTime)) {
            $error = ["type" => "start", "error" => "Start time is required"];
            return $error;
        }

        if (!Validator::validateFieldExistence($endTime)) {
            $error = ["type" => "end", "error" => "End time is required"];
            return $error;
        }

        // if (!Validator::validateTimeFormat($startTime)) {
        //     $error = ["type" => "start", "error" => "Invalid start time format"];
        //     return $error;
        // }

        // if (!Validator::validateTimeFormat($endTime)) {
        //     $error = ["type" => "end", "error" => "Invalid end time format"];
        //     return $error;
        // }
        if (strtotime($startTime) >= strtotime($endTime)) {
            $error = ["type" => "start", "error" => "Start time must be before end time"];
            return $error;
        }

        return $error;
    }

    public function validateAvailabilityData(array $data, $edit = false)
    {
        $errors = [];

        $prefix = '';
        if ($edit) {
            $prefix = 'e_';
        } else {
            // Only for create weekday form
            $weekdayError = $this->validateWeekday($data['weekday']);
            if ($weekdayError) {
                $errors['weekday'] = $weekdayError;
            }
        }

        $startAndEndTimeError = $this->validateStartAndEndTime($data[$prefix . 'start_time'], $data[$prefix . 'end_time']);
        if ($startAndEndTimeError) {
            if ($startAndEndTimeError['type'] === "start") {
                $errors[$prefix . 'start_time'] = $startAndEndTimeError['error'];
            } else if ($startAndEndTimeError['type'] === "end") {
                $errors[$prefix . 'end_time'] = $startAndEndTimeError['error'];
            }
        }

        return $errors;
    }
}