<?php

namespace App\Services;

use App\Models\Child;
use App\Models\ParentM;
use App\Models\PublicHealthMidwife;
use App\Models\User;
use App\Models\ChildRecord;
use App\Helpers\Validator;
use App\Models\ChildAccessRequest;
use App\Models\ParentChild;
use Library\Framework\Database\QueryBuilder;
use App\Helpers\Calculator;
use DateTime;

class ChildService
{

    private  $notificationService;
    private $childRecordService;

    public function __construct()
    {
        $this->notificationService = new NotificationService();
        $this->childRecordService = new ChildRecordService();
    }

    private function applyChildSearch(QueryBuilder $children, string $search)
{
    $children->where('name', 'ILIKE', "$search%");
    return $children;
}


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
        $children = Child::all();

        $resource = [];
        foreach ($children as $child) {

            $parent = ParentChild::query()->where('child_id', '=', $child->id)->first()->getParent();

            $parentResource = NULL;
            if ($parent) {
                $parentResource = [
                    'id' => $parent->id,
                    'name' => User::find($parent->id)->name,
                    'type' => $parent->type,
                ];
            }

            $resource[] = [
                'id' => $child->id,
                'name' => $child->name,
                'age' => $this->calculateAge($child->date_of_birth),
                'date_of_birth' => $child->date_of_birth,
                'gender' => $child->gender,
                'health_status' => $child->health_status,
                'area' => $child->getArea()->code,
                'birth_certificate' => $child->birth_certificate,
                'notes' => $child->notes,
                'parent' => $parentResource,
            ];
        }

