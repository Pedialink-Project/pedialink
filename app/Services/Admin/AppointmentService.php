<?php

namespace App\Services\Admin;

use App\Helpers\AppointmentConfigurationHelper;
use App\Helpers\Calculator;
use App\Helpers\IntToDayName;
use App\Helpers\Validator;
use App\Models\Appointment;
use App\Models\ClinicWeeklyAvailability;

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
            ->orderBy('weekday', 'ASC')
            ->get();

        $resource = [];

        foreach ($clinicWeeklyAvailability as $availability) {
            $resource[] = [
                "id" => $availability->id,
                "weekday" => IntToDayName::convert($availability->weekday),
                "active" => $availability->active,
                "start_time" => Calculator::formatTimeToAmPm($availability->start_time),
                "end_time" => Calculator::formatTimeToAmPm($availability->end_time),
                "slot_length_minutes" => $availability->slot_length_minutes
            ];
        }

        return $resource;
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

    private function validateSlotLength(int $slotLength)
    {
        $error = null;
        if (!Validator::validateFieldExistence($slotLength)) {
            $error = ["Slot length is required"];
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
        }

        $startAndEndTimeError = $this->validateStartAndEndTime($data[$prefix . 'start_time'], $data[$prefix . 'end_time']);
        if ($startAndEndTimeError) {
            if ($startAndEndTimeError['type'] === "start") {
                $errors[$prefix . 'start_time'] = $startAndEndTimeError['error'];
            } else if ($startAndEndTimeError['type'] === "end") {
                $errors[$prefix . 'end_time'] = $startAndEndTimeError['error'];
            }
        }

        $slotLengthError = $this->validateSlotLength($data[$prefix . 'slot_length_minutes']);
        if ($slotLengthError) {
            $errors[$prefix . 'slot_length_minutes'] = $slotLengthError[0];
        }

        return $errors;
    }
}