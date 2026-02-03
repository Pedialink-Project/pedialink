<?php

namespace App\Services;
use App\Models\ChildRecord;
use App\Helpers\Validator;

class ChildRecordService
{   


    public function getAllChildRecords()
    {
        $childrecords = ChildRecord::all();
        $resource = [];
        foreach ($childrecords as $record) {
            $resource[] = [
                'id' => $record->id,
                'child_id' => $record->child_id,
                'visit_date' => $record->visit_date,
                'height' => $record->height,
                'bmi' => $record->bmi,
                'head_circumference' => $record->head_circumference,
                'weight' => $record->weight,
                'health_status' => $record->health_status,
                'notes' => $record->notes,
            ];
        }


        return $resource;
    }



    public function getChildRecordByChildId($id)
    {
        $childrecords = ChildRecord::query()->where('child_id', '=', $id)->get();
        $resource = [];
        foreach ($childrecords as $record) {
            $resource[] = [
                'id' => $record->id,
                'child_id' => $record->child_id,
                'visit_date' => $record->visit_date,
                'height' => $record->height,
                'bmi' => $record->bmi,
                'head_circumference' => $record->head_circumference,
                'weight' => $record->weight,
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

    public function calculateAgeInMonths($dateOfBirth)
    {
        // Calculate age in months from date of birth
        $now = new \DateTime();
        $dob = new \DateTime($dateOfBirth);
        $interval = $now->diff($dob);
        
        // Calculate total months: years * 12 + months
        $totalMonths = ($interval->y * 12) + $interval->m;
        
        return $totalMonths;
    }


    public function determineChildHealthStatus($bmi, $ageInMonths, $weight, $height, $headCircumference = null)
    {
        /**
         * Determine child health status based on BMI, age, height, weight, and head circumference
         * For children, BMI interpretation varies by age
         * 
         * Returns: 'critical', 'bad', or 'good'
         */
        $criticalCount = 0;
        $badCount = 0;

        $age = !empty($ageInMonths) ? (int)$ageInMonths : null;

        // BMI checks (age-based)
        if (!empty($bmi) && $age !== null) {
            $bmiValue = (float)$bmi;

            if ($age <= 24) {
                if ($bmiValue < 12 || $bmiValue > 20) {
                    $criticalCount++;
                } elseif ($bmiValue < 13 || $bmiValue > 18) {
                    $badCount++;
                }
            } elseif ($age <= 60) {
                if ($bmiValue < 13 || $bmiValue > 19) {
                    $criticalCount++;
                } elseif ($bmiValue < 14 || $bmiValue > 17.5) {
                    $badCount++;
                }
            } else {
                if ($bmiValue < 14 || $bmiValue > 25) {
                    $criticalCount++;
                } elseif ($bmiValue < 15.5 || $bmiValue > 23) {
                    $badCount++;
                }
            }
        }

        // Weight checks (kg)
        if (!empty($weight) && $age !== null) {
            $w = (float)$weight;

            if ($age <= 24) {
                if ($w < 2.5 || $w > 18) {
                    $criticalCount++;
                } elseif ($w < 3 || $w > 16) {
                    $badCount++;
                }
            } elseif ($age <= 60) {
                if ($w < 8 || $w > 28) {
                    $criticalCount++;
                } elseif ($w < 9 || $w > 25) {
                    $badCount++;
                }
            } else {
                if ($w < 12 || $w > 50) {
                    $criticalCount++;
                } elseif ($w < 14 || $w > 45) {
                    $badCount++;
                }
            }
        }

        // Height checks (cm)
        if (!empty($height) && $age !== null) {
            $h = (float)$height;

            if ($age <= 24) {
                if ($h < 45 || $h > 100) {
                    $criticalCount++;
                } elseif ($h < 50 || $h > 95) {
                    $badCount++;
                }
            } elseif ($age <= 60) {
                if ($h < 70 || $h > 125) {
                    $criticalCount++;
                } elseif ($h < 75 || $h > 120) {
                    $badCount++;
                }
            } else {
                if ($h < 90 || $h > 180) {
                    $criticalCount++;
                } elseif ($h < 100 || $h > 170) {
                    $badCount++;
                }
            }
        }

        // Head circumference checks (cm)
        if (!empty($headCircumference) && $age !== null) {
            $hc = (float)$headCircumference;

            if ($age <= 24) {
                if ($hc < 32 || $hc > 52) {
                    $criticalCount++;
                } elseif ($hc < 34 || $hc > 50) {
                    $badCount++;
                }
            } elseif ($age <= 60) {
                if ($hc < 42 || $hc > 56) {
                    $criticalCount++;
                } elseif ($hc < 44 || $hc > 54) {
                    $badCount++;
                }
            } else {
                if ($hc < 46 || $hc > 60) {
                    $criticalCount++;
                } elseif ($hc < 48 || $hc > 58) {
                    $badCount++;
                }
            }
        }

        if ($criticalCount > 0) {
            return 'critical';
        }

        if ($badCount > 0) {
            return 'bad';
        }

        return 'good';
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




    public function validateChildRecordData($visitdate, $height, $head_circumference, $bloodSugar,$weight,$trimester, $healthStatus,$additionalNotes, $edit = false)
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

        $headCircumferenceError = $this->validateCommonFields($head_circumference, "Head Circumference");
        if ($headCircumferenceError) {
            $errors["{$errorSuffix}head_circumference"] = $headCircumferenceError;
        }

        // $bloodSugarError = $this->validateNumericStat($bloodSugar, "Blood Sugar");
        // if ($bloodSugarError) {
        //     $errors["{$errorSuffix}blood_sugar"] = $bloodSugarError;
        // }

        $weightError = $this->validateNumericStat($weight, "Weight");
        if ($weightError) {
            $errors["{$errorSuffix}weight"] = $weightError;
        }

        // $trimesterError = $this->validateCommonFields($trimester, "Trimester");
        // if ($trimesterError) {
        //     $errors["{$errorSuffix}trimester"] = $trimesterError;
        // }

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

    public function createChildRecord($childId, $visitdate, $height, $head_circumference,$weight,$healthStatus,$additionalNotes, $ageInMonths){

        // Calculate BMI from height and weight
        $bmi = $this->calculateBMI($weight, $height);
        
        // Determine health status based on BMI and age
        $calculatedHealthStatus = $this->determineChildHealthStatus($bmi, $ageInMonths, $weight, $height, $head_circumference);
        
        $childrecord = new ChildRecord();
        $childrecord->child_id = $childId;
        $childrecord->visit_date = $visitdate;
        $childrecord->age_recorded_at = $ageInMonths;
        $childrecord->height = $height;
        $childrecord->weight = $weight;
        $childrecord->bmi = $bmi;
        $childrecord->head_circumference = $head_circumference;
        $childrecord->health_status = $calculatedHealthStatus;
        $childrecord->notes = $additionalNotes;

        $childrecord->save();

        return $childrecord;
    }

    // public function editChildRecord($recordId, $visitdate, $height, $head_circumference,$weight,$healthStatus,$additionalNotes, $ageInMonths){
    //     $childrecord = ChildRecord::find($recordId);
    //     if (!$childrecord) {
    //         throw new \Exception("Child Record not found");
    //     }

    //     // Calculate BMI from height and weight
    //     $bmi = $this->calculateBMI($weight, $height);

    //     $childrecord->visit_date = $visitdate;
    //     $childrecord->height = $height;
    //     $childrecord->age_recorded_at = $ageInMonths;
    //     $childrecord->bmi = $bmi;
    //     $childrecord->head_circumference = $head_circumference;
    //     $childrecord->weight = $weight;
    //     $childrecord->health_status = $healthStatus;
    //     $childrecord->notes = $additionalNotes;

    //     $childrecord->save();

    //     return $childrecord;
    // }

    // public function markAsInvalid($recordId)
    // {
    //     $childrecord = ChildRecord::find($recordId);

    //     if (!$childrecord) {
    //         throw new \Exception("Child Record not found");
    //     }

    //     $childrecord->health_status = 'invalid';
    //     $childrecord->save();
    //     return $childrecord;
    // }
    // public function getLatestChildRecord($childId)
    // {
    //     $childRecord = ChildRecord::query()
    //         ->where('child_id', '=', $childId)
    //         ->where('health_status', '!=', 'invalid')
    //         ->orderBy('visit_date', 'DESC')
    //         ->first();

    //     if (!$childRecord) {
    //         return null;
    
    //     }

    //     return [
    //         'id' => $childRecord->id,
    //         'child_id' => $childRecord->child_id,
    //         'visit_date' => $childRecord->visit_date,
    //         'height' => $childRecord->height ?? '-',
    //         'bmi' => $childRecord->bmi ?? '-',
    //         'weight' => $childRecord->weight ?? '-',
    //         'head_circumference' => $childRecord->head_circumference ?? '-',
    //         'health_status' => $childRecord->health_status ?? '-',
    //         'notes' => $childRecord->notes ? json_decode($childRecord->notes) : null,
    //     ];
    // }

    public function getLatestChildRecord($ChildID)
    {
        $childRecord = ChildRecord::query()
            ->where('child_id', '=', $ChildID)
            ->where('health_status', '!=', 'invalid')
            ->orderBy('visit_date', 'DESC')
            ->first();

        if (!$childRecord) {
            return null;
        }

        return [
            'id' => $childRecord->id,
            'child_id' => $childRecord->child_id,
            'visit_date' => $childRecord->visit_date,
            // 'trimester' => $childRecord->trimester,
            'height' => $childRecord->height ?? '-',
            'bmi' => $childRecord->bmi ?? '-',
            'weight' => $childRecord->weight ?? '-',
            'head_circumference' => $childRecord->head_circumference ?? '-',
            // 'blood_sugar' => $childRecord->blood_sugar ?? '-',
            // 'blood_pressure' => $childRecord->blood_pressure ?? '-',
            'health_status' => $childRecord->health_status ?? '-',
            'notes' => $childRecord->notes ?? '-',
        ];
    }
}