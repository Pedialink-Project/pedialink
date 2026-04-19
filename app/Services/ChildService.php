<?php

namespace App\Services;

use App\Helpers\NicExtractor;
use App\Models\Child;
use App\Models\ChildMisc;
use App\Models\PublicHealthMidwife;
use App\Models\User;
use App\Models\ChildRecord;
use App\Helpers\Validator;
use App\Models\Appointment;
use App\Models\ParentChild;
use App\Models\ParentM;
use App\Rules\NameRule;
use App\Rules\DivisionRule;
use App\Rules\DateRule;
use App\Helpers\BirthCertificateValidator;
use App\Helpers\NicValidator;
use App\Models\VaccinationReminder;
use Library\Framework\Database\QueryBuilder;
use App\Helpers\Calculator;
use App\Services\VaccinationSchedulerService;
use App\Services\AppointmentSchedulerService;
use DateTime;

class ChildService
{

    use NameRule, DivisionRule, DateRule, BirthCertificateValidator, NicValidator;
    private  $notificationService;
    private ChildRecordService $childRecordService;
    private VaccinationSchedulerService $vaccinationSchedulerService;
    private AppointmentSchedulerService $appointmentSchedulerService;

    public function __construct()
    {
        $this->notificationService = new NotificationService();
        $this->childRecordService = new ChildRecordService();
        $this->vaccinationSchedulerService = new VaccinationSchedulerService();
        $this->appointmentSchedulerService = new AppointmentSchedulerService();
    }
    private function autoArchiveAdults(): void
    {
        QueryBuilder::rawExec(
            "UPDATE children
             SET archived_at = NOW()
             WHERE archived_at IS NULL
               AND date_of_birth IS NOT NULL
               AND date_of_birth <= CURRENT_DATE - INTERVAL '18 years'"
        );
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

            $child = $childParent->getChild();

            $phm = PublicHealthMidwife::find($child->phm_id);

            $phmResource = NULL;
            if ($phm) {
                $phmResource = [
                    'id' => $phm->id,
                    'name' => User::find($phm->id)->name,
                ];
            }

            $childAppointments = Appointment::query()->where('child_id','=', $child->id)->where('status', '=', 'confirmed')->get();

            $today = new \DateTime();
            $startDate = $today->format('Y-m-d');
            $today->modify('+14 days');
            $endDate = $today->format('Y-m-d');

            $childVaccinations = VaccinationReminder::query()->where('child_id','=',$child->id)
                ->where('scheduled_date', ">=", $startDate)
                ->where("scheduled_date", "<=", $endDate)
                ->get();
            $resource[] = [
                'id' => $child->id,
                'name' => $child->name,
                'date_of_birth' => $child->date_of_birth,
                'age' => Calculator::calculateAge($child->date_of_birth),
                'gender' => $child->gender,
                'health_status' => $child->health_status,
                'blood_type' => $child->blood_type,
                'notes' => $child->notes,
                'phm' => $phmResource,
                'appointment_count' => count($childAppointments),
                'vaccination_count'=> count($childVaccinations)
            ];
        }

