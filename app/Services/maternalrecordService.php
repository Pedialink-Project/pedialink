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
                'health_status' => $record->health_status,
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
                'health_status' => $record->health_status,
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
            ->where('health_status', '!=', 'invalid')
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
            'health_status' => $maternalRecord->health_status ?? '-',
            'notes' => $maternalRecord->notes ? json_decode($maternalRecord->notes) : null,
        ];
    }

    /**
     * Analyze maternal health status grouped by age group based on LATEST health record per maternal
     * Returns counts for all statuses (good, bad, critical) by age groups: <20, 20-24, 25-29, 30-34, 35+
     */
    public function getAntenatalRiskByAgeGroup()
    {
        $ageGroups = [
            'under_20' => ['label' => '<20 years', 'min' => 0, 'max' => 19, 'good' => 0, 'bad' => 0, 'critical' => 0],
            '20_24' => ['label' => '20-24 years', 'min' => 20, 'max' => 24, 'good' => 0, 'bad' => 0, 'critical' => 0],
            '25_29' => ['label' => '25-29 years', 'min' => 25, 'max' => 29, 'good' => 0, 'bad' => 0, 'critical' => 0],
            '30_34' => ['label' => '30-34 years', 'min' => 30, 'max' => 34, 'good' => 0, 'bad' => 0, 'critical' => 0],
            'over_35' => ['label' => '35+ years', 'min' => 35, 'max' => 150, 'good' => 0, 'bad' => 0, 'critical' => 0],
        ];

        // Get all maternal profiles
        $maternalProfiles = \App\Models\ParentM::all();

        foreach ($maternalProfiles as $parent) {
            // Get the latest health record for this maternal
            $latestRecord = MaternalRecord::query()
                ->where('parent_id', '=', $parent->id)
                ->where('health_status', '!=', 'invalid')
                ->orderBy('visit_date', 'DESC')
                ->first();

            if (!$latestRecord) {
                continue;
            }

            // Count ALL statuses (good, bad, critical)
            $dob = $parent->date_of_birth ?? null;

            if (empty($dob) && !empty($parent->nic)) {
                $extractor = new \App\Helpers\NicExtractor($parent->nic);
                $nicData = $extractor->getExtractedNic();
                if (!empty($nicData['dob'])) {
                    $dob = $nicData['dob'];
                }
            }

            if (!empty($dob)) {
                try {
                    // Calculate age
                    $dateOfBirth = new \DateTime($dob);
                    $today = new \DateTime();
                    $age = $today->diff($dateOfBirth)->y;

                    // Increment the appropriate age group based on status
                    foreach ($ageGroups as $key => $group) {
                        if ($age >= $group['min'] && $age <= $group['max']) {
                            $status = strtolower($latestRecord->health_status ?? 'good');
                            if ($status === 'critical') {
                                $ageGroups[$key]['critical']++;
                            } elseif ($status === 'bad') {
                                $ageGroups[$key]['bad']++;
                            } else {
                                $ageGroups[$key]['good']++;
                            }
                            break;
                        }
                    }
                } catch (\Exception $e) {
                    // Skip if date parsing fails
                    continue;
                }
            }
        }

        // Return counts for all statuses per age group
        return [
            'good' => [
                $ageGroups['under_20']['good'],
                $ageGroups['20_24']['good'],
                $ageGroups['25_29']['good'],
                $ageGroups['30_34']['good'],
                $ageGroups['over_35']['good'],
            ],
            'bad' => [
                $ageGroups['under_20']['bad'],
                $ageGroups['20_24']['bad'],
                $ageGroups['25_29']['bad'],
                $ageGroups['30_34']['bad'],
                $ageGroups['over_35']['bad'],
            ],
            'critical' => [
                $ageGroups['under_20']['critical'],
                $ageGroups['20_24']['critical'],
                $ageGroups['25_29']['critical'],
                $ageGroups['30_34']['critical'],
                $ageGroups['over_35']['critical'],
            ],
        ];
    }

    /**
     * Get comprehensive antenatal risk analysis based on LATEST health record per maternal
     * Includes labels and counts for chart display
     */
    public function getAntenatalRiskAnalysis()
    {
        $ageGroups = [
            'under_20' => ['label' => '<20 years', 'min' => 0, 'max' => 19, 'count' => 0, 'risk_cases' => []],
            '20_24' => ['label' => '20-24 years', 'min' => 20, 'max' => 24, 'count' => 0, 'risk_cases' => []],
            '25_29' => ['label' => '25-29 years', 'min' => 25, 'max' => 29, 'count' => 0, 'risk_cases' => []],
            '30_34' => ['label' => '30-34 years', 'min' => 30, 'max' => 34, 'count' => 0, 'risk_cases' => []],
            'over_35' => ['label' => '35+ years', 'min' => 35, 'max' => 150, 'count' => 0, 'risk_cases' => []],
        ];

        // Get all maternal profiles
        $maternalProfiles = \App\Models\ParentM::all();

        foreach ($maternalProfiles as $parent) {
            // Get the latest health record for this maternal
            $latestRecord = MaternalRecord::query()
                ->where('parent_id', '=', $parent->id)
                ->where('health_status', '!=', 'invalid')
                ->orderBy('visit_date', 'DESC')
                ->first();

            if (!$latestRecord) {
                continue;
            }

            $dob = $parent->date_of_birth ?? null;

            if (empty($dob) && !empty($parent->nic)) {
                $extractor = new \App\Helpers\NicExtractor($parent->nic);
                $nicData = $extractor->getExtractedNic();
                if (!empty($nicData['dob'])) {
                    $dob = $nicData['dob'];
                }
            }

            if (!empty($dob)) {
                try {
                    // Calculate age
                    $dateOfBirth = new \DateTime($dob);
                    $today = new \DateTime();
                    $age = $today->diff($dateOfBirth)->y;

                    // Categorize by age group
                    foreach ($ageGroups as $key => $group) {
                        if ($age >= $group['min'] && $age <= $group['max']) {
                            // Only count "bad" and "critical" as risk cases
                            if ($latestRecord->health_status === 'bad' || $latestRecord->health_status === 'critical') {
                                $ageGroups[$key]['count']++;
                                $ageGroups[$key]['risk_cases'][] = [
                                    'name' => $parent->name ?? 'Unknown',
                                    'age' => $age,
                                    'health_status' => $latestRecord->health_status,
                                    'visit_date' => $latestRecord->visit_date,
                                    'bmi' => $latestRecord->bmi,
                                    'blood_pressure' => $latestRecord->blood_pressure,
                                    'blood_sugar' => $latestRecord->blood_sugar,
                                ];
                            }
                            break;
                        }
                    }
                } catch (\Exception $e) {
                    // Skip if date parsing fails
                    continue;
                }
            }
        }

        return $ageGroups;
    }
}