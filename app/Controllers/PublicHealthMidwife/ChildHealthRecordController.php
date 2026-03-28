<?php

namespace App\Controllers\PublicHealthMidwife;
use App\Services\ChildRecordService;
use Library\Framework\Http\Request;
// use App\Models\ChildRecord;
use App\Models\Child;


class ChildHealthRecordController
{
    protected $ChildRecordService;

    public function __construct()
    {
        $this->ChildRecordService = new ChildRecordService();
    }

    public function index(Request $request, int $id)
    {

        $childrecords = $this->ChildRecordService->getChildRecordByChildId($id);
        return view("phm/childhealth", [
            "items" => $childrecords,
            "childId" => $id,
            ]);
    }

    public function createChildRecord(Request $request, int $id)
    {
        $child = Child::find($id);

    if (!$child) {
        return redirect(route("phm.child.health", ["id" => $id]))
            ->withErrors([
                'child' => 'Invalid child ID'
            ]);
    }
        $visitdate = $request->input('visit_date');
        $height = $request->input('height');
        // $systolic = $request->input('systolic');
        // $diastolic = $request->input('diastolic');
        // $bloodPressure = !empty($systolic) && !empty($diastolic) ? $systolic . '/' . $diastolic : '';
        // $bloodSugar = $request->input('blood_sugar');
        $headCircumference = $request->input('head_circumference');
        $weight = $request->input('weight');
        $trimester = $request->input('trimester');
        $additionalNotes = $request->input('notes');

        $ageInMonths = $this->ChildRecordService->calculateAgeInMonths($child->date_of_birth);

        $errors = $this->ChildRecordService->validateChildRecordData(
            $visitdate,
            $height,
            $headCircumference,
            '',
            $weight,
            '',
            '',
            $additionalNotes,
            false,
            $ageInMonths
        );
        
        if (count($errors) !== 0) {
            return redirect(route("phm.child.health.records", ["id" => $id]))
                ->withInput([
                    "visit_date" => $visitdate,
                    "height" => $height,
                    "weight" => $weight,
                    "head_circumference" => $headCircumference,
                    "notes" => $additionalNotes,
                ])
                ->withErrors($errors)
                ->with("create", true);
        }
        
        // Auto-determine health status based on vital signs
        $healthStatus = 'Good';

        $this->ChildRecordService->createChildRecord($id, $visitdate, $height, $headCircumference, $weight, $healthStatus, $additionalNotes, $ageInMonths);
        return redirect(route("phm.child.health", ["id" => $id]))
            ->withMessage(
                "Health record was successfully created",
                "Health Record Created",
                "success",
            );

    }


    // public function editChildRecord(Request $request,int $id,int|string $recordId)
    // {   
        
    //     $childRecordId = $recordId;
    //     $childRecord = ChildRecord::find($childRecordId);
    //     if (!$childRecord) {
    //         return redirect(route("phm.child.health", ["id" => $id]))->withErrors(['error' => 'Record not found']);
    //     }
        
    //     $child = Child::find($childRecord->child_id);
    //     if (!$child) {
    //         return redirect(route("phm.child.health", ["id" => $id]))->withErrors(['error' => 'Child not found']);
    //     }
        
    //     $visitdate = $request->input('e_visit_date');
    //     $height = $request->input('e_height');
    //     $headCircumference = $request->input('e_head_circumference');
    //     $weight = $request->input('e_weight');
    //     $additionalNotes = $request->input('e_notes');

    //     $errors = $this->ChildRecordService->validateChildRecordData($visitdate, $height, '', '', $weight, '', '', $additionalNotes, true);
        
    //     if (count($errors) !== 0) {
    //         return redirect(route("phm.child.health.records", ["id" => $id]))
    //             ->withInput([
    //                 "e_visit_date" => $visitdate,
    //                 "e_height" => $height,
    //                 "e_head_circumference" => $headCircumference,
    //                 "e_weight" => $weight,
    //                 "e_notes" => $additionalNotes,
    //             ])
    //             ->withErrors($errors)
    //             ->with("edit", $childRecordId);
    //     }
        
    //     // Calculate child's age in months
    //     $ageInMonths = $this->ChildRecordService->calculateAgeInMonths($child->date_of_birth);
        
    //     // Auto-determine health status based on vital signs
    //     $healthStatus = 'Good';

    //     $this->ChildRecordService->editChildRecord($childRecordId, $visitdate, $height, $headCircumference, $weight, $healthStatus, $additionalNotes, $ageInMonths);
    //     return redirect(route("phm.child.health.records", ["id" => $id]))
    //         ->withMessage(
    //             "Health record was successfully updated",
    //             "Health Record Updated",
    //             "success",
    //         );
    // }

    // public function markInvalid(Request $request, int $id, int|string $recordId)
    // {
    //     $this->ChildRecordService->markAsInvalid($recordId);
    //     return redirect(route("phm.maternal.health", ["id" => $id]))
    //         ->withMessage(
    //             "Health record was marked as invalid",
    //             "Health Record Updated",
    //             "error",
    //         );
    // }
}