        return $resource;
    }

    public function getChildrenByStaffId(
        int $staffId,
        ?string $search = null,
        ?array $filters = null
    ) {

        $this->autoArchiveAdults();

        $childrenQuery = Child::query()
            ->whereNull('archived_at');

        if ($search) {
            $childrenQuery = $this->applyChildSearch($childrenQuery, $search);
        }

        $results = $childrenQuery
            ->orderBy('id', 'ASC')
            ->paginate(10)
            ->toArray();

        $resource = [];

        foreach ($results['items'] as $child) {
            $childArea = $child->getArea();
            $areaCode = $childArea ? $childArea->code : null;

            if (!empty($filters['area']) && !in_array($areaCode, $filters['area'])) {
                continue;
            }

            $parentLinks = ParentChild::query()->where('child_id', '=', $child->id)->get();
            $parents = [];
            foreach ($parentLinks as $parentLink) {
                $parent = $parentLink->getParent();
                if ($parent) {
                    $parents[] = [
                        'id' => $parent->id,
                        'type' => $parent->type,
                        'name' => User::find($parent->id)->name,
                    ];
                }
            }
            $phm    = PublicHealthMidwife::find($child->phm_id);
            $latestRecord = $this->childRecordService->getLatestHeathRecord($child->id);

            $childData = [
                'id' => $child->id,
                'name' => $child->name,
                'age' => Calculator::calculateAge($child->date_of_birth),
                'area' => $areaCode,
                'gender' => $child->gender,
                'blood_type' => $child->blood_type,

                'phm' => $phm ? [
                    'id' => $phm->id,
                    'name' => User::find($phm->id)->name,
                ] : null,
                'record' => $latestRecord,
                'parents' => $parents,


            ];

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

        $this->autoArchiveAdults();

        $childrenQuery = Child::query();

        if ($search) {
            $childrenQuery = $this->applyChildSearch($childrenQuery, $search);
        }

        $results = $childrenQuery
            ->where("area_id", "=", auth()->user()?->getRole()->area_id)
            ->orderBy('id', 'ASC')
            ->paginate(10)
            ->toArray();

        $resource = [];

        foreach ($results['items'] as $child) {
            if ($child->archived_at !== null) {
                continue;
            }

            $linkedStatus = 'unlinked';
            $parentLinks = ParentChild::query()->where('child_id', '=', $child->id)->get();
            $parents = [];
            foreach ($parentLinks as $parentLink) {
                $parent = $parentLink->getParent();
                if ($parent) {
                    $parents[] = [
                        'id' => $parent->id,
                        'type' => $parent->type,
                        'name' => User::find($parent->id)->name,
                    ];
                }
            }

            if (!empty($parents)) {
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
                'linked_status' => $linkedStatus,
                'is_created' => $isPhmCreated,


            ];

            if ($isPhmCreated) {
                $childMisc = ChildMisc::query()->where('children_id', '=', $child->id)->get();

                $motherNic = null;
                $fatherNic = null;

                foreach ($childMisc as $misc) {
                    $nicExtractor = new NicExtractor($misc->parent_nic);
                    $extractedNic = $nicExtractor->getExtractedNic();
                    if (isset($extractedNic['gender'])) {
                        $gender = $extractedNic['gender'];

                        if ($gender === 'M') {
                            $fatherNic = $misc->parent_nic;
                        } elseif ($gender === 'F') {
                            $motherNic = $misc->parent_nic;
                        }
                    }
                }
                $childData = array_merge($childData, [
                    'blood_type' => $child->blood_type,
                    'birth_certificate' => $child->birth_certificate,
                    'date_of_birth' => $child->date_of_birth,
                    'mother_nic' => $motherNic,
                    'father_nic' => $fatherNic,
                    'parents' => $parents,
                ]);
            }

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
                'parents' => $parents,
            ]);

            $resource[] = $childData;
        }

        $links = array_diff_key($results, ['items' => true]);

        return [$resource, $links];
    }

     public function fetchVaccinationRecordsByChildId(int $childId): array
    {
        $vaccinationRemainder = VaccinationReminder::query()
            ->where("child_id", "=", $childId)->limit(5)->get();

      
        $resource = [];
        foreach ($vaccinationRemainder as $remainder) {
            $scheduleVaccine = $remainder->getScheduleVaccine();
            $schedule = $scheduleVaccine ? $scheduleVaccine->getSchedule() : null;
            $vaccine = $scheduleVaccine ? $scheduleVaccine->getVaccine() : null;

            $recorded_age = calculateAge($remainder->getChild()->date_of_birth, new \DateTimeImmutable($remainder->scheduled_date));
            $status = $remainder->getComputedStatus();
            $resource[] = [
                "id" => $remainder->id,
                "vaccine" => $vaccine ? [
                    "id" => $vaccine->id,
                    "name" => $vaccine->name,
                    "code" => $vaccine->code,
                ] : null,
                "schedule_vaccine" => [
                    "dose_number" => $scheduleVaccine ? $scheduleVaccine->dose_number : null,
                    "additional_information" => $scheduleVaccine ? $scheduleVaccine->additional_information : null,
                ],
                "schedule" => $schedule ? [
                    "id" => $schedule->id,
                    "name" => $schedule->name,
                ] : null,
                "status" => $status,
                "recorded_age" => $recorded_age,
                "scheduled_date" => $remainder->scheduled_date,
                
            ];
        }

        return $resource;
        
    }

     public function getChildAppointmentByChildId($childId)
    {

        $appointments = Appointment::query()->where('child_id', '=', $childId);

      
        $appointments = $appointments
            ->join("appointment_slots as s", "s.id", "=", "appointments.slot_id")
            ->limit(3)
            ->get();

        $resource = [];
        foreach ($appointments as $appointment) {
            $slot = $appointment->getSlot();
            $doctor = $slot->getDoctor();
            $child = $appointment->getChild();
            $resource[] = [
                "id" => $appointment->id,
                "slot_date" => $slot->slot_date,
                "start_time" => Calculator::formatTimeToAmPm($slot->start_time),
                "end_time" => Calculator::formatTimeToAmPm($slot->end_time),
                "doctor" => $doctor ? [
                    "id" => $doctor->id,
                    "name" => $doctor->getUser()->name
                ] : null,
                "child" => $child ? [
                    "id" => $child->id,
                    "name" => $child->name,
                ] : null,   
                "reason" => $appointment->reason,
                "status" => $appointment->status
            ];
        }


       return $resource;
    }

    public function getChildGrowthData(int $childId)
{
    $sql = "
    SELECT 
        c.id AS child_id,
        c.name AS child_name,
        h.visit_date,
        h.height,
        h.weight,
        h.bmi
    FROM children c
    JOIN child_records h ON h.child_id = c.id
    WHERE c.id = :childId
    ORDER BY h.visit_date
    ";

    $rows = QueryBuilder::rawGet($sql, [
        ':childId' => $childId
    ]);

    $child = [
        'id' => $childId,
        'name' => '',
        'bmi' => [],
        'height' => [],
        'weight' => [],
        'labels' => []
    ];

    foreach ($rows as $row) {

        $child['name'] = $row['child_name'];

        $child['labels'][] = date("M", strtotime($row['visit_date']));
        $child['bmi'][] = (float)$row['bmi'];
        $child['height'][] = (float)$row['height'];
        $child['weight'][] = (float)$row['weight'];
    }

    return $child;
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
                'age_recorded_at' => Calculator::calculateAgeWithVisitDate(Child::find($child->id)->date_of_birth, $childRecord->visit_date),
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
        $childId = $child->save();


        $childMiscMother = new ChildMisc();
        $childMiscMother->parent_nic = $mother_nic;
        $childMiscMother->children_id = $child->id;
        $childMiscMother->save();

        $childMiscFather = new ChildMisc();
        $childMiscFather->parent_nic = $father_nic;
        $childMiscFather->children_id = $child->id;
        $childMiscFather->save();

        $this->vaccinationSchedulerService->createInitialRemindersForChild((int)$childId);
        $this->appointmentSchedulerService->scheduleInitialForChild((int)$childId);

        $recipientIds = [];

        if (!empty($mother_nic)) {
            $mother = ParentM::query()->where('nic', '=', $mother_nic)->first();
            if ($mother) {
                $user = $mother->getUser();
                if ($user) {
                    $recipientIds[] = (int)$user->id;
                }
            }
        }

        if (!empty($father_nic)) {
            $father = ParentM::query()->where('nic', '=', $father_nic)->first();
            if ($father) {
                $user = $father->getUser();
                if ($user) {
                    $recipientIds[] = (int)$user->id;
                }
            }
        }

        $phmName = auth()->check() ? auth()->user()->name : 'PHM';

        if (!empty($recipientIds)) {
            $message = "A new child profile for {$child->name} has been created by {$phmName}.";
            $this->notificationService->notifyMany(
                $recipientIds,
                "New child profile created",
                $message,
                "child_profile",
                (int)$childId
            );
        }

        $this->notificationService->notifyAdmins(
            "New child profile created",
            "{$phmName} created a new child profile for {$child->name}.",
            "child_profile",
            (int)$childId
        );
    }

    private function validateDateOfBirth(string $dob)
    {
        $error = null;
        
        if (!Validator::validateFieldExistence($dob)) {
            $error = "Date of Birth field cannot be empty";
            return $error;
        }
        
        try {
            $dobDt = new DateTime($dob);
            $now = new DateTime();
            
            if ($dobDt > $now) {
                $error = "Date of Birth cannot be in the future";
                return $error;
            }
        } catch (\Exception $e) {
            $error = "Invalid Date of Birth format";
            return $error;
        }
        
        return $error;
    }

    private function hasReachedEighteen(string $dob): bool
    {
        try {
            $dobDt = new DateTime($dob);
            $today = new DateTime('today');
            $eighteenthBirthday = (clone $dobDt)->modify('+18 years');

            return $eighteenthBirthday <= $today;
        } catch (\Exception $e) {
            return false;
        }
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
            return $error;
        }

        if ($child->date_of_birth && $this->hasReachedEighteen($child->date_of_birth)) {
            $error = "This child profile cannot be restored because the child is 18 years or older";
        }

        return $error;
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
            $childId= $child->save();

            $this->vaccinationSchedulerService->recalculateForChild((int)$childId);
        }
        return null;
    }

    // public function validateRequestAccess($childId, $reasonTitle, $reasonDescription)
    // {
    //     $errors = [];

    //     if (!Validator::validateFieldExistence($childId)) {
    //         $errors['child_id'] = "Child Profile field cannot be empty";
    //     }

    //     if (!Validator::validateFieldExistence($reasonTitle)) {
    //         $errors['reason_title'] = "Reason Title field cannot be empty";
    //     }

    //     if (!Validator::validateFieldExistence($reasonDescription)) {
    //         $errors['reason_description'] = "Reason Description field cannot be empty";
    //     }

    //     return $errors;
    // }

    // public function requestChildAccess(
    //     int $staffId,
    //     int $childId,
    //     string $reasonTitle,
    //     string $reasonDescription
    // ): ?string {
    //     // Prevent duplicate requests
    //     $existing = ChildAccessRequest::query()
    //         ->where('staff_id', '=', $staffId)
    //         ->where('child_id', '=', $childId)
    //         ->first();

    //     if ($existing) {
    //         return "Access request already exists";
    //     }

    //     $request = new ChildAccessRequest();
    //     $request->staff_id = $staffId;
    //     $request->child_id = $childId;
    //     $request->reason_title = $reasonTitle;
    //     $request->reason_description = $reasonDescription;
    //     $request->save();

    //     $staff = User::find($staffId);
    //     $child = Child::find($childId);

    //     $this->notificationService->notifyAdmins(
    //         "Child Access Request",
    //         "{$staff->name} requested access to child profile {$child->name}. Reason: {$reasonTitle}",
    //         "child_access_request",
    //         $request->id
    //     );

    //     return null;
    // }

    // public function getUnaccessedChildrenForStaff(int $staffId): array
    // {
    //     $requestedChildIds = ChildAccessRequest::query()
    //         ->where('staff_id', '=', $staffId)
    //         ->pluck('child_id');

    //     $childrenQuery = Child::query();

    //     if (!empty($requestedChildIds)) {
    //         $childrenQuery->whereNotIn('id', $requestedChildIds);
    //     }

    //     $children = $childrenQuery->get();

    //     $resource = [];
    //     foreach ($children as $child) {
    //         $resource[] = [
    //             'id'   => $child->id,
    //             'name' => $child->name,
    //         ];
    //     }

    //     return $resource;
    // }

    // public function cancelChildAccessRequest(int $staffId, int $childId): ?string
    // {
    //     $request = ChildAccessRequest::query()
    //         ->where('staff_id', '=', $staffId)
    //         ->where('child_id', '=', $childId)
    //         ->first();

    //     if (!$request) {
    //         return "Access request not found";
    //     }

    //     if ($request->accepted === true) {
    //         return "Cannot cancel an already accepted request";
    //     }

    //     $request->delete();

    //     $staff = User::find($staffId);
    //     $child = Child::find($childId);

    //     $this->notificationService->notifyAdmins(
    //         "Child Access Request Cancelled",
    //         "{$staff->name} requested access to child profile {$child->name} has been cancelled.",
    //         "child_access_request_canclled",
    //         $request->id
    //     );


    //     return null;
    // }



    // public function deleteChildProfile(int $id)
    // {
    //     $child = Child::find($id);

    //     $patient = Patient::find($child->id);
    //     $patient->delete();

    //     $child->delete();
    // }

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
        $this->autoArchiveAdults();

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

            $latestHealthRecord = $childRecordService->getLatestHeathRecord($child->id);

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
