<?php

namespace App\Controllers\Doctor;
use App\Services\MaternalRecordService;
use Library\Framework\Http\Request;
use App\Models\ParentM;


class MaternalHealthController
{
    protected $maternalRecordService;

    public function __construct()
    {
        $this->maternalRecordService = new MaternalRecordService();
    }

    public function index(Request $request, int $id)
    {

        $search = $request->input("search");
        $filters = $request->input("filters");
        [$records, $links] = $this->maternalRecordService->getMaternalRecordsByMaternalId($id, $search, $filters);
        $name = $this->maternalRecordService->getMaternalNameByMaternalId($id);

        return view("doctor/maternalhealth", [
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
            return redirect(route("doctor.maternal.health", ["id" => $id]))
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

        return redirect(route("doctor.maternal.health", ["id" => $id]))
            ->withMessage("Health record added successfully.", "Success", "success");
    }

}