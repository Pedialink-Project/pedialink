<?php

namespace App\Services;
use App\Models\MaternalStat;
use App\Models\Maternal;
class MaternalStatService
{
    public function getAllMaternalStats()
    {
        $maternalStats = MaternalStat::all();

        $resource = [];
        foreach ($maternalStats as $stat) {
            $resource[] = [
                'id' => $stat->id,
                'maternal_id' => $stat->maternal_id,
                'visit_date' => $stat->visit_date,
                'trimester' => $stat->trimester,
                'bmi' => $stat->bmi,
                'weight' => $stat->weight,
                'height' => $stat->height,
                'blood_sugar' => $stat->blood_sugar,
                'blood_pressure' => $stat->blood_pressure,
                'health_status' => $stat->health_status,
                'fundal_height' => $stat->fundal_height,
                'notes' => json_decode($stat->notes),
            ];
        }


        return $resource;
    }



    public function getMaternalStatByMaternalId($id)
    {
        $maternalStats = MaternalStat::query()->where('maternal_id', '=', $id)->get();
        $healthStatus = Maternal::query()->where('id', '=', $id)->first()->health_status;
        $resource = [];
        foreach ($maternalStats as $stat) {
            $resource[] = [
                'id' => $stat->id,
                'maternal_id' => $stat->maternal_id,
                'visit_date' => $stat->visit_date,
                'trimester' => $stat->trimester,
                'bmi' => $stat->bmi,
                'weight' => $stat->weight,
                'height' => $stat->height,
                'blood_sugar' => $stat->blood_sugar,
                'blood_pressure' => $stat->blood_pressure,
                'health_status' => $healthStatus,
                'fundal_height' => $stat->fundal_height,
                'notes' => json_decode($stat->notes),
            ];
        }

        return $resource;
    }

    public function validateNumericStat($data, $attributeName)
    {

        $error = null;
        if (Validator::validateFieldExistence($data)) {
            $error = "$attributeName can not be empty";
            return $error;
        }

        if (!is_numeric($data)) {
            $error = "$attributeName must be a valid number";
            return $error;
        }

        return $error;

    }

    public function validateCommonFields($data, $attributeName)
    {
        $error = null;
        if (Validator::validateFieldExistence($data)) {
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



    public function validateMaternalStatData($recordedAt, $bmi, $bloodPressure, $bloodSugar, $weight, $height, $fundalHeight, $healthStatus, $prenacyStage, $notes)
    {
        $errors = [];

        $recordedAtError = $this->validateDate($recordedAt);
        if ($recordedAtError) {
            $errors["recordedAt"] = $recordedAtError;
        }

        $bmiError = $this->validateNumericStat($bmi, "BMI");
        if ($bmiError) {
            $errors["bmi"] = $bmiError;
        }

        $bloodPressureError = $this->validateNumericStat($bloodPressure, "Blood Pressure");
        if ($bloodPressureError) {
            $errors["bloodPressure"] = $bloodPressureError;
        }

        $bloodSugarError = $this->validateNumericStat($bloodSugar, "Blood Sugar");
        if ($bloodSugarError) {
            $errors["bloodSugar"] = $bloodSugarError;
        }

        $weightError = $this->validateNumericStat($weight, "Weight");
        if ($weightError) {
            $errors["weight"] = $weightError;
        }

        $heightError = $this->validateNumericStat($height, "Height");
        if ($heightError) {
            $errors["height"] = $heightError;
        }

        $fundalHeightError = $this->validateNumericStat($fundalHeight, "Fundal Height");
        if ($fundalHeightError) {
            $errors["fundalHeight"] = $fundalHeightError;
        }

        $healthStatusError = $this->validateCommonFields($healthStatus, "Health Status");
        if ($healthStatusError) {
            $errors["healthStatus"] = $healthStatusError;
        }

        $prenacyStageError = $this->validateCommonFields($prenacyStage, "Pregnancy Stage");
        if ($prenacyStageError) {
            $errors["prenacyStage"] = $prenacyStageError;
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

    public function createMaternalStat($maternalId,$recordedAt, $bmi, $bloodPressure, $bloodSugar, $weight, $height, $fundalHeight, $healthStatus, $prenacyStage, $notes){


        
        $maternalStat = new MaternalStat();
        $maternalStat->maternal_id = $maternalId;
        $maternalStat->recorded_at = $recordedAt;
        $maternalStat->bmi = $bmi;
        $maternalStat->blood_pressure = $bloodPressure;
        $maternalStat->blood_sugar = $bloodSugar;
        $maternalStat->weight = $weight;
        $maternalStat->height = $height;
        $maternalStat->fundal_height = $fundalHeight;
        $maternalStat->health_status = $healthStatus;
        $maternalStat->pregnancy_stage = $prenacyStage;
        $maternalStat->notes = $this->formatNotes($notes);

        $maternalStat->save();

        return $maternalStat;
    }

    public function editMaternalStat($id, $recordedAt, $bmi, $bloodPressure, $bloodSugar, $weight, $height, $fundalHeight, $healthStatus, $prenacyStage, $notes){
        $maternalStat = MaternalStat::find($id);

        if (!$maternalStat) {
            throw new \Exception("MaternalStat not found");
        }

        $maternalStat->recorded_at = $recordedAt;
        $maternalStat->bmi = $bmi;
        $maternalStat->blood_pressure = $bloodPressure;
        $maternalStat->blood_sugar = $bloodSugar;
        $maternalStat->weight = $weight;
        $maternalStat->height = $height;
        $maternalStat->fundal_height = $fundalHeight;
        $maternalStat->health_status = $healthStatus;
        $maternalStat->pregnancy_stage = $prenacyStage;
        $maternalStat->notes = $this->formatNotes($notes);

        $maternalStat->save();

        return $maternalStat;
    }

    public function deleteMaternalStat($id){
        $maternalStat = MaternalStat::find($id);

        if (!$maternalStat) {
            throw new \Exception("MaternalStat not found");
        }

        $maternalStat->delete();
    }



}