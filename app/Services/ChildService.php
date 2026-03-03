<?php

namespace App\Services;

use App\Models\Child;
use App\Models\ChildMisc;
use App\Models\PublicHealthMidwife;
use App\Models\User;
use App\Models\ChildRecord;
use App\Helpers\Validator;
use App\Models\ChildAccessRequest;
use App\Models\ParentChild;
use App\Rules\NameRule;
use App\Rules\DivisionRule;
use App\Rules\DateRule;
use App\Helpers\BirthCertificateValidator;
use App\Helpers\NicValidator;
use Library\Framework\Database\QueryBuilder;
use App\Helpers\Calculator;
use DateTime;

class ChildService
{

    use NameRule, DivisionRule, DateRule, BirthCertificateValidator, NicValidator;
    private  $notificationService;

    public function __construct()
    {
        $this->notificationService = new NotificationService();
    }

    private function applyChildSearch(QueryBuilder $children, string $search)
    {
        $children->where('name', 'ILIKE', "$search%");
        return $children;
    }



    public function getParentDetailsByChildId(int $childId)
    {
        $parentChildren = ParentChild::query()->where('child_id', '=', $childId);
        if ($parentChildren) {
            return null;
        }

        $resource = [];


        foreach ($parentChildren as $parentChild) {

            $parent = $parentChild->getParent();

            if (!$parent) {
                return null;
            }

            $resource[] = [
                'id' => $parent->id,
                'name' => User::find($parent->id)->name,
                'email' => User::find($parent->id)->email,
                'type' => $parent->type,
            ];
        }
        return $resource;
    }

    public function getAllChildren()
    {
        $children = Child::all();

        $resource = [];
        foreach ($children as $child) {

            $parentChild = ParentChild::query()->where('child_id', '=', $child->id)->first();
            $parent = $parentChild ? $parentChild->getParent() : null;


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
                'age' => calculateAge($child->date_of_birth),
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
                'age' => Calculator::calculateAge($child->date_of_birth),
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

            //For now only one parent details get but it modifeid to get both parent deatils and return that
            $parentChild = ParentChild::query()->where('child_id', '=', $child->id)->first();
            $parent = $parentChild ? $parentChild->getParent() : null;
            $phm    = PublicHealthMidwife::find($child->phm_id);
            $latestRecord = ChildRecord::query()->where('child_id', '=', $child->id)->orderBy('visit_date', 'DESC')->orderBy('created_at', 'DESC')->first();

            $childData = [
                'id' => $child->id,
                'name' => $child->name,
                'age' => Calculator::calculateAge($child->date_of_birth),
                'access_status' => $accessStatus,

                'phm' => $phm ? [
                    'id' => $phm->id,
                    'name' => User::find($phm->id)->name,
                ] : null,


            ];

            if ($hasFullAccess) {
                $childData = array_merge($childData, [
                    'gender' => $child->gender,
                    'blood_type' => $child->blood_type,
                    'area' => $child->getArea()->code,

                    'record' => $latestRecord ? [
                        'id' => $latestRecord->id,
                        'height' => $latestRecord->height,
                        'weight' => $latestRecord->weight,
                        'bmi' => $latestRecord->bmi,
                        'head_circumference' => $latestRecord->head_circumference,
                        'health_status' => $latestRecord->health_status,
                    ] : null,

                    'parent' => $parent ? [
                        'id' => $parent->id,
                        'type' => $parent->type,
                        'name' => User::find($parent->id)->name,
                    ] : null,
                ]);
            }

            $resource[] = $childData;
        }

        $links = array_diff_key($results, ['items' => true]);

        return [$resource, $links];
    }

