<?php

namespace App\Controllers\PublicHealthMidwife;

use App\Services\PublicHealthMidwife\AppointmentService;
use Library\Framework\Http\Request;

class AppointmentsController
{
    private AppointmentService $appointmentService;

    public function __construct()
    {
        $this->appointmentService = new AppointmentService();
    }

    public function index(Request $request)
    {
        $search = $request->query("search", "");
        $filters = $request->query("filters", []);
        [$appointments, $links] = $this->appointmentService
            ->getAppointmentData($search, $filters);

        return view("phm/appointments", [
            "appointments" => $appointments,
            "links" => $links
        ]);
    }
}