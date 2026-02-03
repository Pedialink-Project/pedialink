<?php

namespace App\Services;
use App\Models\MaternalRecord;
use App\Helpers\Validator;

class maternalrecordService
{
    public function getAllMaternalRecords()
    {
        $maternalrecords = MaternalRecord::all();

        $resource = [];
        foreach ($maternalrecords as $record) {
            $resource[] = [
                'id' => $record->id,
                'parent_id' => $record->parent_id,
                'visit_date' => $record->visit_date,
                'height' => $record->height,
                'bmi' => $record->bmi,
                'blood_sugar' => $record->blood_sugar,
                'blood_pressure' => $record->blood_pressure,
                'weight' => $record->weight,
                'trimester' => $record->trimester,
                'health_status' => $this->determineHealthStatus($record->blood_sugar, $record->blood_pressure, $record->bmi),
                'notes' => $record->notes,
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
                'id' => $record->id,
                'parent_id' => $record->parent_id,
                'visit_date' => $record->visit_date,
                'height' => $record->height,
                'bmi' => $record->bmi,
                'blood_sugar' => $record->blood_sugar,
                'blood_pressure' => $record->blood_pressure,
                'weight' => $record->weight,
                'trimester' => $record->trimester,
                 'health_status' => $this->determineHealthStatus($record->blood_sugar, $record->blood_pressure, $record->bmi),
                'notes' => $record->notes,
            ];
        }
        return $resource;
    }

    public function calculateBMI($weight, $height)
    {
        // BMI = weight (kg) / (height (m))^2
        // Convert to float and validate
        $weight = (float)$weight;
        $height = (float)$height;
        
        // Return null if weight or height are invalid
        if ($weight <= 0 || $height <= 0) {
            return null;
        }
        
        $heightInMeters = $height / 100; // Convert cm to meters
        $bmi = $weight / ($heightInMeters * $heightInMeters);
        
        return round($bmi, 2);
    }

    public function determineHealthStatus($bloodSugar, $bloodPressure, $bmi)
    {
        /**
         * Determine health status based on vital signs
         * 
         * Blood Pressure format: "120/80" (systolic/diastolic)
         * Blood Sugar: in mg/dL
         * BMI: numeric value
         * 
         * Returns: 'critical', 'bad', or 'good'
         */
        
        $criticalCount = 0;
        $badCount = 0;
        
        // Parse blood pressure
        if (!empty($bloodPressure)) {
            $bpValues = explode('/', $bloodPressure);
            if (count($bpValues) === 2) {
                $systolic = (float)$bpValues[0];
                $diastolic = (float)$bpValues[1];
                
                // Critical: Systolic >= 160 or Diastolic >= 110 (Hypertensive Crisis)
                if ($systolic >= 160 || $diastolic >= 110) {
                    $criticalCount++;
                }
                // Bad: Systolic 140-159 or Diastolic 90-109 (Stage 2 Hypertension)
                elseif ($systolic >= 140 || $diastolic >= 90) {
                    $badCount++;
                }
            }
        }
        
        // Check blood sugar
        if (!empty($bloodSugar)) {
            $sugar = (float)$bloodSugar;
            
            // Critical: > 300 mg/dL (Severe Hyperglycemia)
            if ($sugar > 300) {
                $criticalCount++;
            }
            // Bad: 150-300 mg/dL (Moderate Hyperglycemia) or < 70 mg/dL (Hypoglycemia)
            elseif (($sugar >= 150 && $sugar <= 300) || $sugar < 70) {
                $badCount++;
            }
        }
        
        // Check BMI
        if (!empty($bmi)) {
            $bmiValue = (float)$bmi;
            
            // Critical: BMI >= 35 (Severe Obesity) or < 16 (Severe Underweight)
            if ($bmiValue >= 35 || $bmiValue < 16) {
                $criticalCount++;
            }
            // Bad: BMI 30-34.9 (Obesity) or 16-18.4 (Underweight)
            elseif (($bmiValue >= 30 && $bmiValue < 35) || ($bmiValue >= 16 && $bmiValue < 18.5)) {
                $badCount++;
            }
        }
        
        // Determine overall health status
        if ($criticalCount > 0) {
            return 'critical';
        } elseif ($badCount > 0) {
            return 'bad';
        } else {
            return 'good';
        }
    }

