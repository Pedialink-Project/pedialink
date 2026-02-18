<?php

namespace App\Controllers\Doctor;

use App\Services\Doctor\AppointmentService;
use Library\Framework\Http\Request;

class AppointmentController
{
    private AppointmentService $appointmentService;

    public function __construct()
    {
        $this->appointmentService = new AppointmentService();
    }

    public function overview(Request $request)
    {
        $search = $request->query("search", "");
        $filters = $request->query("filters", []);
        [$appointments, $links] = $this->appointmentService
            ->getAppointmentOverviewData($search, $filters);
        return view("doctor/appointment/overview", [
            "appointments" => $appointments,
            "links" => $links
        ]);
    }

    public function configure(Request $request)
    {
        return view("doctor/appointment/configure");
    }
}