<?php

namespace App\Services;
use App\Models\Appointment;



class AppointmentService
{

    public function getAppointmentDetails()
    {
        $appointments = Appointment::query()->get();

        $resource = [];

        foreach ($appointments as $appointment) {
            $resource[] = [
                "id" => $appointment->id,
                "patient_id" => $appointment->patient_id,
                "staff_id" => $appointment->staff_id,
                "requested_by" => $appointment->requested_by,
                "datetime" => $appointment->datetime,
                "location" => $appointment->location,
                "status" => $appointment->status,
                "purpose" => $appointment->purpose,
                "notes" => $appointment->notes,
                "cancled_by" => $appointment->cancled_by,
            ];
        }

        return $resource;
    }
    public function validateDate($date)
    {
        $error = null;

        if (!Validator::validateFieldExistence($date)) {
            $error = "Appointment Date cannot be empty";
            return $error;
        }


        $appointmentDate = new \DateTime($date);

         if (!$appointmentDate) {
        $error = "Invalid time format (expected HH:MM, 24-hour format)";
        return $error;
    }
        $currentDate = new \DateTime();

        if ($appointmentDate <= $currentDate) {

            $error = "Appointment date must be in the future";
            return $error;
        }


        $dayOfWeek = (int) $appointmentDate->format('N');

        if ($dayOfWeek < 1 || $dayOfWeek > 5) {
            $error = "Appointments are only available on weekdays (Monday to Friday)";
            return $error;
        }


        return $error;


    }

    public function validateTime($time)
    {
        $error = null;

        if (!Validator::validateFieldExistence($time)) {
            $error = "Appointment Time cannot be empty";
            return $error;
        }



        $appointmentTime = \DateTime::createFromFormat('H:i', $time);

        if (!$appointmentTime) {
            return "Invalid time format (expected HH:MM, 24-hour format)";
        }
        $startTime = \DateTime::createFromFormat('H:i', '09:00');
        $endTime = \DateTime::createFromFormat('H:i', '17:00');

        if ($appointmentTime < $startTime || $appointmentTime > $endTime) {
            $error = "Appointment Time must be within working hours (09:00 - 17:00)";
            return $error;
        }



        return $error;


    }
    
}

?>