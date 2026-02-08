<?php

namespace App\Controllers\Admin;

use App\Services\EventService;


class EventController
{
    private $eventService;

    public function __construct()
    {
        $this->eventService = new EventService();
    }
    public function index($request)
    {
        $search = $request->input('search');
        [$events, $links] = $this->eventService->getAllEvents($search);

        return view('admin/events/event', ['events' => $events, 'links' => $links]);
    }

    public function participantsDetails($request, $id)
    {
        [$participants, $links] = $this->eventService->getParticipantsByEventId($id);

        return view('admin/events/participants', ['participants' => $participants, 'id' => $id]);
    }

    public function createEvent($request)
    {
        $title = $request->input('title');
        $description = $request->input('description');
        $date = $request->input('date');
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');
        $location = $request->input('location');
        $maxCount = $request->input('max_count');
        $purpose = $request->input('purpose');
        $notes = $request->input('notes');

        $errors = $this->eventService->validateCreateEventData($title, $description, $date, $startTime, $endTime, $location, $maxCount);

        if (count($errors) !== 0) {
            return redirect(route("admin.event"))
                ->withInput([
                    "title" => $title,
                    "description" => $description,
                    "date" => $date,
                    "start_time" => $startTime,
                    "end_time" => $endTime,
                    "location" => $location,
                    "max_count" => $maxCount,
                    "purpose" => $purpose,
                    "notes" => $notes,
                ])
                ->withErrors($errors)
                ->with("create", true);
        }

        $this->eventService->createEvent($title, $description, $date, $startTime, $endTime, $location, $maxCount, $purpose, $notes);

        return redirect(route('admin.event'))->withMessage('success', 'Event created successfully.', 'success');
    }

    public function editEvent($request, $id)
    {
        $title = $request->input('e_title');
        $date = $request->input('e_date');
        $start_time = $request->input('e_start_time');
        $end_time = $request->input('e_end_time');
        $location = $request->input('e_location');
        $maxCount = $request->input('e_max_count');

        $errors = $this->eventService->validateEditEventData($id, $title, $date, $start_time, $end_time, $location, $maxCount);

        if (count($errors) !== 0) {
            return redirect(route("admin.event"))
                ->withInput([
                    "e_title" => $title,
                    "e_date" => $date,
                    "e_start_time" => $start_time,
                    "e_end_time" => $end_time,
                    "e_location" => $location,
                    "e_max_count" => $maxCount,
                ])
                ->withErrors($errors)
                ->with("edit", $id);
        }

        $error = $this->eventService->editEvent($id, $title, $date, $start_time, $end_time, $location, $maxCount);

        if ($error !== NULL) {
            return redirect(route("admin.event"))
                ->withMessage(
                    $error,
                    "Failed",
                    "error",
                );
        }


        return redirect(route('admin.event'))->withMessage('success', 'Event updated successfully.', 'success');
    }

    public function editEventVisible($request, $id)
    {
        $error = $this->eventService->validateEditEventVisible($id);

        if ($error !== NULL) {
            return redirect(route("admin.event"))
                ->with("edit-visible", false)
                ->withMessage(
                    $error,
                    "Failed",
                    "error",
                );
        }

        $this->eventService->editEventVisible($id);

        return redirect(route("admin.event"))
            ->with("edit-visible", true)
            ->withMessage(
                "Event with ID: E-$id 's visibility was successfully changed",
                "Visibility Changed",
                "success",
            );
    }

    public function cancelEvent($request, $id)
    {

       

        $error = $this->eventService->cancelEvent($id);

        if ($error) {
            return redirect(route("admin.event"))
                ->withMessage(
                    $error,
                    "Event Not Cancelled",
                    "error"
                );
        }

        return redirect(route("admin.event"))
            ->withMessage(
                "Event was successfully cancelled",
                "Event Cancelled",
                "success"
            );
    }


    public function deleteEvent($request, $id)
    {
        $error = $this->eventService->validateDeleteEvent($id);

        if ($error !== NULL) {
            return redirect(route("admin.event"))
                ->with("delete", false)
                ->withMessage(
                    $error,
                    "Failed",
                    "error",
                );
        }

        $this->eventService->deleteEvent($id);

        return redirect(route("admin.event"))
            ->with("delete", true)
            ->withMessage(
                "Event with ID: E-$id was successfully deleted",
                "Deleted Successfully",
                "success",
            );
    }
}
