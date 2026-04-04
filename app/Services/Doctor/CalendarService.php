<?php

namespace App\Services\Doctor;

use App\Models\Appointment;
use App\Models\Events;
use App\Models\VaccinationReminder;

class CalendarService
{
    public function getDoctorCalendarEvents(int $doctorId): array
    {
        $events = [];

        $events = array_merge($events, $this->getAppointmentEvents($doctorId));
        $events = array_merge($events, $this->getVaccinationEvents($doctorId));
        $events = array_merge($events, $this->getCampaignEvents());

        return $events;
    }

    private function getAppointmentEvents(int $doctorId): array
    {
        $appointments = Appointment::query()
            ->join('appointment_slots as s', 's.id', '=', 'appointments.slot_id')
            ->where('s.doctor_id', '=', $doctorId)
            ->orderBy('s.slot_date', 'ASC')
            ->get();

        $events = [];

        foreach ($appointments as $appointment) {
            $slot = $appointment->getSlot();
            if (!$slot || !$slot->slot_date) {
                continue;
            }

            $child = $appointment->getChild();
            $maternal = $appointment->getMaternal();

            if ($child) {
                $events[] = [
                    'date' => $slot->slot_date,
                    'type' => 'appointment',
                    'title' => 'Child Appointment',
                    'color' => 'linear-gradient(90deg,#28bdf8,#3b82f6)',
                    'items' => [[
                        'child' => $child->name,
                        'time' => $slot->start_time,
                        'status' => ucfirst((string)$appointment->status),
                    ]],
                ];
            }

            if ($maternal) {
                $events[] = [
                    'date' => $slot->slot_date,
                    'type' => 'maternal',
                    'title' => 'Maternal Appointment',
                    'color' => 'linear-gradient(90deg,#f5a623,#f97316)',
                    'items' => [[
                        'maternal' => $maternal->getUser() ? $maternal->getUser()->name : 'Maternal',
                        'time' => $slot->start_time,
                        'status' => ucfirst((string)$appointment->status),
                    ]],
                ];
            }
        }

        return $events;
    }

    private function getVaccinationEvents(int $doctorId): array
    {
        $appointments = Appointment::query()
            ->join('appointment_slots as s', 's.id', '=', 'appointments.slot_id')
            ->where('s.doctor_id', '=', $doctorId)
            ->whereNotNull('appointments.child_id')
            ->get();

        $childIds = [];
        foreach ($appointments as $appointment) {
            if ($appointment->child_id) {
                $childIds[] = (int)$appointment->child_id;
            }
        }

        $childIds = array_values(array_unique($childIds));
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
