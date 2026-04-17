<?php

namespace App\Controllers\Doctor;

use App\Models\Child;
use App\Services\VaccinationService;
use Library\Framework\Http\Request;
use App\Services\ChildRecordService;

class ChildHealthController
{
    private $childRecordService;
    private VaccinationService $vaccinationService;

    public function __construct()
    {
        $this->childRecordService = new ChildRecordService();
        $this->vaccinationService = new VaccinationService();
    }
    public function index(Request $request, int $id)
    {

        $search = $request->input("search");
        $filters = $request->input("filters");
        [$records, $links] = $this->childRecordService->getChildRecordsByChildId($id, $search, $filters);
        $name = $this->childRecordService->getChildNameById($id);

        return view("doctor/childhealth", [
            "id" => $id,
            'name' => $name,
            "records" => $records,
            "links" => $links
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
    

}