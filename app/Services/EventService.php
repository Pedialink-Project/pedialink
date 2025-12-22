<?php

namespace App\Services;

use App\Models\Events;

class EventService
{
    private function getAllEvents(): array
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
                'event_location' => $event->event_location,
                'max_count' => $event->max_count,
                'admin' => $event->getAdmin() ? [
                    'id' => $event->getAdmin()->id,
                    'name' => $event->getAdmin()->name,
                    'email' => $event->getAdmin()->email,
                ] : null,
            ];
        }

        return $resource;
    }

}
?>