<?php

namespace App\Services;
use App\Models\MaternalRecord;
class maternalrecordService
{
    public function getAllMaternalRecords()
    {
        $maternalrecords = MaternalRecord::all();

        $resource = [];
        foreach ($maternalrecords as $record) {
            $resource[] = [
                'parent_id' => $record->parent_id,
                'visit_date' => $record->visit_date,
                'bmi' => $record->bmi,
                'blood_sugar' => $record->blood_sugar,
                'blood_pressure' => $record->blood_pressure,
                'health_status' => $record->health_status,
            ];
        }


        return $resource;
    }



    public function getMaternalRecordByMaternalId($id)
    {
        $maternalrecords = MaternalRecord::query()->where('parent_id', '=', $id)->get();
        $resource = [];
        foreach ($maternalrecords as $record) {
            $resource[] = [
                'parent_id' => $record->parent_id,
                'visit_date' => $record->visit_date,
                'bmi' => $record->bmi,
                'blood_sugar' => $record->blood_sugar,
                'blood_pressure' => $record->blood_pressure,
                'health_status' => $record->health_status,
            ];
        }

        return $resource;
    }

    public function validateNumericStat($data, $attributeName)
    {

        $error = null;
        if (!Validator::validateFieldExistence($data)) {
            $error = "$attributeName can not be empty";
            return $error;
        }

        if (!is_numeric($data)) {
            $error = "$attributeName must be a valid number";
            return $error;
        }

        if (intval($data) < 0) {
            $error = "$attributeName cannot be negative";
            return $error;
        }

        if (strlen(explode('.', $data, 2)[0]) > 3) {
            $error = "$attributeName is too large";
            return $error;
        }

        return $error;

    }

    public function validateCommonFields($data, $attributeName)
    {
        $error = null;
        if (!Validator::validateFieldExistence($data)) {
            $error = "$attributeName can not be empty";
            return $error;
        }

        return $error;
    }

    public function validateDate($date)
{
    $error = null;

    // FIRST: check for null or empty
    if ($date === null || trim($date) === '') {
        return "Visit date cannot be empty";
    }

    // THEN: safe to call validator
    if (!Validator::validateFieldExistence((string)$date)) {
        return "Visit date cannot be empty";
    }

    return $error;
}




    public function validateMaternalRecordData($visitdate, $bmi, $bloodPressure, $bloodSugar, $healthStatus, $edit = false)
    {
        $errorSuffix = '';
        if ($edit) {
            $errorSuffix = 'e_';
        }
        $errors = [];

        $recordedAtError = $this->validateDate($visitdate);
        if ($recordedAtError) {
            $errors["{$errorSuffix}recorded_at"] = $recordedAtError;
        }

        $bmiError = $this->validateNumericStat($bmi, "BMI");
        if ($bmiError) {
            $errors["{$errorSuffix}bmi"] = $bmiError;
        }

        $bloodPressureError = $this->validateNumericStat($bloodPressure, "Blood Pressure");
        if ($bloodPressureError) {
            $errors["{$errorSuffix}blood_pressure"] = $bloodPressureError;
        }

        $bloodSugarError = $this->validateNumericStat($bloodSugar, "Blood Sugar");
        if ($bloodSugarError) {
            $errors["{$errorSuffix}blood_sugar"] = $bloodSugarError;
        }


        $healthStatusError = $this->validateCommonFields($healthStatus, "Health Status");
        if ($healthStatusError) {
            $errors["{$errorSuffix}health_status"] = $healthStatusError;
        }


        return $errors;
    }

    private function formatNotes(string $notes)
    {
        // Split the string by new lines (\r\n, \r, or \n)
        $lines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $notes)));

        $notesArray = array_map(function ($line) {
            return ['note' => $line];
        }, $lines);

        return json_encode($notesArray, JSON_UNESCAPED_UNICODE);
    }

    public function createMaternalRecord($parentId,$visitdate, $bmi, $bloodPressure, $bloodSugar,$healthStatus,){


        
        $maternalrecord = new MaternalRecord();
        $maternalrecord->parent_id = $parentId;
        $maternalrecord->visit_date = $visitdate;
        $maternalrecord->bmi = $bmi;
        $maternalrecord->blood_pressure = $bloodPressure;
        $maternalrecord->blood_sugar = $bloodSugar;
        $maternalrecord->health_status = $healthStatus;

        $maternalrecord->save();

        return $maternalrecord;
    }

    public function editMaternalRecord($id, $recordedAt, $bmi, $bloodPressure, $bloodSugar,$healthStatus){
        $maternalrecord = MaternalRecord::find($id);

        if (!$maternalrecord) {
            throw new \Exception("MaternalStat not found");
        }

        $maternalrecord->visit_date = $recordedAt;
        $maternalrecord->bmi = $bmi;
        $maternalrecord->blood_pressure = $bloodPressure;
        $maternalrecord->blood_sugar = $bloodSugar;
        $maternalrecord->health_status = $healthStatus;

        $maternalrecord->save();

        return $maternalrecord;
    }

    public function deleteMaternalRecord($id){
        $maternalrecord = MaternalRecord::find($id);

        if (!$maternalrecord) {
            throw new \Exception("MaternalStat not found");
        }

        $maternalrecord->delete();
    }



}