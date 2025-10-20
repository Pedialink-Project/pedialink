<?php

namespace App\Services;
use App\Models\Appointment;
use App\Models\Staff;
use App\Models\Patient;
use App\Models\ParentM;
use App\Models\User;
use App\Models\Maternal;
use App\Models\Child;



class AppointmentService
{

    public function getAllAppointmentDetails()
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

    public function getParentAppointmentDetails($parentId)
    {
        $maternalIds = Maternal::query()->where('parent_id', '=', $parentId)->pluck('id');
        $childIds = Child::query()->where('parent_id', '=', $parentId)->pluck('id');


        $patientIds = array_merge($maternalIds, $childIds);

        $appointments = Appointment::query()->whereIn('patient_id', $patientIds)->get();



        $resource = [];
        foreach ($appointments as $appointment) {

            $parentName = User::query()->where('id', '=', $parentId)->first()->name;
            $maternal = Maternal::query()->where('id', '=', $appointment->patient_id)->first();
            $child = Child::query()->where('id', '=', $appointment->patient_id)->first();

            $patientName = null;

            if ($maternal) {
                $patientName = $parentName;
            } elseif ($child) {
                $patientName = trim(($child->first_name ?? '') . ' ' . ($child->last_name ?? ''));
            } else {
                $patientName = 'Unknown Patient';
            }

            $staffName = User::query()->where('id', '=', $appointment->staff_id)->first()->name;

            $time = date('H:i', strtotime($appointment->datetime));
            $date = date('Y-m-d', strtotime($appointment->datetime));

            $resource[] = [
                "id" => $appointment->id,
                "patient_id" => $appointment->patient_id,
                "staff_id" => $appointment->staff_id,
                "patient_name" => $patientName,
                "staff_name" => $staffName,
                "requested_by" => $appointment->requested_by,
                "date" => $date,
                "time" => $time,
                "datetime" => $appointment->datetime,
                "location" => $appointment->location ?? "Not Allocated",
                "status" => $appointment->status,
                "purpose" => $appointment->purpose,
                "notes" => json_decode($appointment->notes),
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


        $appointmentDate = \DateTime::createFromFormat('Y-m-d', $date);
        $errors = \DateTime::getLastErrors();
        if (!$appointmentDate || $errors['warning_count'] > 0 || $errors['error_count'] > 0) {
            return "Invalid date format. Expected YYYY-MM-DD.";
        }
        $currentDate = new \DateTime();

        if ($appointmentDate < $currentDate) {

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
        $errors = \DateTime::getLastErrors();
        if (!$appointmentTime || $errors['warning_count'] > 0 || $errors['error_count'] > 0) {
            return "Invalid time format. Expected HH:MM, 24-hour format.";
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

        // $staffs = Staff::all();
        // $validType = false;

        // foreach ($staffs as $staff) {
        //     if ($staff === $staff->id) {
        //         $validType = true;
        //         break;
        //     }
        // }

        // if (!$validType) {
        //     $error = "Invalid Staff type";
        //     return $error;
        // }

        return $error;
    }

    private function validatePatient(string $patient)
    {
        $error = null;
        if (!Validator::validateFieldExistence($patient)) {
            $error = "Patient cannot be empty";
            return $error;
        }

        // $patients = Patient::all();
        // $validPatient = false;

        // foreach ($patients as $p) {
        //     if ($patient === $p->parent_id) {
        //         $validPatient = true;
        //         break;
        //     }
        // }

        // if (!$validPatient) {
        //     $error = "Invalid Patient";
        //     return $error;
        // }

        return $error;
    }

    public function validateAppointment($patient, $staff, $date, $time)
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

    public function validateRescheduleAppointment($date, $time)
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



        return $errors;
    }
    private function formatNotes(string $notes)
    {
        // Split the string by new lines (\r\n, \r, or \n)
        $lines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $notes)));

        $notesArray = array_map(function ($line) {
            return ['note' => $line];
        }, $lines);

        return json_encode($notesArray, JSON_UNESCAPED_UNICODE);
    }

    public function createAppointment($patient, $staff, $date, $time, $location, $purpose, $notes)
    {

        $appointmentDateTime = $date . ' ' . $time;

        $appointment = new Appointment();
        $appointment->patient_id = $patient;
        $appointment->staff_id = $staff;
        $appointment->location = $location;
        $appointment->status = "confirmed";
        $appointment->requested_by = "staff";
        $appointment->datetime = $appointmentDateTime;
        $appointment->purpose = $purpose;
        $appointment->notes = $this->formatNotes($notes);
        $appointment->save();

    }

    public function requestAppointment($patient, $staff, $date, $time, $purpose, $notes)
    {

        $appointmentDateTime = $date . ' ' . $time;

        $appointment = new Appointment();
        $appointment->patient_id = $patient;
        $appointment->staff_id = $staff;
        $appointment->requested_by = "parent";
        $appointment->datetime = $appointmentDateTime;
        $appointment->purpose = $purpose;
        $appointment->notes = $this->formatNotes($notes);
        $appointment->save();

    }


    public function getAppointmentById($appointmentId)
    {
        return Appointment::query()->where('id', '=', $appointmentId)->first();
    }

    public function requestRescheduleAppointment($appointmentId, $date, $time, $reason, $notes)
    {
        $appointment = Appointment::find($appointmentId);

        $appointmentDateTime = $date . ' ' . $time;
        $appointment->datetime = $appointmentDateTime;
        $appointment->status = 'reschedule_requested';
        $appointment->reschedule_reason = $reason;
        $appointment->notes = $this->formatNotes($notes);
        $appointment->save();

    }



    public function rescheduleAppointment($appointmentId, $date, $time, $reason, $notes)
    {
        $appointment = Appointment::find($appointmentId);

        $appointmentDateTime = $date . ' ' . $time;
        $appointment->datetime = $appointmentDateTime;
        $appointment->status = 'rescheduled';
        $appointment->reschedule_reason = $reason;
        $appointment->notes = $this->formatNotes($notes);
        $appointment->save();

    }





    public function requestCancelAppointment($appointmentId, $reason, $notes)
    {
        $appointment = Appointment::find($appointmentId);
        $appointment->status = 'cancel_requested';
        $appointment->cancel_reason = $reason;
        $appointment->notes = $this->formatNotes($notes);
        $appointment->save();

    }


    public function cancelAppointment($appointmentId)
    {
        $appointment = Appointment::find($appointmentId);
        $appointment->status = 'cancelled';
        $appointment->save();

    }



    public function completeAppointment($appointmentId)
    {
        $appointment = Appointment::find($appointmentId);
        if ($appointment) {
            $appointment->status = 'completed';
            $appointment->save();
        }
    }

    public function deleteAppointment($appointmentId)
    {
        $appointment = Appointment::find($appointmentId);
        if ($appointment) {
            $appointment->delete();
        }
    }
}

?>