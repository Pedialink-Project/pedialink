<?php

namespace App\Controllers\PublicHealthMidwife;

use App\Services\MaternalRecordService;
use Library\Framework\Http\Request;

class MaternalHealthController
{
    private $maternalRecordService;

    public function __construct()
    {
        $this->maternalRecordService = new MaternalRecordService();
    }
    public function index(Request $request, int $id)
    {

        $search = $request->input("search");
        $filters = $request->input("filters");
        [$records, $links] = $this->maternalRecordService->getMaternalRecordsByMaternalId($id, $search, $filters);
        $name = 'gh';

        return view("phm/maternalhealth", [
            "id" => $id,
            'name' => $name,
            "records" => $records,
            "links" => $links
        ]);
    }

    public function addHealthRecord(Request $request, int $id)
    {
        $staffId = auth()->user()->id;
        $visitDate = $request->input('visit_date');
        $bloodPressure = $request->input('blood_pressure');
        $weight = $request->input('weight');
        $hemoglobin = $request->input('hemoglobin');
        $glucose = $request->input('glucose');
        $fetalHeartRate = $request->input('fetal_heart_rate');
        $fundalHeight = $request->input('fundal_height');
        $notes = $request->input('notes');

        $errors = $this->maternalRecordService->validateMaternalHealthData(
            $visitDate,
            $bloodPressure,
            $weight,
            $hemoglobin,
            $glucose,
            $fetalHeartRate,
            $fundalHeight
        );

        if (count($errors) !== 0) {
            return redirect(route("phm.maternal.health", ["id" => $id]))
                ->withInput([
                    "visit_date" => $visitDate,
                    "blood_pressure" => $bloodPressure,
                    "weight" => $weight,
                    "hemoglobin" => $hemoglobin,
                    "glucose" => $glucose,
                    "fetal_heart_rate" => $fetalHeartRate,
                    "fundal_height" => $fundalHeight,
                    "notes" => $notes
                ])
                ->withErrors($errors)
                ->with("add", true);
        }

        $this->maternalRecordService->addHealthRecord(
            $id,
            $staffId,
            $visitDate,
            $bloodPressure,
            $weight,
            $hemoglobin,
            $glucose,
            $fetalHeartRate,
            $fundalHeight,
            $notes
        );

        return redirect(route("phm.child.health", ["id" => $id]))
            ->withMessage("Health record added successfully.", "Success", "success");
    }

    public function editHealthRecord(Request $request, int $id, int $recordId)
    {
        // Edited values come from the edit form with `e_` prefixes
        $visitDate = $request->input('e_visit_date');
        $bloodPressure = $request->input('e_blood_pressure');
        $weight = $request->input('e_weight');
        $hemoglobin = $request->input('e_hemoglobin');
        $glucose = $request->input('e_glucose');
        $fetalHeartRate = $request->input('e_fetal_heart_rate');
        $fundalHeight = $request->input('e_fundal_height');

        $staffId = auth()->user()->id;


        $errors = $this->maternalRecordService->validateMaternalHealthData(
            $visitDate,
            $bloodPressure,
            $weight,
            $hemoglobin,
            $glucose,
            $fetalHeartRate,
            $fundalHeight,
            true
        );

        if (count($errors) > 0) {
            return redirect(route("phm.maternal.health", ["id" => $id]))
                ->withInput([
                    "e_weight" => $weight,
                    "e_blood_pressure" => $bloodPressure,
                    "e_hemoglobin" => $hemoglobin,
                    "e_glucose" => $glucose,
                    "e_fetal_heart_rate" => $fetalHeartRate,
                    "e_fundal_height" => $fundalHeight,
                    "e_visit_date" => $visitDate,
                ])
                ->withErrors($errors)
                ->with("edit", $recordId);
        }

        $error =   $this->maternalRecordService->editHealthRecord(
            $recordId,
            $staffId,
            $visitDate,
            $bloodPressure,
            $weight,
            $hemoglobin,
            $glucose,
            $fetalHeartRate,
            $fundalHeight,
        );

        if ($error) {
            return redirect(route("phm.maternal.health", ["id" => $id]))
                ->withMessage($error, "Error", "error");
        }

        return redirect(route("phm.maternal.health", ["id" => $id]))
            ->withMessage("Health record updated successfully.", "Success", "success");
    }
}
