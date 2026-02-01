<?php

namespace App\Services;

use App\Models\Events;
use App\Models\EventRegistrations;
use App\Helpers\Validator;
use App\Rules\NumberRule;
use App\Rules\PhoneRule;
use App\Rules\NameRule;
use App\Rules\TextRule;
use App\Rules\DateRule;
use Library\Framework\Database\QueryBuilder;

class EventService
{

    use NameRule, PhoneRule, TextRule, DateRule, NumberRule;


    private function applySearch(QueryBuilder $events, string $search)
    {
        $events->where('title', 'ILIKE', "$search%");

        return $events;
    }



    function getEventStatus($eventId): string
    {

        $event = Events::query()->where('id', '=', $eventId)->first();


        if (!empty($event->is_cancelled)) {
            return 'cancelled';
        }

        $start = new \DateTime($event->event_date . ' ' . $event->start_time);
        $end   = new \DateTime($event->event_date . ' ' . $event->end_time);
        $now   = new \DateTime();

        if ($now < $start) {
            return 'upcoming';
        }

        if ($now >= $start && $now <= $end) {
            return 'ongoing';
        }

        return 'completed';
    }

    public function getAllEvents(?string $search): array
    {
        $events = Events::query();

        if ($search) {
            $events = $this->applySearch($events, $search);
        }

        
        $results = $events
            ->orderBy('id', 'ASC')
            ->paginate(8);


        $resource = [];

        foreach ($results->items as $event) {
            $resource[] = [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'purpose' => $event->purpose,
                'notes' => $event->notes,
                'event_date' => $event->event_date,
                'start_time' => date('H:i', strtotime($event->start_time)),
                'end_time' => date('H:i', strtotime($event->end_time)),
                'event_status' => $this->getEventStatus($event->id),
                'event_location' => $event->event_location,
                'max_count' => $event->max_count,
                'participants_count' => $event->participants_count,
                'visible' => $event->visible,
                'admin' => $event->getAdmin() ? [
                    'id' => $event->getAdmin()->id,
                    'name' => $event->getAdmin()->name,
                    'email' => $event->getAdmin()->email,
                ] : null,
                'booking_status' => $this->getEventBookingStatus($event->id)
            ];
        }

        $links = $results->toArray();

        return [$resource, $links];
    }

    public function getVisibleEvents(?string $search)
    {

        $events = Events::query();

        if ($search) {
            $events = $this->applySearch($events, $search);
        }


        $results = $events
            ->orderBy('id', 'ASC')
            ->paginate(10);


        $resource = [];

        foreach ($results->items as $event) {
            if ($event->visible) {
                $resource[] = [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'purpose' => $event->purpose,
                    'notes' => $event->notes,
                    'event_date' => $event->event_date,
                    'start_time' => date('H:i', strtotime($event->start_time)),
                    'end_time' => date('H:i', strtotime($event->end_time)),
                    'event_status' => $this->getEventStatus($event->id),
                    'event_location' => $event->event_location,
                    'max_count' => $event->max_count,
                    'participants_count' => $event->participants_count,
                    'visible' => $event->visible,
                    'admin' => $event->getAdmin() ? [
                        'id' => $event->getAdmin()->id,
                        'name' => $event->getAdmin()->name,
                        'email' => $event->getAdmin()->email,
                    ] : null,
                    'booking_status' => $this->getEventBookingStatus($event->id)
                ];
            }
        }

        return $resource;
    }

    public function getEventBookingStatus($eventId)
    {
        $eventRegistration = EventRegistrations::query()->where('event_id', '=', $eventId)->first();

        return $eventRegistration ? $eventRegistration->booking_status : null;
    }


    private function validateEmail(string $email)
    {
        $error = null;
        if (!Validator::validateFieldExistence($email)) {
            $error = "Email field cannot be empty";
            return $error;
        }

        if (!Validator::validateEmailFormat($email)) {
            $error = "Email format is invalid";
            return $error;
        }

        return $error;
    }

  

    public function validateEventCancelData($reason)
    {
        $errors = [];

        $reasonError = $this->validateText($reason, "Cancel Reason");
        if ($reasonError) {
            $errors['reason'] = $reasonError;
        }

        return $errors;
    }

    public function addEventParticpantCount($eventId)
    {
        $event = Events::find($eventId);
        if ($event && $event->participants_count < $event->max_count) {
            $event->participants_count += 1;
            $event->save();
        }
    }

    public function reduceEventParticpantCount($eventId)
    {
        $event = Events::find($eventId);
        if ($event && $event->participants_count > 0) {
            $event->participants_count -= 1;
            $event->save();
        }
    }

    public function bookEvent($eventId, $userId)
    {
        $eventRegistration = new EventRegistrations();
        $eventRegistration->event_id = $eventId;
        $eventRegistration->user_id = $userId;
        $eventRegistration->booking_status = 'booked';

        $booked = $eventRegistration->save();

        if ($booked) {
            $this->addEventParticpantCount($eventId);
        }
    }

