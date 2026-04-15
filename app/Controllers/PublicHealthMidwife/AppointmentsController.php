<?php

namespace App\Controllers\PublicHealthMidwife;

use App\Models\Appointment;
use App\Models\Child;
use App\Models\Maternal;
use App\Services\AppointmentSchedulerService;
use App\Services\NotificationService;
use App\Services\PublicHealthMidwife\AppointmentService;
use Library\Framework\Http\Request;

class AppointmentsController
{
    private AppointmentService $appointmentService;
    private AppointmentSchedulerService $appointmentSchedulerService;
    private NotificationService $notificationService;

    public function __construct()
    {
        $this->appointmentService = new AppointmentService();
        $this->appointmentSchedulerService = new AppointmentSchedulerService();
        $this->notificationService = new NotificationService();
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

        $slot = $appointment->getSlot();
        $doctor = $slot ? $slot->getDoctor() : null;

        if ($slot && $doctor) {
            $patientName = null;
            $parentRecipientIds = [];

            if ($appointment->child_id) {
                $child = $appointment->getChild();
                if ($child) {
                    $patientName = $child->name;
                    $parents = $child->getParents();
                    if ($parents) {
                        foreach ($parents as $parent) {
                            $user = $parent->getUser();
                            if ($user) {
                                $parentRecipientIds[] = (int)$user->id;
                            }
                        }
                    }
                }
            } elseif ($appointment->maternal_id) {
                $maternal = $appointment->getMaternal();
                if ($maternal && $maternal->getUser()) {
                    $patientName = $maternal->getUser()->name;
                    $parentRecipientIds[] = (int)$maternal->getUser()->id;
                }
            }

            $patientPart = $patientName ? " for {$patientName}" : "";

            $doctorMessage = "An appointment{$patientPart} on {$slot->slot_date} was cancelled by PHM.";
            $this->notificationService->notify(
                (int)$doctor->id,
                "Appointment cancelled",
                $doctorMessage,
                "appointment",
                (int)$appointment->id
            );

            if (!empty($parentRecipientIds)) {
                $phmName = auth()->check() ? auth()->user()->name : 'PHM';
                $parentMessage = "Your appointment{$patientPart} on {$slot->slot_date} was cancelled by {$phmName}.";
                $this->notificationService->notifyMany(
                    $parentRecipientIds,
                    "Appointment cancelled",
                    $parentMessage,
                    "appointment",
                    (int)$appointment->id
                );
            }
        }

        return redirect(route('phm.appointments'))
            ->withMessage(
                'Appointment cancelled successfully',
                'Success',
                'success'
            );
    }
}