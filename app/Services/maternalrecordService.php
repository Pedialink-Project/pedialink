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
                'id' => $record->id,
                'maternal_id' => $record->maternal_id,
                'recorded_at' => $record->recorded_at,
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
        $maternalrecords = MaternalRecord::query()->where('maternal_id', '=', $id)->get();
        $resource = [];
        foreach ($maternalrecords as $record) {
            $resource[] = [
                'id' => $record->id,
                'maternal_id' => $record->maternal_id,
                'recorded_at' => $record->recoded_at,
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

        if (!Validator::validateFieldExistence($date)) {
            $error = "Recorded At Date cannot be empty";
            return $error;
        }




        return $error;


    }



    public function validateMaternalRecordData($recordedAt, $bmi, $bloodPressure, $bloodSugar, $healthStatus, $edit = false)
    {
        $errorSuffix = '';
        if ($edit) {
            $errorSuffix = 'e_';
        }
        $errors = [];

        $recordedAtError = $this->validateDate($recordedAt);
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

    public function createMaternalStat($maternalId,$recordedAt, $bmi, $bloodPressure, $bloodSugar,$healthStatus,){


        
        $maternalrecord = new MaternalRecord();
        $maternalrecord->maternal_id = $maternalId;
        $maternalrecord->recorded_at = $recordedAt;
        $maternalrecord->bmi = $bmi;
        $maternalrecord->blood_pressure = $bloodPressure;
        $maternalrecord->blood_sugar = $bloodSugar;
        $maternalrecord->health_status = $healthStatus;

        $maternalrecord->save();

        return $maternalrecord;
    }

    public function editMaternalStat($id, $recordedAt, $bmi, $bloodPressure, $bloodSugar,$healthStatus){
        $maternalrecord = MaternalRecord::find($id);

        if (!$maternalrecord) {
            throw new \Exception("MaternalStat not found");
        }

        $maternalrecord->recoded_at = $recordedAt;
        $maternalrecord->bmi = $bmi;
        $maternalrecord->blood_pressure = $bloodPressure;
        $maternalrecord->blood_sugar = $bloodSugar;
        $maternalrecord->health_status = $healthStatus;

        $maternalrecord->save();

        return $maternalrecord;
    }

    public function deleteMaternalStat($id){
        $maternalrecord = MaternalRecord::find($id);

        if (!$maternalrecord) {
            throw new \Exception("MaternalStat not found");
        }

        $maternalrecord->delete();
    }



}