        return $resource;
    }

    public function getChildernByParentId(int $parentId)
    {
        $childrenParent = ParentChild::query()->where('parent_id', '=', $parentId)->get();

        $resource = [];
        foreach ($childrenParent as $childParent) {

            $parent = $childParent->getParent();

            $parentResource = NULL;
            if ($parent) {
                $parentResource = [
                    'id' => $parent->id,
                    'name' => User::find($parent->id)->name,
                    'email' => User::find($parent->id)->email,
                    'type' => $parent->type,
                ];
            }

            $child = $childParent->getChild();

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
            ];
        }

        return $resource;
    }

   public function getChildrenByStaffId(
    int $staffId,
    ?string $search = null,
    ?array $filters = null
) {

    $childrenQuery = Child::query();

    if ($search) {
        $childrenQuery = $this->applyChildSearch($childrenQuery, $search);
    }

    $results = $childrenQuery
        ->orderBy('id', 'ASC')
        ->paginate(10)
        ->toArray();

    $resource = [];

    $requests = ChildAccessRequest::query()
        ->where('staff_id', '=', $staffId)
        ->get();

    foreach ($results['items'] as $child) {

        $request = null;

        foreach ($requests as $req) {
            if ($req->child_id == $child->id) {
                $request = $req;
                break;
            }
        }

        $accessStatus = 'not_requested';
        $hasFullAccess = false;

        if ($request) {
            if ($request->accepted === true) {
                $accessStatus = 'accepted';
                $hasFullAccess = true;
            } elseif ($request->accepted === false) {
                $accessStatus = 'pending';
            } else {
                $accessStatus = 'rejected';
            }
        }

        if (!empty($filters['access_status'])) {
            if (!in_array($accessStatus, $filters['access_status'])) {
                continue;
            }
        }

        $parent = ParentChild::query()->where('child_id', '=', $child['id'])->first()->getParent();
        $phm    = PublicHealthMidwife::find($child->phm_id);
        $latestRecord = ChildRecord::query()->where('child_id', '=', $child['id'])->orderBy('visit_date', 'DESC')->orderBy('created_at', 'DESC')->first();

        $childData = [
            'id' => $child->id,
            'name' => $child->name,
            'age' => $this->calculateAge($child->date_of_birth),
            'access_status' => $accessStatus,

            'phm' => $phm ? [
                'id' => $phm->id,
                'name' => User::find($phm->id)->name,
            ] : null,

            'record' => $latestRecord ? [
                'id'=> $latestRecord->id,
                'visit_date'=> $latestRecord->visit_date,
                'age_recorded_at'=> Calculator::calculateAgeInMonths(Child::find($child['id'])->date_of_birth, $latestRecord->visit_date),
                'height'=> $latestRecord->height,
                'weight'=> $latestRecord->weight,
                'bmi'=> $latestRecord->bmi,
                'head_circumference'=> $latestRecord->head_circumference,
                'health_status'=> $latestRecord->health_status,
                'notes'=> $latestRecord->notes,
            ] : null,
        ];

        if ($hasFullAccess) {
            $childData = array_merge($childData, [
                'gender' => $child->gender,
                'health_status' => $child->health_status,
                'blood_type' => $child->blood_type,
                'notes' => $child->notes,
                'area' => $child->getArea()->code,

                'parent' => $parent ? [
                    'id' => $parent->id,
                    'name' => User::find($parent->id)->name,
                    'email' => User::find($parent->id)->email,
                ] : null,
            ]);
        }

        $resource[] = $childData;
    }

    $links = array_diff_key($results, ['items' => true]);

    return [$resource, $links];
}


    public function getChildernById(int $id)
    {
        $child = Child::find($id);

        $childRecord = ChildRecord::query()->where('child_id', '=', $id)->orderBy('visit_date', 'DESC')->orderBy('created_at', 'DESC')->first();

        $childRecordResource = null;
        if ($childRecord) {
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

        $parent = ParentChild::query()->where('child_id', '=', $child->id)->first()->getParent();

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
            'record' => $childRecordResource
        ];


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

    private function validateCommonFields(string $field, string $attributeName)
    {
        $error = null;
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

    public function validateDeleteProfile(int $id)
    {
        $error = null;

        $child = Child::find($id);

        if ($child && $child->parent_id !== NULL) {
            $error = "Cannot delete linked child account";
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

    public function validateRequestAccess($childId, $reasonTitle, $reasonDescription)
    {
        $errors = [];

        if (!Validator::validateFieldExistence($childId)) {
            $errors['child_id'] = "Child Profile field cannot be empty";
        }

        if (!Validator::validateFieldExistence($reasonTitle)) {
            $errors['reason_title'] = "Reason Title field cannot be empty";
        }

        if (!Validator::validateFieldExistence($reasonDescription)) {
            $errors['reason_description'] = "Reason Description field cannot be empty";
        }

        return $errors;
    }

    public function requestChildAccess(
        int $staffId,
        int $childId,
        string $reasonTitle,
        string $reasonDescription
    ): ?string {
        // Prevent duplicate requests
        $existing = ChildAccessRequest::query()
            ->where('staff_id', '=', $staffId)
            ->where('child_id', '=', $childId)
            ->first();

        if ($existing) {
            return "Access request already exists";
        }

        $request = new ChildAccessRequest();
        $request->staff_id = $staffId;
        $request->child_id = $childId;
        $request->reason_title = $reasonTitle;
        $request->reason_description = $reasonDescription;
        $request->save();

        $staff = User::find($staffId);
        $child = Child::find($childId);

        $this->notificationService->notifyAdmins(
            "Child Access Request",
            "{$staff->name} requested access to child profile {$child->name}. Reason: {$reasonTitle}",
            "child_access_request",
            $request->id
        );

        return null;
    }

    public function getUnaccessedChildrenForStaff(int $staffId): array
    {
        $requestedChildIds = ChildAccessRequest::query()
            ->where('staff_id', '=', $staffId)
            ->pluck('child_id');

        $childrenQuery = Child::query();

        if (!empty($requestedChildIds)) {
            $childrenQuery->whereNotIn('id', $requestedChildIds);
        }

        $children = $childrenQuery->get();

        $resource = [];
        foreach ($children as $child) {
            $resource[] = [
                'id'   => $child->id,
                'name' => $child->name,
            ];
        }

        return $resource;
    }

    public function cancelChildAccessRequest(int $staffId, int $childId): ?string
{
    $request = ChildAccessRequest::query()
        ->where('staff_id', '=', $staffId)
        ->where('child_id', '=', $childId)
        ->first();

    if (!$request) {
        return "Access request not found";
    }

    if ($request->accepted === true) {
        return "Cannot cancel an already accepted request";
    }

    $request->delete();

    $staff = User::find($staffId);
        $child = Child::find($childId);

     $this->notificationService->notifyAdmins(
            "Child Access Request Cancelled",
            "{$staff->name} requested access to child profile {$child->name} has been cancelled.",
            "child_access_request_canclled",
            $request->id
        );


    return null; 
}



    // public function deleteChildProfile(int $id)
    // {
    //     $child = Child::find($id);

    //     $patient = Patient::find($child->id);
    //     $patient->delete();

    //     $child->delete();
    // }
}
