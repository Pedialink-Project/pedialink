<?php

namespace App\Services\Admin;

use App\Models\Child;
use App\Models\ChildAccessRequest;
use App\Models\ChildMisc;
use App\Models\Doctor;
use App\Models\ParentM;
use App\Models\PublicHealthMidwife;

class DashboardService
{
    public function getTotalChildrenCount()
    {
        $children = Child::all();

        return count($children);
    }

    public function getActivePhmCount()
    {
        $phm = PublicHealthMidwife::all();

        return count($phm);
    }

    public function getTotalParentsCount()
    {
        $parents = ParentM::all();

        return count($parents);
    }

    public function getTotalAccessRequestsCount()
    {
        $requests = ChildAccessRequest::query()
            ->where("accepted", "=", 0)
            ->get();

        return count($requests);
    }

    public function getTotalLinkageRequestsCount()
    {
        $requests = ChildMisc::query()
            ->where("accepted", "=", 0)
            ->get();

        return count($requests);
    }

    public function getActiveDoctorsCount()
    {
        $doctors = Doctor::all();

        return count($doctors);
    }
}