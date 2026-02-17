<?php

namespace App\Services;

use App\Models\Maternal;
use App\Models\ParentM;
use App\Models\MaternalAccessRequest;
use App\Models\Pregnancy;
use App\Models\MaternalRecord;
use App\Helpers\Calculator;
use App\Helpers\Validator;
use App\Rules\DateRule;
use App\Models\Area;
use App\Models\PublicHealthMidwife;
use Library\Framework\Database\QueryBuilder;

use App\Models\User;

class MaternalService
{

    use DateRule;

    private $notificationService;

    public function __construct()
    {
        $this->notificationService = new NotificationService();
    }
    public function getAllMaternal()
    {
        $maternals = Maternal::all();

        $resource = [];
        foreach ($maternals as $maternal) {
            $resource[] = [
                'id' => $maternal->id,
                'parent_id' => $maternal->parent_id,
                'type' => $maternal->type,
                'height' => $maternal->height,
                'blood_group' => $maternal->blood_group,
                'created_at' => $maternal->created_at,

            ];
        }

        return $resource;
    }

    public function getMaternalById($id)
    {
        return Maternal::find($id);
    }


    public function requestMaternalAccess(
        int $staffId,
        int $maternalId,
        string $reasonTitle,
        string $reasonDescription
    ): ?string {

    $parentId = Maternal::query()->where('id', '=', $maternalId)->first()->parent_id;
        // Prevent duplicate requests
        $existing = MaternalAccessRequest::query()
            ->where('staff_id', '=', $staffId)
            ->where('maternal_id', '=', $parentId)
            ->first();

        if ($existing) {
            return "Access request already exists";
        }

        $request = new MaternalAccessRequest();
        $request->staff_id = $staffId;
        $request->maternal_id = $parentId;
        $request->reason_title = $reasonTitle;
        $request->reason_description = $reasonDescription;
        $request->save();

        $staff = User::find($staffId);
        $maternal = User::find($parentId);

        $this->notificationService->notifyAdmins(
            "Maternal Access Request",
            "{$staff->name} requested access to maternal profile {$maternal->name}. Reason: {$reasonTitle}",
            "maternal_access_request",
            $request->id
        );

        return null;
    }

    public function getDoctorMaternalDetails()
    {


        $maternals = $this->getAllMaternal();
        $resource = [];
        foreach ($maternals as $maternal) {

            $parentName = User::query()->where('id', '=', $maternal['parent_id'])->first()->name;
            $parentAge = ParentM::query()->where('id', '=', $maternal['parent_id'])->first()->age;
            $parentAddress = ParentM::query()->where('id', '=', $maternal['parent_id'])->first()->address;
            $parentAreaId = ParentM::query()->where('id', '=', $maternal['parent_id'])->first()->areaId;


            $resource[] = [
                'id' => $maternal['id'],
                'type' => $maternal['type'],
                'stage' => $maternal['stage'],
                'pregnancy_date' => $maternal['pregnancy_date'],
                'health_status' => $maternal['health_status'],
                'additional_info' => $maternal['additional_info'],
                'name' => $parentName,
                'age' => $parentAge,
                'address' => $parentAddress,
            ];
        }


        return $resource;
    }

    public function getParentsWithoutMaternal(int $phmId)
    {

        $phm = PublicHealthMidwife::query()->where('id', '=', $phmId)->first();
        $femaleParents = ParentM::query()
            ->where('type', '=', 'mother')
            ->where('area_id', '=', $phm->area_id)
            ->get();

        $resource = [];

        foreach ($femaleParents as $parent) {

            $maternal = Maternal::query()
                ->where('parent_id', '=', $parent->id)
                ->first();

            if (!$maternal) {
                $resource[] = [
                    'id' => $parent->id,
                    'name' => User::find($parent->id)->name,
                ];
            }
        }

        return $resource;
    }


