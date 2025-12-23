<?php

namespace App\Controllers\PublicHealthMidwife;
use App\Services\maternalrecordService;
use Library\Framework\Http\Request;

class maternalhealthrecordController
{
    protected $maternalStatService;

    public function __construct()
    {
        $this->maternalStatService = new maternalrecordService();
    }

    public function index(Request $request, int $id)
    {

        $maternalStats = $this->maternalStatService->getMaternalRecordByMaternalId($id);
        return view("doctor/maternalhealth", [
            'maternalId' => $id,
            "items" => $maternalStats
        ]);
    }

    public function createMaternalRecord(Request $request, $id)
    {

        $maternalId = $id;
        $recordedAt = $request->input('recorded_at');
        $bmi = $request->input('bmi');
        $bloodPressure = $request->input('blood_pressure');
        $bloodSugar = $request->input('blood_sugar');
        $healthStatus = $request->input('health_status');

        $errors = $this->maternalStatService->validateMaternalRecordData($recordedAt, $bmi, $bloodPressure, $bloodSugar,$healthStatus);

        if (count($errors) !== 0) {
            return redirect(route("doctor.maternal.health", ["id" => $id]))
                ->withInput([
                    "recorded_at" => $recordedAt,
                    "bmi" => $bmi,
                    "blood_pressure" => $bloodPressure,
                    "blood_sugar" => $bloodSugar,
                    "health_status" => $healthStatus,
                ])
                ->withErrors($errors)
                ->with("create", true);


        }
        ;

        $this->maternalStatService->createMaternalStat($id, $recordedAt, $bmi, $bloodPressure, $bloodSugar, $healthStatus);
        return redirect(route("doctor.maternal.health", ["id" => $id]))
            ->withMessage(
                "Health record was successfully created",
                "Health Record Created",
                "success",
            );

    }


    public function editMaternalRecord(Request $request,int $id,int $recordId)
    {

        $maternalStatId = $recordId;
        $recordedAt = $request->input('e_recorded_at');
        $bmi = $request->input('e_bmi');
        $bloodPressure = $request->input('e_blood_pressure');
        $bloodSugar = $request->input('e_blood_sugar');
        $healthStatus = $request->input('e_health_status');

        $errors = $this->maternalStatService->validateMaternalRecordData($recordedAt, $bmi, $bloodPressure, $bloodSugar, $healthStatus,);

        if (count($errors) !== 0) {
            return redirect(route("doctor.maternal.health", ["id" => $id]))
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

        $this->maternalStatService->editMaternalStat($maternalStatId, $recordedAt, $bmi, $bloodPressure, $bloodSugar, $healthStatus,);
        return redirect(route("doctor.maternal.health", ["id" => $id]))
            ->withMessage(
                "Health record was successfully updated",
                "Health Record Updated",
                "success",
            );
    }


    public function deleteMaternalRecord(Request $request, $id, $recordId)
    {

        $this->maternalStatService->deleteMaternalStat($recordId);

        return redirect(route("doctor.maternal.health", ["id" => $id]))
            ->withMessage(
                "Health record was successfully deleted",
                "Health Record Deleted",
                "error",
            );
    }


}
