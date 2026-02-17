<?php

namespace App\Services;

use App\Models\Maternal;
use App\Models\ParentM;
use App\Models\MaternalAccessRequest;
use App\Models\Pregnancy;
use App\Models\MaternalRecord;
use App\Helpers\Calculator;
use App\Models\Area;
use App\Models\PublicHealthMidwife;
use App\Models\User;

class MaternalService
{
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

    // public function calculatePregnancyDuration( $date)
    // {
    //     $start = new \DateTime($date);
    //     $end = new \DateTime(); 
    //     $interval = $start->diff($end);

    //     return $interval->days;
    // }



    public function getMaternalById($id)
    {
        return Maternal::find($id);
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

            $is_created = false;
            if ($maternal->phm_id == $phmId) {
                $is_created = true;
            }




            $childData = [
                'id' => $maternal->id,
                'access_status' => $accessStatus,
                'is_created' => $is_created,
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

                $childData = array_merge($childData, [
                    'height_cm' => $maternal->height_cm,
                    'blood_group' => $maternal->blood_group,
                    'emergency_contact' => $maternal->emergency_contact,
                    'latest_pregnancy' => $latestPregnancy ? [
                        'lmp' => $latestPregnancy->lmp,
                        'edd' => $latestPregnancy->edd,
                        'gravida' => $latestPregnancy->gravida,
                        'para' => $latestPregnancy->para,
                        'delivery_outcome' => $latestPregnancy->delivery_outcome,
                        'latest_record' => $latestRecord ? [
                            'visit_date' => $latestRecord->visit_date,
                            'weight' => $latestRecord->weight,
                            'blood_pressure_sys' => $latestRecord->blood_pressure_sys,
                            'blood_pressure_dia' => $latestRecord->blood_pressure_dia,
                            'health_status' => $latestRecord->health_status,
                        ] : null
                    ] : null
                ]);
            }

            $resource[] = $childData;
        }

        $links = array_diff_key($results, ['items' => true]);

        return [$resource, $links];
    }



    public function createMaternalProfile(
        int $parentId,
        int $phmId,
        float $height,
        ?string $bloodType,
        string $lmp,
    ): ?string {


        $existing = Maternal::query()
            ->where('parent_id', '=', $parentId)
            ->first();

        if ($existing) {
            return "Maternal profile already exists for this parent.";
        }


        $maternal = new Maternal();
        $maternal->parent_id = $parentId;
        $maternal->phm_id = $phmId;
        $maternal->type = 'pregnant';
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



        return null;
    }
}
