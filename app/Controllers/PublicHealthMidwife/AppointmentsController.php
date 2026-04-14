<?php

namespace App\Controllers\PublicHealthMidwife;

use App\Models\Appointment;
use App\Models\Child;
use App\Models\Maternal;
use App\Services\AppointmentSchedulerService;
use App\Services\PublicHealthMidwife\AppointmentService;
use Library\Framework\Http\Request;

class AppointmentsController
{
    private AppointmentService $appointmentService;
    private AppointmentSchedulerService $appointmentSchedulerService;

    public function __construct()
    {
        $this->appointmentService = new AppointmentService();
        $this->appointmentSchedulerService = new AppointmentSchedulerService();
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

    public function viewHistory(Request $request, int $id, string $type)
    {
        $search = $request->query("search", "");
        $filters = $request->query("filters", []);
        [$appointments, $links] = $this->appointmentService
            ->getAppointmentData($search, $filters, true, [
                'type' => $type,
                'id' => $id
            ]);

        return view("phm/appointments", [
            "appointments" => $appointments,
            "links" => $links,
            "history" => [
                "status" => true,
                "id" => $id,
                "name" => $type === 'child' ? 
                    Child::find($id)->name : 
                    Maternal::find($id)->getUser()->name,
                "type" => $type
            ],
        ]);
    }

    public function attendAppointment(Request $request, int $id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return redirect(route('phm.appointments'))
                ->withMessage(
                    'Appointment not found',
                    'Error',
                    'error'
                );
        }

        // add function to prevent marking as attended if the current date is before the appointment date
        $slot = $appointment->getSlot();
        $appointmentDateTime = new \DateTime($slot->slot_date . ' ' . $slot->start_time);
        $now = new \DateTime();
        if ($now < $appointmentDateTime) {
            return redirect(route('phm.appointments'))
                ->withMessage(  
                    'Cannot mark appointment as attended before the appointment date and time',
                    'Error',
                    'error'
                );
        }

        if ($appointment->status === 'attended') {
            return redirect(route('phm.appointments'))
                ->withMessage(
                    'Appointment is already marked as attended',
                    'Info',
                    'info'
                );
        }

        $appointment->status = 'attended';
        $appointment->attended_at = new \DateTime();
        $appointment->save();

        return redirect(route('phm.appointments'))
            ->withMessage(
                'Appointment marked as attended successfully',
                'Success',
                'success'
            );
    }

    public function cancelAppointment(Request $request, int $id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return redirect(route('phm.appointments'))
                ->withMessage(
                    'Appointment not found',
                    'Error',
                    'error'
                );
        }

        if ($appointment->status === 'cancelled') {
            return redirect(route('phm.appointments'))
                ->withMessage(
                    'Appointment is already cancelled',
                    'Info',
                    'info'
                );
        }

        $appointment->status = 'cancelled';
        $defaultReason = 'Cancelled by PHM';
        if (auth()->check()) {
            $defaultReason .= ' (' . auth()->user()->name . ')';
        }
        $appointment->reason = $request->input('reason', $defaultReason);
        $appointment->save();

        $this->appointmentSchedulerService->onAppointmentCancelled((int)$appointment->id);

        return redirect(route('phm.appointments'))
            ->withMessage(
                'Appointment cancelled successfully',
                'Success',
                'success'
            );
    }
}