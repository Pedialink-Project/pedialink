<?php

namespace App\Controllers\Doctor;

use App\Models\Child;
use App\Models\DoctorWeeklyAvailability;
use App\Models\Maternal;
use App\Services\Doctor\AppointmentService;
use App\Services\NotificationService;
use Library\Framework\Http\Request;

class AppointmentController
{
    private AppointmentService $appointmentService;
    private NotificationService $notificationService;

    public function __construct()
    {
        $this->appointmentService = new AppointmentService();
        $this->notificationService = new NotificationService();
    }

    public function overview(Request $request)
    {
        $search = $request->query("search", "");
        $filters = $request->query("filters", []);
        [$appointments, $links] = $this->appointmentService
            ->getAppointmentOverviewData($search, $filters);
        return view("doctor/appointment", [
            "appointments" => $appointments,
            "links" => $links
        ]);
    }

    