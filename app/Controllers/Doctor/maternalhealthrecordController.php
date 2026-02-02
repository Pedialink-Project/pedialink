<?php

namespace App\Controllers\Doctor;
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
        return view("doctor/maternalhealth", [
            "items" => $maternalrecords,
            "parentId" => $id,
            ]);
    }

    public function createMaternalRecord(Request $request, int $id)
    {
        $parent = ParentM::find($id);

    if (!$parent) {
        return redirect(route("doctor.maternal.health", ["id" => $id]))
            ->withErrors([
                'parent' => 'Invalid parent ID'
            ]);
    }
        $visitdate = $request->input('visit_date');
        $height = $request->input('height');
        $systolic = $request->input('systolic');
        $diastolic = $request->input('diastolic');
        $bloodPressure = !empty($systolic) && !empty($diastolic) ? $systolic . '/' . $diastolic : '';
        $bloodSugar = $request->input('blood_sugar');
        $weight = $request->input('weight');
        $trimester = $request->input('trimester');
        $additionalNotes = $request->input('notes');

        $errors = $this->maternalrecordService->validateMaternalRecordData($visitdate, $height, $bloodPressure, $bloodSugar, $weight, $trimester, '', $additionalNotes);
        
        // Auto-determine health status based on vital signs
        if (count($errors) === 0) {
            $bmi = $this->maternalrecordService->calculateBMI($weight, $height);
            $healthStatus = $this->maternalrecordService->determineHealthStatus($bloodSugar, $bloodPressure, $bmi);
        } else {
            $healthStatus = '';
        }

        if (count($errors) !== 0) {
            return redirect(route("doctor.maternal.health", ["id" => $id]))
                ->withInput([
                    "visit_date" => $visitdate,
                    "height" => $height,
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

        $this->maternalrecordService->createMaternalRecord($id, $visitdate, $height, $bloodPressure, $bloodSugar,$weight, $trimester, $healthStatus, $additionalNotes);
        return redirect(route("doctor.maternal.health", ["id" => $id]))
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
        $height = $request->input('e_height');
        $systolic = $request->input('e_systolic');
        $diastolic = $request->input('e_diastolic');
        $bloodPressure = !empty($systolic) && !empty($diastolic) ? $systolic . '/' . $diastolic : '';
        $bloodSugar = $request->input('e_blood_sugar');
        $weight = $request->input('e_weight');
        $trimester = $request->input('e_trimester');
        $additionalNotes = $request->input('e_notes');

        $errors = $this->maternalrecordService->validateMaternalRecordData($visitdate, $height, $bloodPressure, $bloodSugar, $weight, $trimester, '',$additionalNotes,true);
        
        // Auto-determine health status based on vital signs
        if (count($errors) === 0) {
            $bmi = $this->maternalrecordService->calculateBMI($weight, $height);
            $healthStatus = $this->maternalrecordService->determineHealthStatus($bloodSugar, $bloodPressure, $bmi);
        } else {
            $healthStatus = '';
        }
        if (count($errors) !== 0) {
            return redirect(route("doctor.maternal.health", ["id" => $id]))
                ->withInput([
                    "e_visit_date" => $visitdate,
                    "e_height" => $height,
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

        $this->maternalrecordService->editMaternalRecord($maternalRecordId, $visitdate, $height, $bloodPressure, $bloodSugar, $weight, $trimester, $healthStatus, $additionalNotes);
        return redirect(route("doctor.maternal.health", ["id" => $id]))
            ->withMessage(
                "Health record was successfully updated",
                "Health Record Updated",
                "success",
            );
    }

    public function markInvalid(Request $request, int $id, int|string $recordId)
    {
        $this->maternalrecordService->markAsInvalid($recordId);

        return redirect(route("doctor.maternal.health", ["id" => $id]))
            ->withMessage(
                "Health record was marked as invalid",
                "Health Record Updated",
                "error",
            );
    }
}