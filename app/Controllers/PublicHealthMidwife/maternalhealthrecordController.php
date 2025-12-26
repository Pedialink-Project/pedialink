<?php

namespace App\Controllers\PublicHealthMidwife;
use App\Services\maternalrecordService;
use Library\Framework\Http\Request;

class maternalhealthrecordController
{
    protected $maternalrecordService;

    public function __construct()
    {
        $this->maternalrecordService = new maternalrecordService();
    }

    public function index(Request $request, int $id)
    {

        $maternalrecords = $this->maternalrecordService->getMaternalRecordByMaternalId($id);
        return view("phm/maternalhealth", [
            'parent_id' => $id,
            "items" => $maternalrecords
        ]);
    }

    public function createMaternalRecord(Request $request, int $id)
    {
        $visitdate = $request->input('visit_date');
        $bmi = $request->input('bmi');
        $bloodPressure = $request->input('blood_pressure');
        $bloodSugar = $request->input('blood_sugar');
        $healthStatus = $request->input('health_status');

        $errors = $this->maternalrecordService->validateMaternalRecordData($visitdate, $bmi, $bloodPressure, $bloodSugar,$healthStatus);

        if (count($errors) !== 0) {
            return redirect(route("phm.maternal.health", ["id" => $id]))
                ->withInput([
                    "visit_date" => $visitdate,
                    "bmi" => $bmi,
                    "blood_pressure" => $bloodPressure,
                    "blood_sugar" => $bloodSugar,
                    "health_status" => $healthStatus,
                ])
                ->withErrors($errors)
                ->with("create", true);


        }

        $this->maternalrecordService->createMaternalRecord($id, $visitdate, $bmi, $bloodPressure, $bloodSugar, $healthStatus);
        return redirect(route("phm.maternal.health", ["id" => $id]))
            ->withMessage(
                "Health record was successfully created",
                "Health Record Created",
                "success",
            );

    }


    public function editMaternalRecord(Request $request,int $id,int $recordId)
    {

        $maternalStatId = $recordId;
        $recordedAt = $request->input('e_visit_date');
        $bmi = $request->input('e_bmi');
        $bloodPressure = $request->input('e_blood_pressure');
        $bloodSugar = $request->input('e_blood_sugar');
        $healthStatus = $request->input('e_health_status');

        $errors = $this->maternalrecordService->validateMaternalRecordData($recordedAt, $bmi, $bloodPressure, $bloodSugar, $healthStatus,);

        if (count($errors) !== 0) {
            return redirect(route("phm.maternal.health", ["id" => $id]))
                ->withInput([
                    "e_recorded_at" => $recordedAt,
                    "e_bmi" => $bmi,
                    "e_blood_pressure" => $bloodPressure,
                    "e_blood_sugar" => $bloodSugar,
                    "e_health_status" => $healthStatus,
                ])
                ->withErrors($errors)
                ->with("edit", $maternalStatId);
        }

        $this->maternalrecordService->editMaternalRecord($maternalStatId, $recordedAt, $bmi, $bloodPressure, $bloodSugar, $healthStatus,);
        return redirect(route("phm.maternal.health", ["id" => $id]))
            ->withMessage(
                "Health record was successfully updated",
                "Health Record Updated",
                "success",
            );
    }


    public function deleteMaternalRecord(Request $request, $id, $recordId)
    {

        $this->maternalrecordService->deleteMaternalRecord($recordId);

        return redirect(route("phm.maternal.health", ["id" => $id]))
            ->withMessage(
                "Health record was successfully deleted",
                "Health Record Deleted",
                "error",
            );
    }


}
