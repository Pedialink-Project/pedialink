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


        [$appointments, $links] = $this->appointmentService->getParentAppointmentByParentId($parentId,$search, $filters);
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
}
