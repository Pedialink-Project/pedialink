<?php

namespace App\Controllers\Parent;
use App\Services\AppointmentService;
use Library\Framework\Http\Request;

class AppointmentController
{

    private $appointmentService;

    public function __construct()
    {
        $this->appointmentService = new AppointmentService();
    }
    public function index()
    {
        $appointments = $this->appointmentService->getParentAppointmentDetails(auth()->id());


        return view("parent/appointments", ['appointments' => $appointments]);
    }

    public function requestAppointment(Request $request)
    {
        $patient = $request->input("patient");
        $staff = $request->input("staff");
        $time = $request->input("time");
        $date = $request->input("date");
        $purpose = $request->input("purpose");
        $notes = $request->input("notes");

        $errors = $this->appointmentService
            ->validateAppointment($patient, $staff, $date, $time);

        if (count($errors) !== 0) {
            return redirect(route("parent.appointments"))
                ->withInput([
                    "patient" => $patient,
                    "staff" => $staff,
                    "date" => $date,
                    "time" => $time
                ])
                ->withErrors($errors)
                ->with("create", true);
        }

        $this->appointmentService->requestAppointment($patient, $staff, $date, $time, $purpose, $notes);

        return redirect(route("parent.appointments"))
            ->withMessage(
                "Appointment request was successfully created",
                "Appointment Requested",
                "success",
            );
    }

    public function requestRescheduleAppointment(Request $request, $appointment)
    {

        $time = $request->input("time");
        $date = $request->input("date");
        $reason = $request->input("reason");
        $notes = $request->input("notes");

        $errors = $this->appointmentService
            ->validateRescheduleAppointment($date, $time);

        if (count($errors) !== 0) {
            return redirect(route("parent.appointments"))
                ->withInput([
                    "date" => $date,
                    "time" => $time
                ])
                ->withErrors($errors)
                ->with("reschedule", $appointment);
        }

        $this->appointmentService->requestRescheduleAppointment($appointment, $date, $time, $reason, $notes);

        return redirect(route("parent.appointments"))
            ->withMessage(
                "Appointment reschedule request was successfully requested",
                "Appointment reschedule requested",
                "success",
            );
    }

    public function requestCancelAppointment(Request $request, $appointment)
    {

        $reason = $request->input("reason");
        $notes = $request->input("notes");

        $this->appointmentService->requestCancelAppointment($appointment, $reason, $notes);

        return redirect(route("parent.appointments"))
            ->withMessage(
                "Appointment cancel request was successfully requested",
                "Appointment Cancel Requested",
                "error",
            )->with("cancel", $appointment);

    }







}