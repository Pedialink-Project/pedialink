<?php

namespace App\Services;
use App\Models\Appointment;
use App\Models\Staff;
use App\Models\Patient;
use App\Models\ParentM;



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

    private function validateStaff(string $staff)
    {
        $error = null;
        if (!Validator::validateFieldExistence($staff)) {
            $error = "Staff Preference cannot be empty";
            return $error;
        }

        $staffTypes = Staff::all();
        $validType = false;

        foreach ($staffTypes as $staffType) {
            if (strtolower($$staff) === strtolower($staffType->type)) {
                $validType = true;
                break;
            }
        }

        if (!$validType) {
            $error = "Invalid Staff type";
            return $error;
        }

        return $error;
    }

    private function validatePatient(string $patient)
    {
        $error = null;
        if (!Validator::validateFieldExistence($patient)) {
            $error = "Patient cannot be empty";
            return $error;
        }

        $patients = Patient::all();
        $validPatient = false;

        foreach ($patients as $p) {
            if (strtolower($patient) === strtolower($p->name)) {
                $validPatient = true;
                break;
            }
        }

        if (!$validPatient) {
            $error = "Invalid Patient";
            return $error;
        }

        return $error;
    }

    public function validateAppointment($patient,$staff,$date, $time)
    {

        $errors = [];

        $dateError = $this->validateDate($date);
        if ($dateError) {
            $errors["date"] = $dateError;
        }

        $timeError = $this->validateTime($time);
        if ($timeError) {
            $errors["time"] = $timeError;
        }

        $staffError = $this->validateStaff($staff);
        if ($staffError) {
            $errors["staff"] = $staffError;
        }

        $patientError = $this->validatePatient($patient);
        if ($patientError) {
            $errors["patient"] = $patientError;
        }

        return $errors;
    }

    public function createAppointment($patient, $staff, $time, $date, $purpose, $notes)
    {

        $parent_id = ParentM::query()->where("name", "=", $patient)->first()->id;
        $patient_id = Patient::query()->where("parent_id", "=", $parent_id)->first()->id;

        $staff_id = Staff::query()->where("name","=", $staff)->first()->id;

        $appointment = new Appointment();
        $appointment->patient_id = $patient_id;
        $appointment->staff_id = $staff_id;
        $appointment->time = $time;
        $appointment->date = $date;
        $appointment->purpose = $purpose;
        $appointment->notes = $notes;
        $appointment->save();




    }
}


?>