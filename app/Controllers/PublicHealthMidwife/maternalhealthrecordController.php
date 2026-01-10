<?php

namespace App\Controllers\PublicHealthMidwife;
use App\Services\maternalrecordService;
use Library\Framework\Http\Request;
use App\Models\ParentM;


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
            "items" => $maternalrecords,
            "parentId" => $id,
            ]);
    }

    public function createMaternalRecord(Request $request, int $id)
    {
        $parent = ParentM::find($id);

    if (!$parent) {
        return redirect(route("phm.maternal.health", ["id" => $id]))
            ->withErrors([
                'parent' => 'Invalid parent ID'
            ]);
    }
        $visitdate = $request->input('visit_date');
        $bmi = $request->input('bmi');
        $bloodPressure = $request->input('blood_pressure');
        $bloodSugar = $request->input('blood_sugar');
        $weight = $request->input('weight');
        $trimester = $request->input('trimester');
        $healthStatus = $request->input('health_status');
        $additionalNotes = $request->input('notes');

        $errors = $this->maternalrecordService->validateMaternalRecordData($visitdate, $bmi, $bloodPressure, $bloodSugar, $weight, $trimester, $healthStatus, $additionalNotes);

        if (count($errors) !== 0) {
            return redirect(route("phm.maternal.health", ["id" => $id]))
                ->withInput([
                    "visit_date" => $visitdate,
                    "bmi" => $bmi,
                    "blood_pressure" => $bloodPressure,
                    "blood_sugar" => $bloodSugar,
                    "weight" => $weight,
                    "trimester" => $trimester,
                    "health_status" => $healthStatus,
                    "notes" => $additionalNotes,
                ])
                ->withErrors($errors)
                ->with("create", true);


        }

        $this->maternalrecordService->createMaternalRecord($id, $visitdate, $bmi, $bloodPressure, $bloodSugar,$weight, $trimester, $healthStatus, $additionalNotes);
        return redirect(route("phm.maternal.health", ["id" => $id]))
            ->withMessage(
                "Health record was successfully created",
                "Health Record Created",
                "success",
            );

    }


    public function editMaternalRecord(Request $request,int $id,int|string $recordId)
    {   
        
        $maternalRecordId = $recordId;
        $visitdate = $request->input('e_visit_date');
        $bmi = $request->input('e_bmi');
        $bloodPressure = $request->input('e_blood_pressure');
        $bloodSugar = $request->input('e_blood_sugar');
        $weight = $request->input('e_weight');
        $trimester = $request->input('e_trimester');
        $healthStatus = $request->input('e_health_status');
        $additionalNotes = $request->input('e_notes');

        $errors = $this->maternalrecordService->validateMaternalRecordData($visitdate, $bmi, $bloodPressure, $bloodSugar, $weight, $trimester, $healthStatus,$additionalNotes,true);
        if (count($errors) !== 0) {
            return redirect(route("phm.maternal.health", ["id" => $id]))
                ->withInput([
                    "e_visit_date" => $visitdate,
                    "e_bmi" => $bmi,
                    "e_blood_pressure" => $bloodPressure,
                    "e_blood_sugar" => $bloodSugar,
                    "e_weight" => $weight,
                    "e_trimester" => $trimester,
                    "e_health_status" => $healthStatus,
                    "e_notes" => $additionalNotes,
                ])
                ->withErrors($errors)
                ->with("edit", $maternalRecordId);
        }

        $this->maternalrecordService->editMaternalRecord($maternalRecordId, $visitdate, $bmi, $bloodPressure, $bloodSugar, $weight, $trimester, $healthStatus, $additionalNotes);
        return redirect(route("phm.maternal.health", ["id" => $id]))
            ->withMessage(
                "Health record was successfully updated",
                "Health Record Updated",
                "success",
            );
    }
}
