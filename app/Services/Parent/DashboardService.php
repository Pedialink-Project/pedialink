<?php

namespace App\Services\Parent;

use App\Models\Appointment;
use App\Models\ChildAccessRequest;
use App\Models\ChildMisc;
use App\Models\Doctor;
use App\Models\Maternal;
use App\Models\Events;
use App\Models\ParentChild;
use App\Models\ParentM;
use App\Models\PublicHealthMidwife;
use App\Services\EventService;
use App\Helpers\Calculator;
use Library\Framework\Database\QueryBuilder;

class DashboardService
{
    private EventService $eventService;

    public function __construct()
    {
        $this->eventService = new EventService();
    }

    public function getTotalChildrenCount()
    {
        $children = ParentChild::query()->where("parent_id",'=', auth()->user()->id)->get();

        return count($children);
    }

    public function getAppointmentCount()
    {
        $childIds = ParentChild::query()->where("parent_id", '=', auth()->user()->id)->pluck("child_id");
        $childAppointments = Appointment::query()->whereIn('child_id', $childIds)->get();
        $maternalId  = Maternal::query()->where("parent_id", "=", auth()->user()->id)->get();
        $maternalappointments = Appointment::query()
            ->where("maternal_id", "=", $maternalId)
            ->get();

        return count($childAppointments) + count($maternalappointments);
    }

   
  

    public function getEventsData()
    {
        $events = Events::query()
            ->where("visible", "=", 1)
            ->limit(3)
            ->get();

        $resource = [];
        foreach ($events as $event) {
            
            $resource[] = [
                "id" => $event->id,
                "title" => $event->title,
                "description" => $event->description,
                "event_date" => $event->event_date,
                "start_time" => $event->start_time,
                "participants_count" => $event->participants_count,
                "event_location" => $event->event_location,
                "event_status" => $this->eventService->getEventStatus($event->id)
            ];
        }

        return $resource;
    }

    public function getLatestChildAppointmentsByParentId($parentId)
{
    $childIds = ParentChild::query()
        ->where("parent_id", "=", $parentId)
        ->pluck("child_id");

    

       $appointments = Appointment::query()->whereIn('child_id', $childIds)
       ->join("appointment_slots as s", "s.id", "=", "appointments.slot_id")
        ->orderBy("s.slot_date", "DESC")
        ->orderBy("s.start_time", "DESC")
        ->limit(3)
        ->get();

    $resource = [];

    foreach ($appointments as $appointment) {

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
                "name" => $child->name
            ] : null,

            
            "status" => $appointment->status
        ];
    }

    return $resource;
}
   
    public function getTodaysAppointments()
    {
        $today = (new \DateTimeImmutable('today'))->format('Y-m-d');

        // Get top 3 appointments for today with child, slot and doctor info
        $sql = "
        SELECT 
            a.id,
            a.reason,
            a.status,
            s.start_time,
            s.end_time,
            s.doctor_id,
            c.name AS child_name,
            u.name AS doctor_name
        FROM appointments a
        JOIN appointment_slots s ON a.slot_id = s.id
        LEFT JOIN children c ON a.child_id = c.id
        LEFT JOIN doctors d ON s.doctor_id = d.id
        LEFT JOIN users u ON d.id = u.id
        WHERE s.slot_date = :today
        ORDER BY s.start_time ASC
        LIMIT 3
        ";

        $rows = QueryBuilder::rawGet($sql, [':today' => $today]);

        $resource = [];
        foreach ($rows as $r) {
            // Format time to 12-hour format (e.g., "10:00 AM")
            $startTime = $r['start_time'] ?? null;
            $formattedTime = 'N/A';
            if ($startTime) {
                $timeObj = \DateTime::createFromFormat('H:i:s', $startTime);
                if ($timeObj) {
                    $formattedTime = $timeObj->format('g:i A');
                }
            }

            // Map status to display label
            $statusMap = [
                'confirmed' => 'Scheduled',
                'pending' => 'Pending',
                'attended' => 'Finished',
                'cancelled' => 'Cancelled',
                'no-show' => 'No Show',
            ];
            $status = $r['status'] ?? 'pending';
            $displayStatus = $statusMap[$status] ?? ucfirst($status);

            $resource[] = [
                'id' => $r['id'],
                'child_name' => $r['child_name'] ?? 'Unknown',
                'reason' => $r['reason'] ?? 'Routine Checkup',
                'doctor_name' => $r['doctor_name'] ?? 'N/A',
                'time' => $formattedTime,
                'status' => $displayStatus,
            ];
        }

        return $resource;
    }
}