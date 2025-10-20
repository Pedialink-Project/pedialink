<?php

namespace App\Controllers\Admin;

use Library\Framework\Http\Request;

class VaccineController
{
    public function vaccines(Request $request)
    {
        return view("admin/vaccination/vaccines");
    }

    public function schedule(Request $request)
    {
        return view("admin/vaccination/schedule");
    }

    public function manageSchedule(Request $request, int $schedule_id)
    {
        return view("admin/vaccination/manage", [
            "schedule_id" => $schedule_id,
        ]);
    }
}