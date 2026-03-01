<?php

namespace App\Services;

use App\Models\Child;
use App\Models\ParentM;
use App\Models\PublicHealthMidwife;
use App\Models\User;
// use App\Models\ChildRecord;
use App\Helpers\Validator;
use DateTime;
use Library\Framework\Database\QueryBuilder;

class ChildService
{
    private function calculateAge($dob): string
    {
        $dobDt = $dob instanceof DateTime ? clone $dob : new DateTime($dob);
        $now = new DateTime();

        if ($dobDt > $now) {
            return "0 months"; // simple handling for future dates
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

    public function getAllChildren()
    {
        // Get only non-archived children using raw query for proper NULL checking
        $children = QueryBuilder::rawGet(
            "SELECT * FROM children WHERE archived_at IS NULL ORDER BY id DESC"
        );
        
        // Convert to Child models
        $childModels = [];
        foreach ($children as $row) {
            $child = new Child();
            $child->hydrate($row);
            $childModels[] = $child;
        }
        
        $childRecordService = new ChildRecordService();

        $resource = [];
        foreach ($childModels as $child) {

            $parent = ParentM::find($child->parent_id);

            $parentResource = NULL;
            if ($parent) {
                $parentResource = [
                    'id' => $parent->id,
                    'name' => User::find($parent->id)->name,
                    'type' => $parent->type,
                ];
            }

            $latestHealthRecord = $childRecordService->getLatestChildRecord($child->id);

            $resource[] = [
                'id' => $child->id,
                'name' => $child->name,
                'age' => $this->calculateAge($child->date_of_birth),
                'date_of_birth' => $child->date_of_birth,
                'gender' => $child->gender,
                'health_status' => $child->health_status,
                'area' => $child->getArea()?->code ?? 'Unknown',
                'birth_certificate' => $child->birth_certificate,
                'notes' => $child->notes,
                'parent' => $parentResource,
                'latest_health_record' => $latestHealthRecord,
            ];
        }

        return $resource;
    }

    public function getChildernByParentId(int $parentId)
    {
        // Get only non-archived children for this parent using raw query
        $children = QueryBuilder::rawGet(
            "SELECT * FROM children WHERE parent_id = :parent_id AND archived_at IS NULL ORDER BY id DESC",
            [':parent_id' => $parentId]
        );

        // Convert to Child models
        $childModels = [];
        foreach ($children as $row) {
            $child = new Child();
            $child->hydrate($row);
            $childModels[] = $child;
        }

        $resource = [];
        foreach ($childModels as $child) {

            $parent = ParentM::find($child->parent_id);

            $parentResource = NULL;
            if ($parent) {
                $parentResource = [
                    'id' => $parent->id,
                    'name' => User::find($parent->id)->name,
                    'email' => User::find($parent->id)->email,
                    'type' => $parent->type,
                ];
            }

            $phm = PublicHealthMidwife::find($child->phm_id);

            $phmResource = NULL;
            if ($phm) {
                $phmResource = [
                    'id' => $phm->id,
                    'name' => User::find($phm->id)->name,
                ];
            }


            $resource[] = [
                'id' => $child->id,
                'name' => $child->name,
                'date_of_birth' => $child->date_of_birth,
                'age' => $this->calculateAge($child->date_of_birth),
                'gender' => $child->gender,
                'health_status' => $child->health_status,
                'blood_type' => $child->blood_type,
                'notes' => $child->notes,
                'parent' => $parentResource,
                'phm' => $phmResource,
            ]
            ;
        }

        return $resource;
    }

    public function getChildernById(int $id)
    {
        $child = Child::find($id);

        $childRecord = ChildRecord::query()->where('child_id', '=', $id)->orderBy('visit_date', 'DESC')->orderBy('created_at', 'DESC')->first();

        $childRecordResource = null;
        if($childRecord) {
            $childRecordResource = [
                'id' => $childRecord->id,
                'visit_date' => $childRecord->visit_date,
                'age_recorded_at' => $childRecord->age_recorded_at,
                'height' => $childRecord->height,
                'weight' => $childRecord->weight,
                'bmi' => $childRecord->bmi,
                'head_circumference' => $childRecord->head_circumference,
                'notes' => $childRecord->notes,
            ];
        }

        $parent = ParentM::find($child->parent_id);

        $parentResource = NULL;
        if ($parent) {
            $parentResource = [
                'id' => $parent->id,
                'name' => User::find($parent->id)->name,
                'email' => User::find($parent->id)->email,
                'type' => $parent->type,
            ];
        }

        $phm = PublicHealthMidwife::find($child->phm_id);

        $phmResource = NULL;
        if ($phm) {
            $phmResource = [
                'id' => $phm->id,
                'name' => User::find($phm->id)->name,
            ];
        }


        $resource = [
            'id' => $child->id,
            'name' => $child->name,
            'date_of_birth' => $child->date_of_birth,
            'age' => $this->calculateAge($child->date_of_birth),
            'gender' => $child->gender,
            'health_status' => $child->health_status,
            'blood_type' => $child->blood_type,
            'notes' => $child->notes,
            'parent' => $parentResource,
            'phm' => $phmResource,
            'record'=>$childRecordResource
        ]
        ;


        return $resource;
    }




    private function validateName(string $name)
    {
        $error = null;
        if (!Validator::validateFieldExistence($name)) {
            $error = "Name field cannot be empty";
            return $error;
        }

        if (!Validator::validateFieldMinLength($name, 3)) {
            $error = "Name cannot be less than 3 characters";
            return $error;
        }

        if (!Validator::validateFieldMaxLength($name, 20)) {
            $error = "Name cannot be greater than 20 characters";
            return $error;
        }

        return $error;
    }

    private function validateCommonFields($field, string $attributeName)
    {
        $error = null;
        
        // Handle integer fields (like areaId)
        if (is_int($field)) {
            if ($field <= 0) {
                $error = "{$attributeName} must be a valid selection";
                return $error;
            }
            return $error;
        }
        
        // Handle string fields
        if (!Validator::validateFieldExistence($field)) {
            $error = "{$attributeName} field cannot be empty";
            return $error;
        }

        return $error;
    }


    private function validateGender(string $gender)
    {
        $error = null;
        if (!Validator::validateFieldExistence($gender)) {
            $error = "Gender field cannot be empty";
            return $error;
        }

        $gender = strtolower($gender);
        if ($gender !== "m" && $gender !== "f") {
            $error = "Invalid Gender";
            return $error;
        }

        return $error;
    }

    public function validateChildProfile(string $name, int $areaId, string $dob, string $gender, string $birthCertificate, bool $edit = false)
    {
        $errors = [];
        $suffix = $edit ? 'e_' : '';

        $nameError = $this->validateName($name);
        if ($nameError) {
            $errors["{$suffix}name"] = $nameError;
        }

        $areaError = $this->validateCommonFields($areaId, "Area");
        if ($areaError) {
            $errors["{$suffix}area"] = $areaError;
        }

        $dobError = $this->validateCommonFields($dob, "Date of Birth");
        if ($dobError) {
            $errors["{$suffix}date_of_birth"] = $dobError;
        }

        $birthCertificateError = $this->validateCommonFields($birthCertificate, "Birth Certificate No");
        if ($birthCertificateError) {
            $errors["{$suffix}birth_certificate"] = $birthCertificateError;
        }

        $genderError = $this->validateGender($gender);
        if ($genderError) {
            $errors["{$suffix}gender"] = $genderError;
        }

        return $errors;
    }

    public function validateArchiveProfile(int $id)
    {
        $error = null;

        $child = Child::find($id);

        if (!$child) {
            $error = "Child profile not found";
            return $error;
        }

        if ($child->archived_at !== null) {
            $error = "This child profile is already archived";
        }

        return $error;
    }

    public function validateUnarchiveProfile(int $id)
    {
        $error = null;

        $child = Child::find($id);

        if (!$child) {
            $error = "Child profile not found";
            return $error;
        }

        if ($child->archived_at === null) {
            $error = "This child profile is not archived";
        }

        return $error;
    }

    public function createChildProfile(string $name, string $areaId, string $dob, string $gender, string $birthCertificate)
    {
        $phmId = auth()->id();

        
        $child = new Child();
        $child->name = $name;
        $child->date_of_birth = $dob;
        $child->gender = $gender;
        $child->birth_certificate = $birthCertificate;
        $child->area_id = $areaId;
        $child->phm_id = $phmId;
        $child->save();
    }

    public function editChildProfile(int $childId, string $name, int $areaId, string $dob, string $gender, string $birthCertificate)
    {
        $child = Child::find($childId);
        if ($child) {
            $child->name = $name;
            $child->date_of_birth = $dob;
            $child->gender = $gender;
            $child->area_id = $areaId;
            $child->birth_certificate = $birthCertificate;
            $child->save();
        }
    }

     public function deleteChildProfile(int $id)
     {
         $child = Child::find($id);

         // If Patient model exists and is related, uncomment and use the following lines:
         // $patient = Patient::find($child->id);
         // if ($patient) {
         //     $patient->delete();
         // }

         $child->delete();
     }

    public function archiveChildProfile(int $id)
    {
        $child = Child::find($id);

        if ($child) {
            $child->archived_at = date('Y-m-d H:i:s');
            $child->save();
        }
    }

    public function unarchiveChildProfile(int $id)
    {
        $child = Child::find($id);

        if ($child) {
            $child->archived_at = null;
            $child->save();
        }
    }

    public function getArchivedChildren()
    {
        // Get only archived children using raw query
        $children = QueryBuilder::rawGet(
            "SELECT * FROM children WHERE archived_at IS NOT NULL ORDER BY archived_at DESC"
        );

        // Convert to Child models
        $childModels = [];
        foreach ($children as $row) {
            $child = new Child();
            $child->hydrate($row);
            $childModels[] = $child;
        }

        $childRecordService = new ChildRecordService();

        $resource = [];
        foreach ($childModels as $child) {

            $parent = ParentM::find($child->parent_id);

            $parentResource = NULL;
            if ($parent) {
                $parentResource = [
                    'id' => $parent->id,
                    'name' => User::find($parent->id)->name,
                    'type' => $parent->type,
                ];
            }

            $latestHealthRecord = $childRecordService->getLatestChildRecord($child->id);

            $resource[] = [
                'id' => $child->id,
                'name' => $child->name,
                'age' => $this->calculateAge($child->date_of_birth),
                'date_of_birth' => $child->date_of_birth,
                'gender' => $child->gender,
                'health_status' => $child->health_status,
                'area' => $child->getArea()?->code ?? 'Unknown',
                'birth_certificate' => $child->birth_certificate,
                'notes' => $child->notes,
                'parent' => $parentResource,
                'latest_health_record' => $latestHealthRecord,
                'archived_at' => $child->archived_at,
            ];
        }

        return $resource;
    }
}