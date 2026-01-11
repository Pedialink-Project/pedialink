<?php

namespace App\Services;

use App\Models\Events;
use App\Models\EventRegistrations;
use App\Helpers\Validator;
use App\Rules\PhoneRule;
use App\Rules\NameRule; 

class EventService
{

    use NameRule,PhoneRule;
    public function getAllEvents(): array
    {
        $events = Events::all();

        $resource = [];

        foreach ($events as $event) {
            $resource[] = [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'purpose' => $event->purpose,
                'notes' => $event->notes,
                'event_date' => $event->event_date,
                'event_time' => $event->event_time,
                'event_status' => $event->event_status,
                'event_location' => $event->event_location,
                'max_count' => $event->max_count,
                'participants_count'=> $event->participants_count,
                'admin' => $event->getAdmin() ? [
                    'id' => $event->getAdmin()->id,
                    'name' => $event->getAdmin()->name,
                    'email' => $event->getAdmin()->email,
                ] : null,
                'booking_status' => $this->getEventBookingStatus($event->id)
            ];
        }

        return $resource;
    }

    public function getEventBookingStatus($eventId)
    {
        $eventRegistration =  EventRegistrations::find($eventId);

    return $eventRegistration ? $eventRegistration->booking_status : null;

    }


     private function validateEmail(string $email)
    {
        $error = null;
        if(!Validator::validateFieldExistence($email)) {
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

        $nameError = $this->validateName($name,"Participant Name");
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
   

    public function addEventParticpantCount($eventId)
    {
        $event = Events::find($eventId);
        if ($event) {
            $event->participants_count += 1;
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

       if($booked){
        $this->addEventParticpantCount($eventId);
       }
    }

}
?>