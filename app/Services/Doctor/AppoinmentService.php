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

            if ($doctor == null || ($doctor && $doctor->id !== auth()->user()?->id)) {
                continue; // Skip appointments that don't belong to the logged-in doctor
            }

            $maternal = $appointment->getMaternal();
            $child = $appointment->getChild();
            $resource[] = [
                "id" => $appointment->id,
                "slot_date" => $slot->slot_date,
                "start_time" => Calculator::formatTimeToAmPm($slot->start_time),
                "end_time" => Calculator::formatTimeToAmPm($slot->end_time),
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

     public function removeInvalidWeekdays()
    {
        $clinicWeekdays = ClinicWeeklyAvailability::query()
            ->where("active", "=", 1)
            ->get();

        $clinicWeekdaysArray = [];
        foreach ($clinicWeekdays as $clinicWeekday) {
            $clinicWeekdaysArray[] = (int)$clinicWeekday->weekday;
        }
        
        $doctorWeekdays = DoctorWeeklyAvailability::query()
            ->get();

        foreach ($doctorWeekdays as $doctorWeekday) {
            if (!in_array((int)$doctorWeekday->weekday, $clinicWeekdaysArray)) {
                $deleteWeekday = DoctorWeeklyAvailability::find($doctorWeekday->id);
                $deleteWeekday->delete();
            }
        }
        
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
                "start_time" => Calculator::formatTimeToAmPm($availability->start_time),
                "end_time" => Calculator::formatTimeToAmPm($availability->end_time),
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
            ->where("active", "=", true)
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