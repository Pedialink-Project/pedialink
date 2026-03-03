<?php

namespace App\Services;

use App\Helpers\Calculator;
use App\Models\MaternalStat;
use App\Models\Maternal;
use App\Models\ParentM;
use App\Models\MaternalRecord;
use App\Models\Pregnancy;
use App\Models\User;

class MaternalRecordService
{
    public function getAllMaternalRecords()
    {
        $maternalRecords = MaternalRecord::all();

        $resource = [];
        foreach ($maternalRecords as $record) {
            $resource[] = [
                'id' => $record->id,
                'maternal_id' => $record->maternal_id,
                'parent_id' => $record->parent_id,
                'staff_id' => $record->staff_id,
                'visit_date' => $record->visit_date,
                'trimester' => $record->trimester,
                'bmi' => $record->bmi,
                'weight' => $record->weight,
                'height' => $record->height,
                'blood_sugar' => $record->blood_sugar,
                'blood_pressure' => $record->blood_pressure,
                'health_status' => $record->health_status,
                'fundal_height' => $record->fundal_height,
                'notes' => json_decode($record->notes),
            ];
        }


        return $resource;
    }





    public function getMaternalRecordsByMaternalId(
        int $maternalId,
        ?string $search = null,
        ?array $filters = null
    ): array {

        $parentId = Maternal::find($maternalId)->parent_id;
        $recordsQuery = MaternalRecord::query()
            ->where('parent_id', '=', $parentId);

        if ($search) {
            $recordsQuery->where('notes', 'ILIKE', "%{$search}%");
        }
        if (!empty($filters['health_status'])) {
            $recordsQuery->whereIn('health_status', $filters['health_status']);
        }


        $results = $recordsQuery
            ->orderBy('visit_date', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate(7)
            ->toArray();

        $resource = [];

        foreach ($results['items'] as $record) {

            if ($record->mark_as_invalid) {
                continue;
            }

            $resource[] = [
                'id' => $record->id,
                'maternal_id' => $record->maternal_id,
                'parent_id' => $record->parent_id,
                'age_recorded-at' => Calculator::calculateAgeInMonths(ParentM::find($record->parent_id)->date_of_birth, $record->visit_date),
                'staff_id' => $record->staff_id,
                'staff' => [
                    'id' => $record->staff_id,
                    'name' => User::find($record->staff_id)->name,
                    'role' => User::find($record->staff_id)->role,
                ],
                'visit_date' => $record->visit_date,
                'trimester' => $record->trimester,
                'bmi' => $record->bmi,
                'weight' => $record->weight,
                'height' => $record->height,
                'blood_sugar' => $record->blood_sugar,
                'blood_pressure' => $record->blood_pressure,
                'health_status' => $record->health_status,
                'fundal_height' => $record->fundal_height,
                'notes' => json_decode($record->notes),
            ];
        }


        $links = array_diff_key($results, ['items' => true]);


        return [$resource, $links];
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



    public function validateMaternalStatData($recordedAt, $bmi, $bloodPressure, $bloodSugar, $weight, $height, $fundalHeight, $healthStatus, $prenacyStage, $edit = false)
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

        $weightError = $this->validateNumericStat($weight, "Weight");
        if ($weightError) {
            $errors["{$errorSuffix}weight"] = $weightError;
        }

        $heightError = $this->validateNumericStat($height, "Height");
        if ($heightError) {
            $errors["{$errorSuffix}height"] = $heightError;
        }

        $fundalHeightError = $this->validateNumericStat($fundalHeight, "Fundal Height");
        if ($fundalHeightError) {
            $errors["{$errorSuffix}fundal_height"] = $fundalHeightError;
        }

        $healthStatusError = $this->validateCommonFields($healthStatus, "Health Status");
        if ($healthStatusError) {
            $errors["{$errorSuffix}health_status"] = $healthStatusError;
        }

        $prenacyStageError = $this->validateCommonFields($prenacyStage, "Pregnancy Stage");
        if ($prenacyStageError) {
            $errors["{$errorSuffix}pregnancy_stage"] = $prenacyStageError;
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
    public function addHealthRecord(
        $maternalId,
        $staffId,
        $visitDate,
        $bloodPressure,
        $weight,
        $hemoglobin,
        $glucose,
        $fetalHeartRate,
        $fundalHeight,
        $notes
    ) {

        $parentId = Maternal::find($maternalId)->parent_id;

        $height = Maternal::find($maternalId)->height;

        $bmi = Calculator::calculateBMI($height, $weight);

        $lmp = Pregnancy::find($maternalId)->where('maternal_id', $maternalId)->first()->lmp;


        $gestationWeeks = Calculator::calculateGestationWeeks($lmp);

        $trimester = Calculator::calculateTrimester($gestationWeeks);


        $healthStatus = Calculator::calculateMaternalHealthStatus($hemoglobin, $glucose, $bloodPressure);

        $record = new MaternalRecord();

        $record->parent_id = $parentId;
        $record->staff_id = $staffId;
        $record->visit_date = $visitDate;
        $record->trimester = $trimester;
        $record->health_status = $healthStatus;
        $record->weight = $weight;
        $record->blood_pressure = $bloodPressure;
        $record->glucose = $glucose;
        $record->hemoglobin = $hemoglobin;
        $record->fetal_heart_rate = $fetalHeartRate;
        $record->fundal_height = $fundalHeight;

        $record->bmi = $bmi;
        $record->notes = $notes;


        $record->save();

        return $record;
    }

    public function editMaternalStat($id, $recordedAt, $bmi, $bloodPressure, $bloodSugar, $weight, $height, $fundalHeight, $healthStatus, $prenacyStage, $notes)
    {
        $maternalStat = MaternalStat::find($id);

        if (!$maternalStat) {
            throw new \Exception("MaternalStat not found");
        }

        $maternalStat->visit_date = $recordedAt;
        $maternalStat->bmi = $bmi;
        $maternalStat->blood_pressure = $bloodPressure;
        $maternalStat->blood_sugar = $bloodSugar;
        $maternalStat->weight = $weight;
        $maternalStat->height = $height;
        $maternalStat->fundal_height = $fundalHeight;
        $maternalStat->health_status = $healthStatus;
        $maternalStat->trimester = $prenacyStage;
        $maternalStat->notes = $this->formatNotes($notes);

        $maternalStat->save();

        return $maternalStat;
    }

    public function deleteMaternalStat($id)
    {
        $maternalStat = MaternalStat::find($id);

        if (!$maternalStat) {
            throw new \Exception("MaternalStat not found");
        }

        $maternalStat->delete();
    }
}
