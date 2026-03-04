<?php

namespace App\Services\PublicHealthMidwife;

use App\Models\Appointment;
use App\Models\Child;
use App\Models\PublicHealthMidwife;
use App\Models\VaccinationReminder;

class DashboardService
{
    public function getLinkedChildrenCount()
    {
        $currentUser = auth()->user();
        if ($currentUser) {
            $phm = PublicHealthMidwife::find($currentUser->id);

            $linkedChildren = Child::query()
                ->where("phm_id", "=", $phm->id)
                ->get();

            return count($linkedChildren);
        }
    }

    public function getAppointmentsCount()
    {
        $currentUser = auth()->user();
        if ($currentUser) {
            $phm = PublicHealthMidwife::find($currentUser->id);

            $linkedChildren = Child::query()
                ->select("id")
                ->where("phm_id", "=", $phm->id)
                ->get();

            $childIds = array_map(function ($child) {
                return $child->id;
            }, $linkedChildren);


            $appointment = Appointment::query()
                ->whereIn("child_id", $childIds)
                ->whereIn("status", ["confirmed", "pending"])
                ->get();

            return count($appointment);
        }
    }

    public function getUpcomingVaccinationsCount()
    {
        $currentUser = auth()->user();
        if ($currentUser) {
            $phm = PublicHealthMidwife::find($currentUser->id);

            $linkedChildren = Child::query()
                ->select("id")
                ->where("phm_id", "=", $phm->id)
                ->get();

            $childIds = array_map(function ($child) {
                return $child->id;
            }, $linkedChildren);

            $upcomingVaccinations = Child::query()
                ->join("vaccination_reminders as vr", "vr.child_id", "=", "children.id")
                ->whereIn("children.id", $childIds)
                ->where("vr.status", "=", "pending")
                ->get();

            return count($upcomingVaccinations);
        }
    }

    public function upcomingAppointments()
    {
        $currentUser = auth()->user();
        if ($currentUser) {
            $phm = PublicHealthMidwife::find($currentUser->id);

            $linkedChildren = Child::query()
                ->select("id")
                ->where("phm_id", "=", $phm->id)
                ->get();

            $childIds = array_map(function ($child) {
                return $child->id;
            }, $linkedChildren);

            $appointments = Appointment::query()
                ->whereIn("child_id", $childIds)
                ->whereIn("status", ["confirmed", "pending"])
                ->orderBy("id", "ASC")
                ->paginate(3)
                ->toArray();

            $resource = [];

            foreach ($appointments['items'] as $appointment) {
                $child = $appointment->getChild();
                $slot = $appointment->getSlot();
                $doctor = $slot ? $slot->getDoctor() : null;
                $resource[] = [
                    "id" => $appointment->id,
                    "child_name" => $child ? $child->name : null,
                    "slot_date" => $slot ? $slot->slot_date : null,
                    "start_time" => $slot ? $slot->start_time : null,
                    "end_time" => $slot ? $slot->end_time : null,
                    "status" => $appointment->status,
                    "reason" => $appointment->reason,
                    "doctor_name" => $doctor ? $doctor->getUser()->name : null,
                ];
            }

            return $resource;
        }
    }

    public function upcomingVaccinations()
    {
        $currentUser = auth()->user();
        if ($currentUser) {
            $phm = PublicHealthMidwife::find($currentUser->id);

            $linkedChildren = Child::query()
                ->select("id")
                ->where("phm_id", "=", $phm->id)
                ->get();

            $childIds = array_map(function ($child) {
                return $child->id;
            }, $linkedChildren);

            $upcomingVaccinations = VaccinationReminder::query()
                ->join("children", "vaccination_reminders.child_id", "=", "children.id")
                ->whereIn("children.id", $childIds)
                ->where("vaccination_reminders.status", "=", "pending")
                ->orderBy("vaccination_reminders.scheduled_date", "ASC")
                ->paginate(3)
                ->toArray();

            $resource = [];
            foreach ($upcomingVaccinations['items'] as $item) {
                $child = Child::find($item->child_id);
                $scheduleVaccine = $item->getScheduleVaccine();
                $vaccine = $scheduleVaccine ? $scheduleVaccine->getVaccine() : null;
                $resource[] = [
                    "id" => $item->id,
                    "child_name" => $child ? $child->name : null,
                    "scheduled_date" => $item->scheduled_date,
                    "vaccine_code" => $vaccine ? $vaccine->code : null,
                ];
            }

            return $resource;
        }
    }
}