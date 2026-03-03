<?php

namespace App\Controllers\Doctor;

use App\Models\DoctorWeeklyAvailability;
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
        $search = $request->query("search", "");
        $filter = $request->query("filters", []);

        $clinicWeeklyAvailability = $this->appointmentService
            ->getAppointmentConfigurationData($search, $filter);
        $availableWeekdays = $this->appointmentService->getAvailableWeekdays();
        $availableClinicWeekdays = $this->appointmentService->getAvailableClinicWeekdays();
        return view("doctor/appointment/configure", [
            "clinicWeeklyAvailability" => $clinicWeeklyAvailability,
            "availableWeekdays" => $availableWeekdays,
            "availableClinicWeekdays" => $availableClinicWeekdays,
        ]);
    }

    public function createAvailability(Request $request)
    {
        $data = [
            "weekday" => $request->input("weekday", ""),
            "start_time" => $request->input("start_time", ""),
            "end_time" => $request->input("end_time", ""),
        ];

        $errors = $this->appointmentService->validateAvailabilityData($data);

        if (count($errors) > 0) {
            return redirect(route("doctor.appointments.configure"))
                ->withErrors($errors)
                ->withInput($data)
                ->with('add', true);
        }

        $doctorWeeklyAvailability = new DoctorWeeklyAvailability();
        $doctorWeeklyAvailability->doctor_id = auth()->user()->id;
        $doctorWeeklyAvailability->weekday = $data['weekday'];
        $doctorWeeklyAvailability->start_time = $data['start_time'];
        $doctorWeeklyAvailability->end_time = $data['end_time'];
        $doctorWeeklyAvailability->save();
        
        return redirect(route("doctor.appointments.configure"))
            ->withMessage("Availability created successfully.", "Success", "success");
    }

    public function editAvailability(Request $request, int $id)
    {
        $data = [
            "e_start_time" => $request->input("e_start_time", ""),
            "e_end_time" => $request->input("e_end_time", ""),
        ];
        
        $errors = $this->appointmentService->validateAvailabilityData($data, true);

        if (count($errors) > 0) {
            return redirect(route("doctor.appointments.configure"))
                ->withErrors($errors)
                ->withInput($data)
                ->with('edit', $id);
        }

        $doctorWeeklyAvailability = DoctorWeeklyAvailability::find($id);
        $doctorWeeklyAvailability->start_time = $data['e_start_time'];
        $doctorWeeklyAvailability->end_time = $data['e_end_time'];
        $doctorWeeklyAvailability->save();
        return redirect(route("doctor.appointments.configure"))
            ->withMessage("Availability updated successfully.", "Success", "success");
    }
}