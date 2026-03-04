<?php

namespace App\Services\PublicHealthMidwife;

use App\Models\Appointment;
use App\Models\Child;
use App\Models\PublicHealthMidwife;

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
}