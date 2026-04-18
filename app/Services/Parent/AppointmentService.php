<?php

namespace App\Services\Parent;

use App\Helpers\Calculator;
use App\Models\Appointment;
use App\Models\Maternal;
use App\Models\ParentChild;
use App\Services\AppointmentSchedulerService;
use App\Services\NotificationService;
use App\Rules\TextRule;

class AppointmentService
{
    use TextRule;

    private AppointmentSchedulerService $appointmentSchedulerService;
    private NotificationService $notificationService;

    public function __construct()
    {
        $this->appointmentSchedulerService = new AppointmentSchedulerService();
        $this->notificationService = new NotificationService();
    }

    public function getChildAppointmentByParentId($parentId, string $search, array $filters = [])
    {

        $childIds = ParentChild::query()->where("parent_id", '=', $parentId)->pluck("child_id");
        $appointments = Appointment::query()->whereIn('child_id', $childIds);

        if (isset($filters['status'])) {
            $appointments = $appointments
                ->whereIn("appointments.status", $filters['status']);
        }

        $appointments = $appointments
            ->join("appointment_slots as s", "s.id", "=", "appointments.slot_id")
            ->orderBy("s.slot_date", "DESC")
            ->paginate(10)
            ->toArray();

        $resource = [];
        foreach ($appointments['items'] as $appointment) {
            $slot = $appointment->getSlot();
            $doctor = $slot->getDoctor();
            $maternal = $appointment->getMaternal();
            $child = $appointment->getChild();
            $resource[] = [
                "id" => $appointment->id,
                "slot_date" => $slot->slot_date,
                "start_time" => Calculator::formatTimeToAmPm($slot->start_time),
                "end_time" => Calculator::formatTimeToAmPm($slot->end_time),
                "doctor" => $doctor ? [
                    "id" => $doctor->id,
                    "name" => $doctor->getUser()->name
                ] : null,
                "child" => $child ? [
                    "id" => $child->id,
                    "name" => $child->name,
                ] : null,
                "maternal" => $maternal ? [
                    "id" => $maternal->id,
                    "name" => $maternal->getUser()->name,
                ] : null,
                "reason" => $appointment->reason,
                "notes" => $appointment->notes,
                "status" => $appointment->status
            ];
        }

        $links = array_diff_key($appointments, ['items' => true]);

        return [
            $resource,
            $links
        ];
    }

    public function getParentAppointmentByParentId($parentId, string $search, array $filters = [])
    {
        $maternalIds = Maternal::query()->where("parent_id", '=', $parentId)->pluck("id");
        $appointments = Appointment::query()->whereIn('maternal_id', $maternalIds);

        if (isset($filters['status'])) {
            $appointments = $appointments
                ->whereIn("appointments.status", $filters['status']);
        }

        $appointments = $appointments
            ->join("appointment_slots as s", "s.id", "=", "appointments.slot_id")
            ->orderBy("s.slot_date", "DESC")
            ->paginate(10)
            ->toArray();

        $resource = [];
        foreach ($appointments['items'] as $appointment) {
            $slot = $appointment->getSlot();
            $doctor = $slot->getDoctor();
            $maternal = $appointment->getMaternal();
            $child = $appointment->getChild();
            $resource[] = [
                "id" => $appointment->id,
                "slot_date" => $slot->slot_date,
                "start_time" => Calculator::formatTimeToAmPm($slot->start_time),
                "end_time" => Calculator::formatTimeToAmPm($slot->end_time),
                "doctor" => $doctor ? [
                    "id" => $doctor->id,
                    "name" => $doctor->getUser()->name
                ] : null,
                "child" => $child ? [
                    "id" => $child->id,
                    "name" => $child->name,
                ] : null,
                "maternal" => $maternal ? [
                    "id" => $maternal->id,
                    "name" => $maternal->getUser()->name,
                ] : null,
                "reason" => $appointment->reason,
                "notes" => $appointment->notes,
                "status" => $appointment->status
            ];
        }

        $links = array_diff_key($appointments, ['items' => true]);

        return [
            $resource,
            $links
        ];
    }


    public function validateAppointmentCancel($reason)
    {
        $errors = [];

        $reasonError = $this->validateText($reason, "Cancel Reason");
        if ($reasonError) {
            $errors['reason'] = $reasonError;
        }

        return $errors;
    }


    public function cancelAppointment($appointmentId, $reason)
    {

        $appointment = Appointment::find($appointmentId);
        if (!$appointment) {
            return "Appointment not found";
        }

        if ($appointment->status === 'cancelled') {
            return "Appointment is already cancelled";
        }

        $appointment->status = "cancelled";
        $appointment->reason = $reason;
        $appointment->save();

        $this->appointmentSchedulerService->onAppointmentCancelled((int)$appointmentId);

        $slot = $appointment->getSlot();
        $doctor = $slot ? $slot->getDoctor() : null;

        if ($slot && $doctor) {
            $patientName = null;

            if ($appointment->child_id) {
                $child = $appointment->getChild();
                $patientName = $child ? $child->name : null;
            } elseif ($appointment->maternal_id) {
                $maternal = $appointment->getMaternal();
                $patientName = $maternal && $maternal->getUser() ? $maternal->getUser()->name : null;
            }

            $patientPart = $patientName ? " for {$patientName}" : "";
            $message = "An appointment{$patientPart} on {$slot->slot_date} from "
                . Calculator::formatTimeToAmPm($slot->start_time)
                . " to " . Calculator::formatTimeToAmPm($slot->end_time)
                . " was cancelled. Reason: {$reason}";

            $this->notificationService->notify(
                (int)$doctor->id,
                "Appointment cancelled",
                $message,
                "appointment",
                (int)$appointment->id
            );

            $this->notificationService->notifyAdmins(
                "Appointment cancelled",
                "An appointment{$patientPart} on {$slot->slot_date} was cancelled by a parent.",
                "appointment",
                (int)$appointment->id
            );
        }


        return null;
    }
}
