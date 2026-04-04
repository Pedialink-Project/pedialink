<?php

namespace App\Services\Parent;

use App\Models\Appointment;
use App\Models\Events;
use App\Models\Maternal;
use App\Models\ParentChild;
use App\Models\VaccinationReminder;

class CalendarService
{
    public function getParentCalendarEvents(int $parentId): array
    {
        $events = [];

        $events = array_merge($events, $this->getChildAppointmentEvents($parentId));
        $events = array_merge($events, $this->getMaternalAppointmentEvents($parentId));
        $events = array_merge($events, $this->getVaccinationEvents($parentId));
        $events = array_merge($events, $this->getCampaignEvents());

        usort($events, function ($a, $b) {
            if ($a['date'] === $b['date']) {
                return strcmp($a['title'], $b['title']);
            }

            return strcmp($a['date'], $b['date']);
        });

        return $events;
    }

    private function getChildAppointmentEvents(int $parentId): array
    {
        $childIds = ParentChild::query()
            ->where('parent_id', '=', $parentId)
            ->pluck('child_id');

        if (empty($childIds)) {
            return [];
        }

        $appointments = Appointment::query()
            ->whereIn('child_id', $childIds)
            ->orderBy('id', 'DESC')
            ->get();

        $events = [];

        foreach ($appointments as $appointment) {
            $slot = $appointment->getSlot();
            if (!$slot || !$slot->slot_date) {
                continue;
            }

            $child = $appointment->getChild();
            $doctor = $slot->getDoctor();

            $events[] = [
                'date' => $slot->slot_date,
                'type' => 'appointment',
                'title' => 'Child Appointment',
                'color' => 'linear-gradient(90deg,#28bdf8,#3b82f6)',
                'items' => [[
                    'child' => $child ? $child->name : 'Child',
                    'time' => $slot->start_time,
                    'doctor' => $doctor && $doctor->getUser() ? $doctor->getUser()->name : '-',
                    'status' => ucfirst((string)$appointment->status),
                ]],
            ];
        }

        return $events;
    }

    private function getMaternalAppointmentEvents(int $parentId): array
    {
        $maternalIds = Maternal::query()
            ->where('parent_id', '=', $parentId)
            ->pluck('id');

        if (empty($maternalIds)) {
            return [];
        }

        $appointments = Appointment::query()
            ->whereIn('maternal_id', $maternalIds)
            ->orderBy('id', 'DESC')
            ->get();

        $events = [];

        foreach ($appointments as $appointment) {
            $slot = $appointment->getSlot();
            if (!$slot || !$slot->slot_date) {
                continue;
            }

            $maternal = $appointment->getMaternal();
            $doctor = $slot->getDoctor();

            $events[] = [
                'date' => $slot->slot_date,
                'type' => 'maternal',
                'title' => 'Maternal Appointment',
                'color' => 'linear-gradient(90deg,#f5a623,#f97316)',
                'items' => [[
                    'maternal' => $maternal && $maternal->getUser() ? $maternal->getUser()->name : 'Maternal',
                    'time' => $slot->start_time,
                    'doctor' => $doctor && $doctor->getUser() ? $doctor->getUser()->name : '-',
                    'status' => ucfirst((string)$appointment->status),
                ]],
            ];
        }

        return $events;
    }

    private function getVaccinationEvents(int $parentId): array
    {
        $childIds = ParentChild::query()
            ->where('parent_id', '=', $parentId)
            ->pluck('child_id');

        if (empty($childIds)) {
            return [];
        }

        $reminders = VaccinationReminder::query()
            ->whereIn('child_id', $childIds)
            ->orderBy('scheduled_date', 'ASC')
            ->get();

        $events = [];

        foreach ($reminders as $reminder) {
            if (!$reminder->scheduled_date) {
                continue;
            }

            $child = $reminder->getChild();
            $scheduledVaccine = $reminder->getScheduleVaccine();
            $vaccine = $scheduledVaccine ? $scheduledVaccine->getVaccine() : null;

            $events[] = [
                'date' => $reminder->scheduled_date,
                'type' => 'vaccination',
                'title' => 'Vaccination',
                'color' => 'linear-gradient(90deg,#10b981,#06b6d4)',
                'items' => [[
                    'child' => $child ? $child->name : 'Child',
                    'vaccine' => $vaccine ? $vaccine->name : '-',
                    'status' => ucfirst((string)$reminder->status),
                ]],
            ];
        }

        return $events;
    }

    private function getCampaignEvents(): array
    {
        $campaigns = Events::query()
            ->where('visible', '=', true)
            ->orderBy('event_date', 'ASC')
            ->get();

        $events = [];

        foreach ($campaigns as $campaign) {
            if (!$campaign->event_date) {
                continue;
            }

            $events[] = [
                'date' => $campaign->event_date,
                'type' => 'campaign',
                'title' => $campaign->title,
                'color' => 'linear-gradient(90deg,#9333ea,#6366f1)',
                'items' => [[
                    'location' => $campaign->event_location,
                    'time' => $campaign->start_time,
                ]],
            ];
        }

        return $events;
    }
}
