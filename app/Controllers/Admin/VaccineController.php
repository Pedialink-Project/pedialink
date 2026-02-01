<?php

namespace App\Controllers\Admin;

use App\Models\Vaccine;
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

    public function addVaccine(Request $request)
    {
        $data = [
            "name" => $request->input("name") ?? "",
            "code" => $request->input("code") ?? ""
        ];

        $errors = $this->vaccineService->validateVaccineData(
            $data["name"], $data["code"]
        );

        if (count($errors) !== 0) {
            return redirect(route("admin.vaccination.vaccines"))
                ->withInput($data)
                ->withErrors($errors)
                ->with("add", true);
        }

        $vaccine = new Vaccine();
        $vaccine->name = $data["name"];
        $vaccine->code = $data["code"];
        $vaccine->save();

        return redirect(route("admin.vaccination.vaccines"))
            ->withMessage("Added vaccine", "Success", "success");
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