    public function validateNumericStat($data, $attributeName)
    {

        $error = null;
        if ($data === null || trim((string)$data) === '') {
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
        if ($data === null || trim((string)$data) === '') {
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




    public function validateMaternalRecordData($visitdate, $height, $bloodPressure, $bloodSugar,$weight,$trimester, $healthStatus,$additionalNotes, $edit = false)
    {
        $errorSuffix = '';
        if ($edit) {
            $errorSuffix = 'e_';
        }
        $errors = [];

        $visitDateError = $this->validateDate($visitdate);
        if ($visitDateError) {
            $errors["{$errorSuffix}visit_date"] = $visitDateError;
        }

        $heightError = $this->validateNumericStat($height, "Height");
        if ($heightError) {
            $errors["{$errorSuffix}height"] = $heightError;
        }

        $bloodPressureError = $this->validateCommonFields($bloodPressure, "Blood Pressure");
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

        $trimesterError = $this->validateCommonFields($trimester, "Trimester");
        if ($trimesterError) {
            $errors["{$errorSuffix}trimester"] = $trimesterError;
        }

        // Health status is now auto-calculated, so no validation needed
        // Notes are optional, no validation needed

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

    public function createMaternalRecord($parentId,$visitdate, $height, $bloodPressure, $bloodSugar,$weight,$trimester,$healthStatus,$additionalNotes){

        // Calculate BMI from height and weight
        $bmi = $this->calculateBMI($weight, $height);
        
        $maternalrecord = new MaternalRecord();
        $maternalrecord->parent_id = $parentId;
        $maternalrecord->visit_date = $visitdate;
        $maternalrecord->height = $height;
        $maternalrecord->bmi = $bmi;
        $maternalrecord->blood_pressure = $bloodPressure;
        $maternalrecord->blood_sugar = $bloodSugar;
        $maternalrecord->weight = $weight;
        $maternalrecord->trimester = $trimester;
        $maternalrecord->health_status = $healthStatus;
        $maternalrecord->notes = $additionalNotes;

        $maternalrecord->save();

        return $maternalrecord;
    }

    public function editMaternalRecord($recordId, $visitdate, $height, $bloodPressure, $bloodSugar,$weight,$trimester,$healthStatus,$additionalNotes){
        $maternalrecord = MaternalRecord::find($recordId);

        if (!$maternalrecord) {
            throw new \Exception("Maternal Record not found");
        }

        // Calculate BMI from height and weight
        $bmi = $this->calculateBMI($weight, $height);

        $maternalrecord->visit_date = $visitdate;
        $maternalrecord->height = $height;
        $maternalrecord->bmi = $bmi;
        $maternalrecord->blood_pressure = $bloodPressure;
        $maternalrecord->blood_sugar = $bloodSugar;
        $maternalrecord->weight = $weight;
        $maternalrecord->trimester = $trimester;
        $maternalrecord->health_status = $healthStatus;
        $maternalrecord->notes = $additionalNotes;

        $maternalrecord->save();

        return $maternalrecord;
    }

    public function markAsInvalid($recordId)
    {
        $maternalrecord = MaternalRecord::find($recordId);

        if (!$maternalrecord) {
            throw new \Exception("Maternal Record not found");
        }

        $maternalrecord->health_status = 'invalid';
        $maternalrecord->save();

        return $maternalrecord;
    }
    public function getLatestMaternalRecord($maternalId)
    {
        $maternalRecord = MaternalRecord::query()
            ->where('parent_id', '=', $maternalId)
            ->orderBy('visit_date', 'DESC')
            ->first();

        if (!$maternalRecord) {
            return null;
        }

        return [
            'id' => $maternalRecord->id,
            'maternal_id' => $maternalRecord->parent_id,
            'visit_date' => $maternalRecord->visit_date,
            'trimester' => $maternalRecord->trimester,
            'height' => $maternalRecord->height ?? '-',
            'bmi' => $maternalRecord->bmi ?? '-',
            'weight' => $maternalRecord->weight ?? '-',
            'blood_sugar' => $maternalRecord->blood_sugar ?? '-',
            'blood_pressure' => $maternalRecord->blood_pressure ?? '-',
                 'health_status' => $this->determineHealthStatus($maternalRecord->blood_sugar, $maternalRecord->blood_pressure, $maternalRecord->bmi),
            'notes' => $maternalRecord->notes ? json_decode($maternalRecord->notes) : null,
        ];
    }
}