    public function cancelEventBooking($eventId, $userId, $reason)
    {
        $eventRegistration = EventRegistrations::query()->where('event_id', '=', $eventId)
            ->where('user_id', '=', $userId)
            ->first();

        $cancelled = false;

        if ($eventRegistration) {
            $eventRegistration->booking_status = 'cancelled';
            $eventRegistration->cancel_reason = $reason;
            $eventRegistration->cancelled_at = date('Y-m-d H:i:s');
            $cancelled = $eventRegistration->save();
        }

        if ($cancelled) {
            $this->reduceEventParticpantCount($eventId);
        }
    }


    public function validateCreateEventData($title, $description, $eventDate, $eventStartTime, $eventEndTime, $eventLocation, $maxCount)
    {
        $errors = [];

        $titleError = $this->validateName($title, "Event Title");
        if ($titleError) {
            $errors['title'] = $titleError;
        }

        $descriptionError = $this->validateText($description, "Event Description");
        if ($descriptionError) {
            $errors['description'] = $descriptionError;
        }

        $dateError = $this->validateDate($eventDate, "Event Date", true);
        if ($dateError) {
            $errors['date'] = $dateError;
        }

        $startTimeError = $this->validateTime($eventStartTime, "Event Start Time", $eventDate);
        if ($startTimeError) {
            $errors['start_time'] = $startTimeError;
        }

        $endTimeError = $this->validateTime($eventEndTime, "Event End Time", $eventDate, $eventStartTime);
        if ($endTimeError) {
            $errors['end_time'] = $endTimeError;
        }

        $locationError = $this->validateText($eventLocation, "Event Location");
        if ($locationError) {
            $errors['location'] = $locationError;
        }

        $maxCountError = $this->validateInteger($maxCount, "Maximum Participants", 1, null);
        if ($maxCountError) {
            $errors['max_count'] = $maxCountError;
        }

        return $errors;
    }

    public function validateEditEventData($evetId, $title, $eventDate, $eventStartTime, $eventEndTime, $eventLocation, $maxCount)
    {
        $errors = [];

        $event = Events::find($evetId);

        $titleError = $this->validateName($title, "Event Title");
        if ($titleError) {
            $errors['e_title'] = $titleError;
        }


        $dateError = $this->validateDate($eventDate, "Event Date", true);
        if ($dateError) {
            $errors['e_date'] = $dateError;
        }

        $startTimeError = $this->validateTime($eventStartTime, "Event Start Time", $eventDate);
        if ($startTimeError) {
            $errors['e_start_time'] = $startTimeError;
        }

        $endTimeError = $this->validateTime($eventEndTime, "Event End Time", $eventDate, $eventStartTime);
        if ($endTimeError) {
            $errors['e_end_time'] = $endTimeError;
        }

        $locationError = $this->validateText($eventLocation, "Event Location");
        if ($locationError) {
            $errors['e_location'] = $locationError;
        }

        $maxCountError = $this->validateInteger($maxCount, "Maximum Participants", 1, null);
        if ($maxCountError) {
            $errors['e_max_count'] = $maxCountError;
        }

        if (!$maxCountError && $maxCount < $event->participants_count) {
            $errors['e_max_count'] = "Maximum participants cannot be less than already registered participants ({$event->participants_count})";
        }

        return $errors;
    }
    public function validateDeleteEvent($eventId)
    {
        $event = Events::find($eventId);

        if (!$event) {
            return "Event not found";
        }

        $eventStart = new \DateTime(
            $event->event_date . ' ' . $event->start_time
        );

        $now = new \DateTime();
        $limit = (clone $now)->modify('+24 hours');

        if ($eventStart <= $limit) {
            return "Cannot delete an event within 24 hours of its start time";
        }

        if ($event->participants_count > 0) {
            return "Cannot delete an event with registered participants";
        }
    }


    public function validateEditEventVisible($eventId)
    {

        $event = Events::find($eventId);

        $error = null;

        if (!$event) {
            $error = "Event not found";
            return $error;
        }
    }
    public function createEvent($title, $description, $eventDate, $eventStartTime, $eventEndTime, $eventLocation, $maxCount, $purpose, $notes)
    {

        $event = new Events();
        $event->title = $title;
        $event->description = $description;
        $event->admin_id = auth()->user()->id;
        $event->event_date = $eventDate;
        $event->start_time = $eventStartTime;
        $event->end_time = $eventEndTime;
        $event->event_location = $eventLocation;
        $event->max_count = $maxCount;
        $event->notes = $notes;
        $event->purpose = $purpose;

        $event->save();
    }

    public function editEvent($eventId, $title, $eventDate, $eventStartTime, $eventEndTime, $eventLocation, $maxCount)
    {

        $event = Events::find($eventId);
        $event->title = $title;
        $event->admin_id = auth()->user()->id;
        $event->event_date = $eventDate;
        $event->start_time = $eventStartTime;
        $event->end_time = $eventEndTime;
        $event->event_location = $eventLocation;
        $event->max_count = $maxCount;

        $event->save();
    }

    public function editEventVisible($eventId)
    {

        $event = Events::find($eventId);
        $visible = $event->visible;

        if ($visible) {
            $event->visible = false;
        } else {
            $event->visible = true;
        }

        $event->save();
    }

    public function deleteEvent($eventId)
    {

        $event = Events::find($eventId);
        $event->delete();
    }
}
