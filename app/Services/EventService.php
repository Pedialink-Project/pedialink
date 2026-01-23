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

    private function applyFilters(QueryBuilder $events, array $filters)
    {
        foreach ($filters as $filterName => $filterValue) {
            if ($filterValue && is_array($filterValue)) {
                $events->whereIn('event_status', $filterValue);
            }
        }

        return $events;
    }
    public function getAllEvents(?string $search, ?array $filters): array
    {
        $events = Events::query();

        if ($search) {
            $events = $this->applySearch($events, $search);
        }

        if ($filters) {
            $events = $this->applyFilters($events, $filters);
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
                'event_time' => date('H:i', strtotime($event->event_time)),
                'event_status' => $event->event_status,
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

    public function getVisibleEvents(?string $search, ?array $filters){

        $events = Events::query();

        if ($search) {
            $events = $this->applySearch($events, $search);
        }

        if ($filters) {
            $events = $this->applyFilters($events, $filters);
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
                    'event_time' => date('H:i', strtotime($event->event_time)),
                    'event_status' => $event->event_status,
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

    public function validateEventBookingData($name, $email, $phone)
    {
        $errors = [];

        $nameError = $this->validateName($name, "Participant Name");
        if ($nameError) {
            $errors['name'] = $nameError;
        }

        $emailError = $this->validateEmail($email);
        if ($emailError) {
            $errors['email'] = $emailError;
        }

        $phoneError = $this->validatePhone($phone);
        if ($phoneError) {
            $errors['phone'] = $phoneError;
        }

        return $errors;
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

    public function bookEvent($eventId, $userId, $name, $email, $phone)
    {
        $eventRegistration = new EventRegistrations();
        $eventRegistration->event_id = $eventId;
        $eventRegistration->user_id = $userId;
        $eventRegistration->name = $name;
        $eventRegistration->email = $email;
        $eventRegistration->phone = $phone;
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


    public function validateCreateEventData($title, $description, $eventDate, $eventTime, $eventLocation, $maxCount)
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

        $timeError = $this->validateTime($eventTime, "Event Time");
        if ($timeError) {
            $errors['time'] = $timeError;
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

    public function validateEditEventData($title, $eventDate, $eventTime, $eventLocation, $maxCount)
    {
        $errors = [];

        $titleError = $this->validateName($title, "Event Title");
        if ($titleError) {
            $errors['e_title'] = $titleError;
        }


        $dateError = $this->validateDate($eventDate, "Event Date", true);
        if ($dateError) {
            $errors['e_date'] = $dateError;
        }

        $timeError = $this->validateTime($eventTime, "Event Time");
        if ($timeError) {
            $errors['e_time'] = $timeError;
        }

        $locationError = $this->validateText($eventLocation, "Event Location");
        if ($locationError) {
            $errors['e_location'] = $locationError;
        }

        $maxCountError = $this->validateInteger($maxCount, "Maximum Participants", 1, null);
        if ($maxCountError) {
            $errors['e_max_count'] = $maxCountError;
        }

        return $errors;
    }

    public function validateDeleteEvent($eventId)
    {

        $event = Events::find($eventId);

        $error = null;

        if (!$event) {
            $error = "Event not found";
            return $error;
        }

        if ($event->event_status == 'ongoning') {
            $error = "Cannot delete ongoing events";
            return $error;
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
    public function createEvent($title, $description, $eventDate, $eventTime, $eventLocation, $maxCount, $purpose, $notes)
    {

        $event = new Events();
        $event->title = $title;
        $event->description = $description;
        $event->admin_id = auth()->user()->id;
        $event->event_date = $eventDate;
        $event->event_time = $eventTime;
        $event->event_location = $eventLocation;
        $event->max_count = $maxCount;
        $event->notes = $notes;
        $event->purpose = $purpose;
        $event->event_status = 'upcoming';

        $event->save();

    }

    public function editEvent($eventId, $title, $eventDate, $eventTime, $eventLocation, $maxCount)
    {

        $event = Events::find($eventId);
        $event->title = $title;
        $event->admin_id = auth()->user()->id;
        $event->event_date = $eventDate;
        $event->event_time = $eventTime;
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
?>