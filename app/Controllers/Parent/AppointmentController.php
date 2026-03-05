<?php

namespace App\Controllers\Parent;

use App\Services\Parent\AppointmentService;
use Library\Framework\Http\Request;

class AppointmentController
{

    private $appointmentService;

    public function __construct()
    {
        $this->appointmentService = new AppointmentService();
    }
    public function myAppointments(Request $request)
    {
        $search = $request->query("search", "");
        $filters = $request->query("filters", []);
        $parentId = auth()->user()->id;


        [$appointments, $links] = $this->appointmentService->getParentAppointmentByParentId($parentId, $search, $filters);
        return view("parent/my-appointments", [
            "appointments" => $appointments,
            "links" => $links
        ]);
    }

    public function childAppointments(Request $request)
    {
        $search = $request->query("search", "");
        $filters = $request->query("filters", []);
        $parentId = auth()->user()->id;


        [$appointments, $links] = $this->appointmentService->getChildAppointmentByParentId($parentId, $search, $filters);
        return view("parent/child-appointments", [
            "appointments" => $appointments,
            "links" => $links
        ]);
    }

    public function cancelMyAppointment(Request $request, $id)
    {
        $reason = $request->input("reason");


        $errors = $this->appointmentService->validateAppointmentCancel($reason);

        if (count($errors) !== 0) {
            return redirect(route("parent.appointments.my"))
                ->withInput([
                    "reason" => $reason
                ])
                ->withErrors($errors)
                ->with("cancelAppointment", $id);
        }

        $error = $this->appointmentService->cancelAppointment($id, $reason);


        if ($error) {
            return redirect(route("parent.appointments.my"))
                ->withMessage($error, "Error", "error");
        }

        return redirect(route("parent.appointments.my"))
            ->withMessage("Appointment cancelled successfully.", "Success", "success");
    }

    public function cancelChildAppointment(Request $request, $id)
    {
        $reason = $request->input("reason");
        $errors = $this->appointmentService->validateAppointmentCancel($reason);

        if (count($errors) !== 0) {
            return redirect(route("parent.appointments.child"))
                ->withInput([
                    "reason" => $reason
                ])
                ->withErrors($errors)
                ->with("cancelAppointment", $id);
        }

        $error = $this->appointmentService->cancelAppointment($id, $reason);

        if ($error) {
            return redirect(route("parent.appointments.child"))
                ->withMessage($error, "Error", "error");
        }

        return redirect(route("parent.appointments.child"))
            ->withMessage("Appointment cancelled successfully.", "Success", "success");
    }
}
