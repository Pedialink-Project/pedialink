<?php

namespace App\Services;

use App\Models\Events;
use App\Models\EventRegistrations;
use APP\Models\User;
use App\Helpers\Validator;
use App\Rules\NumberRule;
use App\Rules\PhoneRule;
use App\Rules\NameRule;
use App\Rules\TextRule;
use App\Rules\DateRule;
use App\Services\NotificationService;
use Library\Framework\Database\QueryBuilder;

class EventService
{

    use NameRule, PhoneRule, TextRule, DateRule, NumberRule;

    private NotificationService $notificationService;

    public function __construct()
    {
        $this->notificationService = new NotificationService();
    }


    private function applySearch(QueryBuilder $events, string $search)
    {
        $events->where('title', 'ILIKE', "$search%");

        return $events;
    }



    function getEventStatus($eventId): string
    {

        $event = Events::query()->where('id', '=', $eventId)->first();


        if ($event->is_cancelled) {
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

    public function getVisibleEvents(?string $search = null)
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


    public function getDashboardEvents(int $limit = 3)
    {
        $events = Events::query()
            ->where('visible', '=', true)
            ->orderBy('event_date', 'ASC')
            ->limit($limit)
            ->get();

        $resource = [];

        foreach ($events as $event) {
            $resource[] = [
                'id' => $event->id,
                'title' => $event->title,
                'event_date' => $event->event_date,
                'start_time' => date('H:i', strtotime($event->start_time)),
                'end_time' => date('H:i', strtotime($event->end_time)),
                'event_status' => $this->getEventStatus($event->id),
                'event_location' => $event->event_location,
                'participants_count' => $event->participants_count,
            ];
        }

        return $resource;
    }

    public function getEventBookingStatus($eventId)
    {
        $eventRegistration = EventRegistrations::query()->where('event_id', '=', $eventId)->first();

        return $eventRegistration ? $eventRegistration->booking_status : null;
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
        $event = Events::find($eventId);

        if (!$event) {
            return  "Event not found";
        }

        if ($event->is_cancelled) {
            return "Event is cancelled";
        }

        if ($event->participants_count >= $event->max_count) {
            return "Event is fully booked";
        }

        $status = $this->getEventStatus($eventId);
        if ($status !== 'upcoming') {
            return "Cannot book this event";
        }

        $alreadyBooked = EventRegistrations::query()
            ->where('event_id', '=', $eventId)
            ->where('user_id', '=', $userId)
            ->where('booking_status', '=', 'booked')
            ->first();

        if ($alreadyBooked) {
            return "You have already booked this event";
        }

        $registration = new EventRegistrations();
        $registration->event_id = $eventId;
        $registration->user_id = $userId;
        $registration->booking_status = 'booked';
        $registration->save();

        $this->addEventParticpantCount($eventId);

        $event = Events::find($eventId);
        $adminId = $event->admin_id;

        $this->notificationService->notify(
            $adminId,
            "New Event Booking",
            "User P-00{$userId} has booked your event '{$event->title}'.",
            "event",
            $event->id
        );
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
            $event = Events::find($eventId);
            $this->notificationService->notify(
                $event->admin_id,
                "Event Booking Cancelled",
                "User P-00{$userId} has cancelled their booking for '{$event->title}'. Reason: {$reason}",
                "event",
                $event->id
            );
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

        if (!($event->is_cancelled)) {
            return "Only cancelled events can be deleted";
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

        if (!$event) {
            $error = "Event not found";
            return $error;
        }

        $eventStart = new \DateTime(
            $event->event_date . ' ' . $event->start_time
        );

        $now   = new \DateTime();
        $limit = (clone $now)->modify('+24 hours');

        if ($eventStart <= $limit) {
            $error = "Event details can only be edited more than 24 hours before the event starts";
            return $error;
        }
        $event->title = $title;
        $event->admin_id = auth()->user()->id;
        $event->event_date = $eventDate;
        $event->start_time = $eventStartTime;
        $event->end_time = $eventEndTime;
        $event->event_location = $eventLocation;
        $event->max_count = $maxCount;

        $event->save();

        $registrations = EventRegistrations::query()
            ->where('event_id', '=', $eventId)
            ->where('booking_status', '=', 'booked')
            ->get();

        if (!empty($registrations)) {

            foreach ($registrations as $registration) {
                $this->notificationService->notify(
                    $registration->user_id,
                    "Event Updated",
                    "Details of the event '{$event->title}' have been updated. Please review the latest information.",
                    "event",
                    $eventId
                );
            }
        }

        return null;
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

    public function cancelEvent($eventId)
    {
        $event = Events::find($eventId);
        if (!$event) {
            return "Event not found";
        }

        if ($event->is_cancelled) {
            return "Event is already cancelled";
        }

        $event->is_cancelled = true;
        $event->visible = false;
        $event->save();



        $registrations = EventRegistrations::query()
            ->where('event_id', '=', $eventId)
            ->where('booking_status', '=', 'booked')
            ->get();

        if (!empty($registrations)) {

            foreach ($registrations as $registration) {

                $registration->booking_status = 'cancelled';
                $registration->cancel_reason = 'Event cancelled by administrator';
                $registration->cancelled_at = date('Y-m-d H:i:s');
                $registration->save();

                $this->notificationService->notify(
                    $registration->user_id,
                    "Event Cancelled",
                    "The event '{$event->title}' has been cancelled. Reason: Event cancelled by administrator",
                    "event",
                    $eventId
                );
            }
        }

        return null;
    }


    public function deleteEvent($eventId)
    {

        $event = Events::find($eventId);
        $event->delete();
    }

    public function getParticipantsByEventId(
        int $eventId,
        ?string $search = null,
        ?array $filters = null
    ) {

        $query = EventRegistrations::query()
            ->where('event_id', '=', $eventId);

      
        if ($search) {

            $users = User::query()
                ->where('name', 'ILIKE', "%{$search}%")
                ->get();

            $userIds = [];

            foreach ($users as $user) {
                $userIds[] = $user->id;
            }

            // Prevent empty WHERE IN ()
            if (empty($userIds)) {
                return [[], []];
            }

            $query->whereIn('user_id', $userIds);
        }

       
        if (!empty($filters['booking_status'])) {
            $query->whereIn('booking_status', $filters['booking_status']);
        }

        /**
         * PAGINATION
         */
        $results = $query
            ->orderBy('registration_date', 'DESC')
            ->paginate(10)
            ->toArray();

        $resource = [];

        $userIds = [];

        foreach ($results['items'] as $registration) {
            $userIds[] = $registration->user_id;
        }
        $users = User::query()
            ->whereIn('id', $userIds)
            ->get();

        $userMap = [];

        foreach ($users as $user) {
            $userMap[$user->id] = $user;
        }

       
        foreach ($results['items'] as $registration) {

            $user = $userMap[$registration->user_id] ?? null;

            $resource[] = [
                'id' => $registration->id,
                'user_id' => $registration->user_id,
                'name' => $user->name,
                'email' => $user->email,
                'booking_status' => $registration->booking_status,
                'cancel_reason' => $registration->cancel_reason,
                'cancelled_at' => $registration->cancelled_at
                    ? date('Y-m-d H:i', strtotime($registration->cancelled_at))
                    : null,
                'registration_date' => date(
                    'Y-m-d H:i',
                    strtotime($registration->registration_date)
                ),
            ];
        }

        $links = array_diff_key($results, ['items' => true]);

        return [$resource, $links];
    }
}
