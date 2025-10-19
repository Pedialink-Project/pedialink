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
        $purpose =$request->input("purpose");
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

        $this->appointmentService->createAppointment($patient,$staff,$date,$time,$purpose,$notes,$requester = "parent");

        return redirect(route("parent.appointments"))
            ->withMessage("success");
    }



   
 
}