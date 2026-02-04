<?php

namespace App\Controllers\Admin;

use App\Models\Schedule;
use App\Models\Vaccine;
use App\Services\Admin\ManageScheduleService;
use App\Services\Admin\ScheduleService;
use App\Services\Admin\VaccineService;
use Exception;
use Library\Framework\Http\Request;

class VaccineController
{
    private VaccineService $vaccineService;
    private ScheduleService $scheduleService;
    private ManageScheduleService $manageScheduleService;

    public function __construct()
    {
        $this->vaccineService = new VaccineService();
        $this->scheduleService = new ScheduleService();
        $this->manageScheduleService = new ManageScheduleService();
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

    public function editVaccine(Request $request, int $id)
    {
        $data = [
            "e_name" => $request->input("e_name") ?? "",
            "e_code" => $request->input("e_code") ?? ""
        ];

        $errors = $this->vaccineService->validateVaccineData(
            $data["e_name"], $data["e_code"], true
        );

        if (count($errors) !== 0) {
            return redirect(route("admin.vaccination.vaccines"))
                ->withInput($data)
                ->withErrors($errors)
                ->with("edit", $id);
        }

        $vaccine = Vaccine::find($id);

        if ($vaccine) {
            $vaccine->name = $data['e_name'];
            $vaccine->code = $data['e_code'];
            $vaccine->save();

            return redirect(route("admin.vaccination.vaccines"))
                ->withMessage("Edited vaccine", "Success", "success");
        }

        return redirect(route("admin.vaccination.vaccines"))
                ->withMessage("An unexpected error occured", "Fail", "error");
    }

    public function deleteVaccine(Request $request, int $id)
    {
        $vaccine = Vaccine::find($id);

        try {
            if ($vaccine) {
                $vaccine->delete();

                return redirect(route("admin.vaccination.vaccines"))
                    ->withMessage(
                        "Vaccine removed successfully", 
                        "Success",
                        "success"
                    );
            }
        } catch (Exception $e) {
            return redirect(route("admin.vaccination.vaccines"))
                ->withMessage(
                    "Failed to remove vaccine", 
                    "Fail",
                    "error"
                );
        }
    }

    public function schedule(Request $request)
    {
        $search = $request->query("search") ?? "";
        [$schedules, $links] = $this->scheduleService
            ->getScheduleData($search);
        return view("admin/vaccination/schedule", [
            "schedules" => $schedules,
            "links" => $links
        ]);
    }

    public function addSchedule(Request $request)
    {
        $data = [
            "name" => $request->input("name"),
            "version" => $request->input("version"),
            "effective_from" => $request->input("effective_from"),
        ];

        $errors = $this->scheduleService->validateScheduleData(
            $data['name'], $data['version'], $data['effective_from']
        );

        if (count($errors) !== 0) {
            return redirect(route("admin.vaccination.schedule"))
                ->withInput($data)
                ->withErrors($errors)
                ->with("add", true);
        }

        $schedule = new Schedule();
        $schedule->name = $data['name'];
        $schedule->version = $data['version'];
        $schedule->effective_from = $data['effective_from'];
        $schedule->save();

        return redirect(route("admin.vaccination.schedule"))
            ->withMessage(
                "Successfully created schedule",
                "Success",
                "success"
            );
    }

    public function editSchedule(Request $request, int $id)
    {
        $data = [
            "e_name" => $request->input("e_name"),
            "e_version" => $request->input("e_version"),
            "e_effective_from" => $request->input("e_effective_from"),
        ];

        $errors = $this->scheduleService->validateScheduleData(
            $data['e_name'], $data['e_version'], $data['e_effective_from'],
            true
        );

        if (count($errors) !== 0) {
            return redirect(route("admin.vaccination.schedule"))
                ->withInput($data)
                ->withErrors($errors)
                ->with("edit", $id);
        }

        $schedule = Schedule::find($id);

        if ($schedule) {
            $schedule->name = $data['e_name'];
            $schedule->version = $data['e_version'];
            $schedule->effective_from = $data['e_effective_from'];
            $schedule->save();
        }

        return redirect(route("admin.vaccination.schedule"))
            ->withMessage(
                "Successfully modified schedule",
                "Success",
                "success"
            );
    }

    public function deleteSchedule(Request $request, int $id)
    {
        $canDelete = $this->scheduleService->validateDelete($id);

        if (!$canDelete) {
            return redirect(route("admin.vaccination.schedule"))
                ->withMessage(
                    "Cannot delete active schedule",
                    "Failed",
                    "error"
                );
        }

        $schedule = Schedule::find($id);

        if ($schedule) {
            try {
                $schedule->delete();

                return redirect(route("admin.vaccination.schedule"))
                    ->withMessage(
                        "Successfully deleted schedule",
                        "Success",
                        "success"
                    );
            } catch (Exception $e) {
                return redirect(route("admin.vaccination.schedule"))
                    ->withMessage(
                        "Schedule was previously used by the system",
                        "Failed",
                        "error"
                    );
            }
        }

        return redirect(route("admin.vaccination.schedule"))
            ->withMessage(
                "Schedule does not exist",
                "Failed",
                "error"
            );
    }

    public function enableSchedule(Request $request, int $id)
    {
        
        $schedule = Schedule::find($id);
        if ($schedule) {
            try {
                $schedules = Schedule::query()
                    ->where("id", "!=", $id)
                    ->get();

                foreach ($schedules as $otherSchedule) {
                    $otherSchedule->active = 0;
                    $otherSchedule->save();
                }

                $schedule->active = 1;
                $schedule->save();

                 return redirect(route("admin.vaccination.schedule"))
                    ->withMessage(
                        "Schedule successfully enabled",
                        "Success",
                        "success"
                    );
            } catch (Exception $e) {
                 return redirect(route("admin.vaccination.schedule"))
                    ->withMessage(
                        "An unexpected error occured",
                        "Failed",
                        "error"
                    );
            }
        }

         return redirect(route("admin.vaccination.schedule"))
            ->withMessage(
                "Schedule does not exist",
                "Failed",
                "error"
            );
    }

    public function disableSchedule(Request $request, int $id)
    {
        $schedule = Schedule::find($id);

        if ($schedule) {
            try {
                $schedule->active = 0;
                $schedule->save();

                return redirect(route("admin.vaccination.schedule"))
                    ->withMessage(
                        "Successfully disabled schedule",
                        "Success",
                        "success"
                    );
            } catch (Exception $e) {
                return redirect(route("admin.vaccination.schedule"))
                    ->withMessage(
                        "Failed to disable schedule",
                        "Failed",
                        "error"
                    );
            }
        }

        return redirect(route("admin.vaccination.schedule"))
            ->withMessage(
                "Schedule does not exist",
                "Failed",
                "error"
            );
    }

    public function manageSchedule(Request $request, int $schedule_id)
    {
        $search = $request->query("search") ?? '';

        [$scheduleList, $vaccines, $links] = $this->manageScheduleService->getScheduleVaccineData(
            $schedule_id, $search
        );
        return view("admin/vaccination/manage", [
            "schedule_id" => $schedule_id,
            "scheduleList" => $scheduleList,
            "vaccines" => $vaccines,
            "links" => $links
        ]);
    }
}