    public function getMaternalByPhmId(
        int $phmId,
        ?string $search = null,
        ?array $filters = null
    ) {

        $maternalQuery = Maternal::query();

        //search implmeted yet 

        $results = $maternalQuery
            ->orderBy('id', 'ASC')
            ->paginate(10)
            ->toArray();

        $resource = [];

        $requests = MaternalAccessRequest::query()
            ->where('staff_id', '=', $phmId)
            ->get();

        foreach ($results['items'] as $maternal) {

            $request = null;

            foreach ($requests as $req) {
                if ($req->maternal_id == $maternal->id) {
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
            if (!empty($filters['type'])) {
                if (!in_array($maternal->type, $filters['type'])) {
                    continue;
                }
            }




            $maternalData = [
                'id' => $maternal->id,
                'name' => User::find($maternal->parent_id)->name,
                'age' => Calculator::calculateAge(ParentM::find($maternal->parent_id)->date_of_birth),
                'height' => $maternal->height,
                'blood_type' => $maternal->blood_type,
                'type' => $maternal->type,
                'access_status' => $accessStatus,
            ];

            if ($hasFullAccess) {

                $latestPregnancy = Pregnancy::query()
                    ->where('maternal_id', '=', $maternal->id)
                    ->orderBy('id', 'DESC')
                    ->first();

                $latestRecord = null;

                if ($latestPregnancy) {
                    $latestRecord = MaternalRecord::query()
                        ->where('pregnancy_id', '=', $latestPregnancy->id)
                        ->orderBy('visit_date', 'DESC')
                        ->first();
                }

                $maternalData = array_merge($maternalData, [

                    'latest_pregnancy' => $latestPregnancy ? [
                        'lmp' => $latestPregnancy->lmp,
                        'edd' => $latestPregnancy->edd,
                        'gravida' => $latestPregnancy->gravida,
                        'para' => $latestPregnancy->para,
                        'delivery_outcome' => $latestPregnancy->delivery_outcome,
                        'latest_record' => $latestRecord ? [
                            'visit_date' => $latestRecord->visit_date,
                            'trimester'=> $latestRecord->trimester,
                            'weight' => $latestRecord->weight,
                            'blood_pressure' => $latestRecord->blood_pressure,
                            'bmi' => $latestRecord->bmi,
                            'glucose' => $latestRecord->glucose,
                            'hemoglobin' => $latestRecord->hemoglobin,
                            'fundal_height' => $latestRecord->fundal_height,
                            'fetal_heart_rate' => $latestRecord->fetal_heart_rate,
                            'health_status' => $latestRecord->health_status,
                        ] : null
                    ] : null
                ]);
            }

            $resource[] = $maternalData;
        }

        $links = array_diff_key($results, ['items' => true]);

        return [$resource, $links];
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

    public function validateMaternalProfile(float $height, string $lmp, string $bloodType, bool $edit = false)
    {
        $errors = [];
        $suffix = $edit ? 'e_' : '';

        if ($height !== null) {
            if (!is_numeric($height)) {
                $errors['height'] = 'Height must be numeric.';
            } elseif ($height < 10 || $height > 250) {
                $errors['height'] = 'Height must be between 10cm and 250cm.';
            }
        }

        $lmpError = $this->validatePastDate($lmp, "LMP");
        if ($lmpError) {
            $errors["{$suffix}lmp"] = $lmpError;
        }

        $bloodTypeError = $this->validateBloodType($bloodType);
        if ($bloodTypeError) {
            $errors["{$suffix}blood_type"] = $bloodTypeError;
        }




        return $errors;
    }



    public function createMaternalProfile(
        int $parentId,
        int $phmId,
        float $height,
        ?string $bloodType,
        string $lmp,
    ): ?string {


        $parent = ParentM::query()->where('id', '=', $parentId);
        $isMother = $parent->where('type', '=', 'mother')->first();
        if (!$isMother) {
            return "Selected parent is not registered as a mother.";
        }

        $phm = PublicHealthMidwife::query()->where('id', '=', $phmId)->first();

        $isphmAssigned = $parent->where('area_id', '=', $phm->area_id)->first();
        if (!$isphmAssigned) {
            return "Selected parent is not assigned to you.";
        }

        $existing = Maternal::query()
            ->where('parent_id', '=', $parentId)
            ->first();

        if ($existing) {
            return "Maternal profile already exists for this parent.";
        }


        $maternal = new Maternal();
        $maternal->parent_id = $parentId;
        $maternal->type = 'antenatal';
        $maternal->height = $height;
        $maternal->blood_type = $bloodType;
        $maternal->save();



        $pregnancy = new Pregnancy();
        $pregnancy->maternal_id = $maternal->id;
        $pregnancy->lmp = $lmp;
        $pregnancy->edd = Calculator::calculateEdd($lmp);
        $pregnancy->gravida = 1;
        $pregnancy->para = 0;
        $pregnancy->save();

        $this->requestMaternalAccess($phmId, $maternal->id, "New Maternal Profile Created", "A new maternal profile named {$maternal->name} has been created and is awaiting your approval.");

        $this->notificationService->notify(
            $parentId,
            "Your maternal profile has been created",
            "Your maternal profile has been successfully created and is now awaiting approval from your assigned Public Health Midwife.",
            "maternal_profile_created",
            $maternal->id
        );
        return null;
    }
}
