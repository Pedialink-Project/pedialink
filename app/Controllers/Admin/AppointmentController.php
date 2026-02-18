<?php

namespace App\Controllers\Admin;

use App\Models\ClinicWeeklyAvailability;
use App\Services\Admin\AppointmentService;
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
            
        return view("admin/appointment/overview", [
            "appointments" => $appointments,
            "links" => $links
        ]);
    }

    public function configure(Request $request)
    {
        $search = $request->query("search", "");
        $filters = $request->query("filters", []);

        $clinicWeeklyAvailability = $this->appointmentService
            ->getAppointmentConfigurationData($search, $filters);

        return view("admin/appointment/configure", [
            "clinicWeeklyAvailability" => $clinicWeeklyAvailability
        ]);
    }

    public function editAvailability(Request $request, int $id)
    {
        $data = [
            "e_start_time" => $request->input("e_start_time", ""),
            "e_end_time" => $request->input("e_end_time", ""),
            "e_slot_length_minutes" => $request->input("e_slot_length_minutes", 0)
        ];
        
        $errors = $this->appointmentService->validateAvailabilityData($data, true);

        if (count($errors) > 0) {
            return redirect(route("admin.appointment.configure"))
                ->withErrors($errors)
                ->withInput($data)
                ->with('edit', $id);
        }

        $clinicWeeklyAvailability = ClinicWeeklyAvailability::find($id);
        $clinicWeeklyAvailability->start_time = $data['e_start_time'];
        $clinicWeeklyAvailability->end_time = $data['e_end_time'];
        $clinicWeeklyAvailability->slot_length_minutes = $data['e_slot_length_minutes'];
        $clinicWeeklyAvailability->save();

        return redirect(route("admin.appointment.configure"))
            ->withMessage("Availability updated successfully.", "Success", "success");
    }

}