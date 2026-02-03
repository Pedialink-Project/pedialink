<?php

namespace App\Controllers\Doctor;

use Library\Framework\Http\Request;
use App\Services\ChildRecordService;

class ChildHealthController
{

    private $childRecordService;

    public function __construct()
    {
        $this->childRecordService = new ChildRecordService();
    }
    public function index(Request $request, int $id)
    {

    $search = $request->input("search");
        [$records, $links] = $this->childRecordService->getChildRecordsByChildId($id, $search);
        $name = $this->childRecordService->getChildNameById($id);


        return view("doctor/childhealth", [
            "id" => $id,
            'name'=>$name,
            "records" => $records,
            "links"=> $links
        ]);
    }

    public function addHealthRecord(Request $request, int $id)
    {
        $staffId = auth()->user()->id;

        $height = $request->input('height');
        $weight = $request->input('weight');
        $headCircumference = $request->input('head_circumference');
        $visitDate = $request->input('visit_date');
        $notes = $request->input('notes');
    

        $errors = $this->childRecordService->validateRecordData(
            $visitDate,
            $height,
            $weight,
            $headCircumference,
        );

        if (count($errors) !== 0) {
            return redirect(route("doctor.child.health", ["id" => $id]))
                ->withInput([
                    "height" => $height,
                    "weight" => $weight,
                    "head_circumference" => $headCircumference,
                    "visit_date" => $visitDate,
                    "notes" => $notes,
                ])
                ->withErrors($errors)
                ->with("add", true);
        }

        $this->childRecordService->addHealthRecord(
             $id,
        $staffId,
        $visitDate,
        $height,
        $weight,
        $headCircumference,
        $notes
        );

        return redirect(route("doctor.child.health", ["id" => $id]))
            ->withMessage("Health record added successfully.", "Success", "success");
    }

    public function editHealthRecord(Request $request, int $id, int $recordId)
    {
        $height = $request->input('e_height');
        $weight = $request->input('e_weight');
        $headCircumference = $request->input('e_head_circumference');
        $visitDate = $request->input('e_visit_date');

        $errors = $this->childRecordService->validateEditRecordData(
            $visitDate,
            $height,
            $weight,
            $headCircumference,
        );

        if (count($errors) > 0) {
            return redirect(route("doctor.child.health", ["id" => $id]))
                ->withInput([
                    "e_height" => $height,
                    "e_weight" => $weight,
                    "e_head_circumference" => $headCircumference,
                    "e_visit_date" => $visitDate,
                ])
                ->withErrors($errors)
                ->with("edit", $recordId);
        }

        $this->childRecordService->editHealthRecord(
            $recordId,
            $visitDate,
            $height,
            $weight,
            $headCircumference,
        );

        return redirect(route("doctor.child.health", ["id" => $id]))
            ->withMessage("Health record updated successfully.", "Success", "success");
    }


    public function vaccinationIndex(Request $request, int $id)
    {
        return view("doctor/vaccinationrecord", [
            "id" => $id,
        ]);
    }
}
