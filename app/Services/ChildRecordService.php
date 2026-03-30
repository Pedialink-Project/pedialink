<?php

namespace App\Services;
use App\Models\ChildRecord;
use App\Helpers\Validator;
use DateTime;

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


    private function calculateAge($dob): string
    {
        $dobDt = $dob instanceof DateTime ? clone $dob : new DateTime($dob);
        $now = new DateTime();

        if ($dobDt > $now) {
            return "Date of birth is in the future"; // simple handling for future dates
        }

        $diff = $now->diff($dobDt);

        if ($diff->y >= 1) {
            $y = $diff->y;
            return $y . ' year' . ($y === 1 ? '' : 's');
        }

        if ($diff->m >= 1) {
            $m = $diff->m;
            return $m . ' month' . ($m === 1 ? '' : 's');
        }

        $d = $diff->d;
        return $d . ' day' . ($d === 1 ? '' : 's');


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
        $now = new DateTime();
        $dob = new DateTime($dateOfBirth);
        $interval = $now->diff($dob);
        
        // Calculate total months: years * 12 + months
        $totalMonths = ($interval->y * 12) + $interval->m;
        
        return $totalMonths;
    }


    public function determineChildHealthStatus($bmi, $ageInMonths, $weight, $height, $headCircumference = null)
    {
        /**
         * Determine child health status based on BMI, age, height, weight, and head circumference
         * Based on WHO growth patterns and developmental stages:
         * - Infants (0-12 months): Rapid growth, weight triples
         * - Toddlers (12-36 months): Growth tapers, gain 2-3 kg/year
         * - Preschoolers (36-60 months): Consistent growth, gain ~2 kg/year
         * - School-aged (60-144 months): Steady growth, gain 2.5-3.5 kg/year
         * - Adolescents (144-216 months): Variable growth with puberty spurts
         * 
         * Returns: 'Critical', 'Bad', or 'Good'
         */
        $criticalCount = 0;
        $badCount = 0;

        $age = !empty($ageInMonths) ? (int)$ageInMonths : null;

        if ($age === null || $age < 0) {
            return null;
        }

        // BMI checks (age-based)
        if (!empty($bmi)) {
            $bmiValue = (float)$bmi;
            if ($bmiValue <= 0) {
                return null;
            }

            // Infants (0-12 months): BMI ranges 12-20
            if ($age <= 12) {
                if ($bmiValue < 10 || $bmiValue > 20) {
                    $criticalCount++;
                } elseif ($bmiValue < 12 || $bmiValue > 18) {
                    $badCount++;
                }
            }
            // Toddlers (12-36 months): BMI ranges 12-20
            elseif ($age <= 36) {
                if ($bmiValue < 12 || $bmiValue > 20) {
                    $criticalCount++;
                } elseif ($bmiValue < 13 || $bmiValue > 18) {
                    $badCount++;
                }
            }
            // Preschoolers (36-60 months): BMI ranges 12.5-20
            elseif ($age <= 60) {
                if ($bmiValue < 12.5 || $bmiValue > 20) {
                    $criticalCount++;
                } elseif ($bmiValue < 13.5 || $bmiValue > 19) {
                    $badCount++;
                }
            }
            // School-aged (60-144 months): BMI ranges 13-22
            elseif ($age <= 144) {
                if ($bmiValue < 13 || $bmiValue > 22) {
                    $criticalCount++;
                } elseif ($bmiValue < 14 || $bmiValue > 20.5) {
                    $badCount++;
                }
            }
            // Adolescents (144+ months): BMI ranges 14-25
            else {
                if ($bmiValue < 14 || $bmiValue > 25) {
                    $criticalCount++;
                } elseif ($bmiValue < 15.5 || $bmiValue > 23.5) {
                    $badCount++;
                }
            }
        }

        // Weight checks (kg) - based on growth patterns
        if (!empty($weight)) {
            $w = (float)$weight;
            if ($w <= 0) {
                // Invalid weight value, skip checks
            }
            // Infants (0-12 months): Birth ~3.5kg, Triple by 12 months ~10.5kg
            elseif ($age <= 12) {
                if ($w < 2.5 || $w > 11) {
                    $criticalCount++;
                } elseif ($w < 3 || $w > 10) {
                    $badCount++;
                }
            }
            // Toddlers (12-36 months): 10.5kg → 16.5kg (gain 2-3kg/year)
            elseif ($age <= 24) {
                if ($w < 8 || $w > 15) {
                    $criticalCount++;
                } elseif ($w < 9 || $w > 14) {
                    $badCount++;
                }
            }
            elseif ($age <= 36) {
                if ($w < 11 || $w > 18) {
                    $criticalCount++;
                } elseif ($w < 12 || $w > 17) {
                    $badCount++;
                }
            }
            // Preschoolers (36-60 months): 16.5kg → 21kg (gain ~2kg/year)
            elseif ($age <= 48) {
                if ($w < 13 || $w > 20) {
                    $criticalCount++;
                } elseif ($w < 14 || $w > 19) {
                    $badCount++;
                }
            }
            elseif ($age <= 60) {
                if ($w < 15 || $w > 23) {
                    $criticalCount++;
                } elseif ($w < 16 || $w > 22) {
                    $badCount++;
                }
            }
            // School-aged (60-144 months): 21kg → 42kg (gain 2.5-3.5kg/year)
            elseif ($age <= 84) {
                if ($w < 18 || $w > 30) {
                    $criticalCount++;
                } elseif ($w < 19 || $w > 28) {
                    $badCount++;
                }
            }
            elseif ($age <= 108) {
                if ($w < 25 || $w > 38) {
                    $criticalCount++;
                } elseif ($w < 26 || $w > 36) {
                    $badCount++;
                }
            }
            elseif ($age <= 144) {
                if ($w < 32 || $w > 48) {
                    $criticalCount++;
                } elseif ($w < 34 || $w > 46) {
                    $badCount++;
                }
            }
            // Adolescents (144+ months): Variable growth, significant range
            else {
                if ($w < 40 || $w > 85) {
                    $criticalCount++;
                } elseif ($w < 45 || $w > 80) {
                    $badCount++;
                }
            }
        }

        // Height checks (cm) - based on growth patterns
        if (!empty($height)) {
            $h = (float)$height;
            if ($h <= 0) {
                // Invalid height value, skip checks
            }
            // Infants (0-12 months): 50cm → 75cm (gain ~25cm)
            elseif ($age <= 12) {
                if ($h < 48 || $h > 77) {
                    $criticalCount++;
                } elseif ($h < 52 || $h > 75) {
                    $badCount++;
                }
            }
            // Toddlers (12-36 months): 75cm → 99cm (gain ~12cm/year)
            elseif ($age <= 24) {
                if ($h < 70 || $h > 90) {
                    $criticalCount++;
                } elseif ($h < 73 || $h > 87) {
                    $badCount++;
                }
            }
            elseif ($age <= 36) {
                if ($h < 80 || $h > 102) {
                    $criticalCount++;
                } elseif ($h < 85 || $h > 99) {
                    $badCount++;
                }
            }
            // Preschoolers (36-60 months): 99cm → 113cm (gain 6-8cm/year)
            elseif ($age <= 48) {
                if ($h < 95 || $h > 109) {
                    $criticalCount++;
                } elseif ($h < 98 || $h > 106) {
                    $badCount++;
                }
            }
            elseif ($age <= 60) {
                if ($h < 102 || $h > 117) {
                    $criticalCount++;
                } elseif ($h < 106 || $h > 114) {
                    $badCount++;
                }
            }
            // School-aged (60-144 months): 113cm → 148cm (gain 5-6cm/year)
            elseif ($age <= 84) {
                if ($h < 110 || $h > 135) {
                    $criticalCount++;
                } elseif ($h < 113 || $h > 132) {
                    $badCount++;
                }
            }
            elseif ($age <= 108) {
                if ($h < 125 || $h > 150) {
                    $criticalCount++;
                } elseif ($h < 128 || $h > 147) {
                    $badCount++;
                }
            }
            elseif ($age <= 144) {
                if ($h < 135 || $h > 165) {
                    $criticalCount++;
                } elseif ($h < 138 || $h > 162) {
                    $badCount++;
                }
            }
            // Adolescents (144+ months): 148cm → 175cm+ (variable growth with puberty)
            else {
                if ($h < 145 || $h > 190) {
                    $criticalCount++;
                } elseif ($h < 150 || $h > 185) {
                    $badCount++;
                }
            }
        }

        // Head circumference checks (cm)
        if (!empty($headCircumference)) {
            $hc = (float)$headCircumference;
            if ($hc <= 0) {
                // Invalid head circumference value, skip checks
            }
            // Infants (0-12 months): 35cm → 47cm
            elseif ($age <= 12) {
                if ($hc < 32 || $hc > 50) {
                    $criticalCount++;
                } elseif ($hc < 34 || $hc > 48) {
                    $badCount++;
                }
            }
            // Toddlers (12-36 months): 47cm → 50cm (slow growth)
            elseif ($age <= 36) {
                if ($hc < 44 || $hc > 53) {
                    $criticalCount++;
                } elseif ($hc < 46 || $hc > 51) {
                    $badCount++;
                }
            }
            // Preschoolers (36-60 months): 50cm → 52cm
            elseif ($age <= 60) {
                if ($hc < 48 || $hc > 55) {
                    $criticalCount++;
                } elseif ($hc < 50 || $hc > 53) {
                    $badCount++;
                }
            }
            // School-aged (60-144 months): 52cm → 57cm
            elseif ($age <= 144) {
                if ($hc < 50 || $hc > 58) {
                    $criticalCount++;
                } elseif ($hc < 52 || $hc > 56) {
                    $badCount++;
                }
            }
            // Adolescents (144+ months): 57cm → 60cm
            else {
                if ($hc < 54 || $hc > 62) {
                    $criticalCount++;
                } elseif ($hc < 56 || $hc > 60) {
                    $badCount++;
                }
            }
        }

        if ($criticalCount > 0) {
            return 'Critical';
        }

        if ($badCount > 0) {
            return 'Bad';
        }

        return 'Good';
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

    private function getMeasurementRangesByAge(int $ageInMonths): array
    { 
        // if ($ageInMonths <= 1) {
        //     return [
        //         'weight' => ['min' => 2.5, 'max' => 5],
        //         'height' => ['min' => 51, 'max' => 65],
        //         'head_circumference' => ['min' => 32, 'max' => 52],
        //     ];
        // }
        if ($ageInMonths <= 6) {
            return [
                'weight' => ['min' => 2.5, 'max' => 8],
                'height' => ['min' => 45, 'max' => 70],
                'head_circumference' => ['min' => 32, 'max' => 42],
            ];
        }
        if ($ageInMonths <= 12) {
            return [
                'weight' => ['min' => 10, 'max' => 28],
                'height' => ['min' => 55, 'max' => 80],
                'head_circumference' => ['min' => 34, 'max' => 46],
            ];
        }
        if ($ageInMonths <= 24) {
            return [
                'weight' => ['min' => 10, 'max' => 50],
                'height' => ['min' => 45, 'max' => 100],
                'head_circumference' => ['min' => 32, 'max' => 52],
            ];
        }

        if ($ageInMonths <= 60) {
            return [
                'weight' => ['min' => 10, 'max' => 50],
                'height' => ['min' => 70, 'max' => 125],
                'head_circumference' => ['min' => 42, 'max' => 56],
            ];
        }

        return [
            'weight' => ['min' => 12, 'max' => 50],
            'height' => ['min' => 90, 'max' => 180],
            'head_circumference' => ['min' => 46, 'max' => 60],
        ];
    }

    private function validateAgeBasedMeasurements($height, $weight, $headCircumference, int $ageInMonths, string $errorSuffix = ''): array
    {
        $errors = [];
        $ranges = $this->getMeasurementRangesByAge($ageInMonths);

        if (is_numeric($weight)) {
            $weightValue = (float) $weight;
            if ($weightValue < $ranges['weight']['min'] || $weightValue > $ranges['weight']['max']) {
                $errors["{$errorSuffix}weight"] = "Weight is not valid";
            }
        }

        if (is_numeric($height)) {
            $heightValue = (float) $height;
            if ($heightValue < $ranges['height']['min'] || $heightValue > $ranges['height']['max']) {
                $errors["{$errorSuffix}height"] = "Height is not valid";
            }
        }

        if (is_numeric($headCircumference)) {
            $headValue = (float) $headCircumference;
            if ($headValue < $ranges['head_circumference']['min'] || $headValue > $ranges['head_circumference']['max']) {
                $errors["{$errorSuffix}head_circumference"] = "Head circumference is not valid";
            }
        }

        return $errors;
    }




    public function validateChildRecordData($visitdate, $height, $head_circumference, $bloodSugar,$weight,$trimester, $healthStatus,$additionalNotes, $edit = false, $ageInMonths = null)
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

        $headCircumferenceError = $this->validateNumericStat($head_circumference, "Head Circumference");
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

        if ($ageInMonths !== null && is_numeric($ageInMonths)) {
            $ageBasedErrors = $this->validateAgeBasedMeasurements(
                $height,
                $weight,
                $head_circumference,
                (int) $ageInMonths,
                $errorSuffix
            );

            $errors = array_merge($errors, $ageBasedErrors);
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