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
        $filters = $request->input('filters');
        [$events, $links] = $this->eventService->getAllEvents($search, $filters);

        return view('admin/event', ['events' => $events, 'links' => $links]);
    }

    public function createEvent($request)
    {
        $title = $request->input('title');
        $description = $request->input('description');
        $date = $request->input('date');
        $time = $request->input('time');
        $location = $request->input('location');
        $maxCount = $request->input('max_count');
        $purpose = $request->input('purpose');
        $notes = $request->input('notes');

        $errors = $this->eventService->validateCreateEventData($title, $description, $date, $time, $location, $maxCount);

        if (count($errors) !== 0) {
            return redirect(route("admin.event"))
                ->withInput([
                    "title" => $title,
                    "description" => $description,
                    "date" => $date,
                    "time" => $time,
                    "location" => $location,
                    "max_count" => $maxCount,
                    "purpose" => $purpose,
                    "notes" => $notes,
                ])
                ->withErrors($errors)
                ->with("create", true);
        }

        $this->eventService->createEvent($title, $description, $date, $time, $location, $maxCount, $purpose, $notes);

        return redirect(route('admin.event'))->withMessage('success', 'Event created successfully.', 'success');
    }

    public function editEvent($request, $id)
    {
        $title = $request->input('e_title');
        $date = $request->input('e_date');
        $time = $request->input('e_time');
        $location = $request->input('e_location');
        $maxCount = $request->input('e_max_count');

        $errors = $this->eventService->validateEditEventData($title, $date, $time, $location, $maxCount);

        if (count($errors) !== 0) {
            return redirect(route("admin.event"))
                ->withInput([
                    "e_title" => $title,
                    "e_date" => $date,
                    "e_time" => $time,
                    "e_location" => $location,
                    "e_max_count" => $maxCount,
                ])
                ->withErrors($errors)
                ->with("edit", $id);
        }

        $this->eventService->editEvent($id, $title, $date, $time, $location, $maxCount);

        return redirect(route('admin.event'))->withMessage('success', 'Event updated successfully.', 'success');
    }

    public function editEventVisible($requset, $id)
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

