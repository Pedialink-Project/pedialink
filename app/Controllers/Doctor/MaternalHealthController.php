<?php

namespace App\Controllers\Doctor;
use App\Services\MaternalStatService;

use Library\Framework\Http\Request;

class MaternalHealthController
{
    protected $maternalStatService;

    public function __construct()
    {
        $this->maternalStatService = new MaternalStatService();
    }

    public function index(Request $request, int $id)
    {

        $maternalStats = $this->maternalStatService->getMaternalStatByMaternalId($id);
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
        $weight = $request->input('weight');
        $height = $request->input('height');
        $fundalHeight = $request->input('fundal_height');
        $healthStatus = $request->input('health_status');
        $pregnancyStage = $request->input('pregnancy_stage');
        $notes = $request->input('notes');

        $errors = $this->maternalStatService->validateMaternalStatData($recordedAt, $bmi, $bloodPressure, $bloodSugar, $weight, $height, $fundalHeight, $healthStatus, $pregnancyStage);

        if (count($errors) !== 0) {
            return redirect(route("doctor.maternal.health", ["id" => $id]))
                ->withInput([
                    "recorded_at" => $recordedAt,
                    "bmi" => $bmi,
                    "blood_pressure" => $bloodPressure,
                    "blood_sugar" => $bloodSugar,
                    "health_status" => $healthStatus,
                    "weight" => $weight,
                    "height" => $height,
                    "fundal_height" => $fundalHeight,
                    "pregnancy_stage" => $pregnancyStage,
                    "notes" => $notes
                ])
                ->withErrors($errors)
                ->with("create", true);


        }
        ;

        $this->maternalStatService->createMaternalStat($id, $recordedAt, $bmi, $bloodPressure, $bloodSugar, $weight, $height, $fundalHeight, $healthStatus, $pregnancyStage, $notes);
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
        $weight = $request->input('e_weight');
        $height = $request->input('e_height');
        $fundalHeight = $request->input('e_fundal_height');
        $healthStatus = $request->input('e_health_status');
        $pregnancyStage = $request->input('e_pregnancy_stage');
        $notes = $request->input('e_notes');

        $errors = $this->maternalStatService->validateMaternalStatData($recordedAt, $bmi, $bloodPressure, $bloodSugar, $weight, $height, $fundalHeight, $healthStatus, $pregnancyStage, true);

        if (count($errors) !== 0) {
            return redirect(route("doctor.maternal.health", ["id" => $id]))
                ->withInput([
                    "e_recorded_at" => $recordedAt,
                    "e_bmi" => $bmi,
                    "e_blood_pressure" => $bloodPressure,
                    "e_blood_sugar" => $bloodSugar,
                    "e_health_status" => $healthStatus,
                    "e_weight" => $weight,
                    "e_height" => $height,
                    "e_fundal_height" => $fundalHeight,
                    "e_pregnancy_stage" => $pregnancyStage,
                    "e_notes" => $notes
                ])
                ->withErrors($errors)
                ->with("edit", $maternalStatId);
        }

        $this->maternalStatService->editMaternalStat($maternalStatId, $recordedAt, $bmi, $bloodPressure, $bloodSugar, $weight, $height, $fundalHeight, $healthStatus, $pregnancyStage, $notes);
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
