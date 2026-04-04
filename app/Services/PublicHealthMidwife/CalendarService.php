<?php

namespace App\Services\PublicHealthMidwife;

use App\Models\Appointment;
use App\Models\Child;
use App\Models\Events;
use App\Models\Maternal;
use App\Models\PublicHealthMidwife;
use App\Models\VaccinationReminder;

class CalendarService
{
    public function getPHMCalendarEvents(int $phmId): array
    {
        $phm = PublicHealthMidwife::find($phmId);
        if (!$phm || !$phm->area_id) {
            return [];
        }

        $areaId = $phm->area_id;
        $events = [];

        $events = array_merge($events, $this->getChildAppointmentEvents($areaId));
        $events = array_merge($events, $this->getMaternalAppointmentEvents($areaId));
        $events = array_merge($events, $this->getVaccinationEvents($areaId));
        $events = array_merge($events, $this->getCampaignEvents());


        return $events;
    }

    private function getChildAppointmentEvents(int $areaId): array
    {
        $childIds = Child::query()
            ->where('area_id', '=', $areaId)
            ->pluck('id');

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

    private function getMaternalAppointmentEvents(int $areaId): array
    {
        $maternalIds = Maternal::query()
            ->join('parents as p', 'p.id', '=', 'maternal.parent_id')
            ->where('p.area_id', '=', $areaId)
            ->pluck('maternal.id');

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

    private function getVaccinationEvents(int $areaId): array
    {
        $childIds = Child::query()
            ->where('area_id', '=', $areaId)
            ->pluck('id');

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