    public function getChildrenByPhmId(
        int $phmId,
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
            ->where('staff_id', '=', $phmId)
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

            $linkedStatus = 'unlinked';
            //For now only one parent details get but it modifeid to get both parent deatils and return that
            $parentChild = ParentChild::query()->where('child_id', '=', $child->id)->first();
            $parent = $parentChild ? $parentChild->getParent() : null;

            if ($parent) {
                $linkedStatus = 'linked';
            } else {
                $linkedStatus = 'unlinked';
            }

            if (!empty($filters['linked_status'])) {
                if (!in_array($linkedStatus, $filters['linked_status'])) {
                    continue;
                }
            }

            $isPhmCreated = false;

            if ($child->phm_id == $phmId) {
                $isPhmCreated = true;
            }


            $latestRecord = ChildRecord::query()->where('child_id', '=', $child->id)->orderBy('visit_date', 'DESC')->orderBy('created_at', 'DESC')->first();
            $phm    = PublicHealthMidwife::find($child->phm_id);

            $childData = [
                'id' => $child->id,
                'name' => $child->name,
                'age' => Calculator::calculateAge($child->date_of_birth),
                'gender' => $child->gender,
                'area' => $child->getArea()->code,
                'access_status' => $accessStatus,
                'linked_status' => $linkedStatus,
                'is_created' => $isPhmCreated,


            ];

            if ($isPhmCreated) {
                $childMisc = ChildMisc::query()->where('children_id', '=', $child->id)->first();
                $childData = array_merge($childData, [
                    'blood_type' => $child->blood_type,
                    'birth_certificate' => $child->birth_certificate,
                    'date_of_birth' => $child->date_of_birth,
                    'parent_nic' => $childMisc->parent_nic,
                    'parent' => $parent ? [
                        'id' => $parent->id,
                        'type' => $parent->type,
                        'name' => User::find($parent->id)->name,
                    ] : null,
                ]);
            }

            if ($hasFullAccess) {
                $childData = array_merge($childData, [
                    'blood_type' => $child->blood_type,
                    'birth_certificate' => $child->birth_certificate,
                    'phm' => $phm ? [
                        'id' => $phm->id,
                        'name' => User::find($phm->id)->name,
                    ] : null,
                    'record' => $latestRecord ? [
                        'id' => $latestRecord->id,
                        'height' => $latestRecord->height,
                        'weight' => $latestRecord->weight,
                        'bmi' => $latestRecord->bmi,
                        'head_circumference' => $latestRecord->head_circumference,
                        'health_status' => $latestRecord->health_status,
                    ] : null,
                    'parent' => $parent ? [
                        'id' => $parent->id,
                        'type' => $parent->type,
                        'name' => User::find($parent->id)->name,
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
                'age_recorded_at' => Calculator::calculateAgeInMonths(Child::find($child->id)->date_of_birth, $childRecord->visit_date),
                'height' => $childRecord->height,
                'weight' => $childRecord->weight,
                'bmi' => $childRecord->bmi,
                'head_circumference' => $childRecord->head_circumference,
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
            'age' => Calculator::calculateAge($child->date_of_birth),
            'gender' => $child->gender,
            'blood_type' => $child->blood_type,
            'parent' => $parentResource,
            'phm' => $phmResource,
            'record' => $childRecordResource
        ];


        return $resource;
    }



    private function validateBloodType($bloodType)
    {
        $error = null;

        if (!Validator::validateFieldExistence($bloodType)) {
            $error = "Blood type field cannot be empty";
            return $error;
        }

        $validTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

        if (!in_array(strtoupper($bloodType), $validTypes)) {
            $error = "Please provide a valid blood type (e.g., A+, O-, AB+)";
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

    public function validateChildProfile(string $name, string $dob, string $gender, ?string $birthCertificate, string $bloodType, ?string $mother_nic, ?string $father_nic, bool $edit = false)
    {
        $errors = [];
        $suffix = $edit ? 'e_' : '';

        $nameError = $this->validateName($name, "Child Name");
        if ($nameError) {
            $errors["{$suffix}name"] = $nameError;
        }

       

        $dobError = $this->validatePastDate($dob, "Date of Birth");
        if ($dobError) {
            $errors["{$suffix}date_of_birth"] = $dobError;
        }


        if (!$edit) {

            $birthCertificateError = $this->validateBirthCertificate($birthCertificate);
            if ($birthCertificateError) {
                $errors["{$suffix}birth_certificate"] = $birthCertificateError;
            }

            $motherNicError = $this->validateNIC($mother_nic);
            if ($motherNicError) {
                $errors["{$suffix}mother_nic"] = $motherNicError;
            }

            $fatherNicError = $this->validateNIC($father_nic);
            if ($fatherNicError) {
                $errors["{$suffix}father_nic"] = $fatherNicError;
            }
        }

        $bloodTypeError = $this->validateBloodType($bloodType);
        if ($bloodTypeError) {
            $errors["{$suffix}blood_type"] = $bloodTypeError;
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

    public function createChildProfile(string $name, string $dob, string $gender, string $birthCertificate, string $bloodType, string $mother_nic, string $father_nic)
    {
        $phmId = auth()->id();
        $areaId = PublicHealthMidwife::find($phmId)->area_id;

        $child = new Child();
        $child->name = $name;
        $child->date_of_birth = $dob;
        $child->gender = $gender;
        $child->birth_certificate = $birthCertificate;
        $child->area_id = $areaId;
        $child->blood_type = $bloodType;
        $child->phm_id = $phmId;
        $child->save();


        $childMiscMother = new ChildMisc();
        $childMiscMother->parent_nic = $mother_nic;
        $childMiscMother->children_id = $child->id;
        $childMiscMother->save();

        $childMiscFather = new ChildMisc();
        $childMiscFather->parent_nic = $father_nic;
        $childMiscFather->children_id = $child->id;
        $childMiscFather->save();

        $this->requestChildAccess($phmId, $child->id, "New Child Profile Created", "A new child profile named {$child->name} has been created and is awaiting your approval.");
    }

    public function editChildProfile(int $childId, string $name, string $dob, string $gender, string $bloodType)
    {
        $child = Child::find($childId);

        if ($child->phm_id != auth()->user()->id) {
            return "Unauthorized";
        }
        if ($child) {
            $child->name = $name;
            $child->date_of_birth = $dob;
            $child->gender = $gender;
            $child->blood_type = $bloodType;
            $child->save();
        }
        return null;
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
