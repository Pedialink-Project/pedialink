<?php

namespace App\Controllers\Admin;

use App\Services\Admin\VaccineService;
use Library\Framework\Http\Request;

class VaccineController
{
    private VaccineService $vaccineService;

    public function __construct()
    {
        $this->vaccineService = new VaccineService();
    }

    public function vaccines(Request $request)
    {
        $search = $request->query("search") ?? "";

        [$vaccines, $links] = $this->vaccineService->getVaccineData($search);
        return view("admin/vaccination/vaccines", [
            "vaccines" => $vaccines,
            "links" => $links
        ]);
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