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

    public function getChildrenCount()
    {
        $children = ParentChild::query()->where("parent_id",'=', auth()->user()->id)->get();

        return count($children);
    }

    public function getAppointmentCount()
    {
        $childIds = ParentChild::query()->where("parent_id", '=', auth()->user()->id)->pluck("child_id");
        $childAppointments = Appointment::query()->whereIn('child_id', $childIds)->get();
        $maternalId  = Maternal::query()->where("parent_id", "=", auth()->user()->id)->pluck("id");
        $maternalappointments = Appointment::query()
            ->whereIn("maternal_id", $maternalId)
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

    public function getLatestChildAppointmentsByParentId()
{
    $parentId = auth()->user()->id;
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

 public function getLatestMaternalAppointmentsByParentId()
{
    $parentId = auth()->user()->id;
    $maternalId = Maternal::query()
        ->where("parent_id", "=", $parentId)
        ->pluck("id");

    

       $appointments = Appointment::query()->whereIn('maternal_id', $maternalId)
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

        $resource[] = [
            "slot_date" => $slot->slot_date,
            "start_time" => Calculator::formatTimeToAmPm($slot->start_time),
            "end_time" => Calculator::formatTimeToAmPm($slot->end_time),

            "doctor" => $doctor ? [
                "id" => $doctor->id,
                "name" => $doctor->getUser()->name
            ] : null,

            "maternal" => $maternal ? [
                "id" => $maternal->id,
                "name" => $maternal->name
            ] : null,

            
            "status" => $appointment->status
        ];
    }

    return $resource;
}
   
}