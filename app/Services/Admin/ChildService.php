<?php

namespace App\Services\Admin;

use App\Models\Area;
use App\Models\Child;
use App\Models\ChildAccessRequest;
use App\Models\ParentChild;
use App\Models\ParentM;
use App\Models\PublicHealthMidwife;
use App\Models\Staff;
use App\Models\User;

class ChildService
{
    public function getChildren(string $search)
    {
        $children = Child::query()
            ->where("name", "ILIKE", "{$search}%")
            ->orderBy('id', 'ASC')
            ->paginate(10)
            ->toArray();

        $resource = [];

        foreach ($children['items'] as $child) {
            $assignedPhm = PublicHealthMidwife::find($child->phm_id);
            $resource[] = [
                'id' => $child->id,
                'name' => $child->name,
                'date_of_birth' => $child->date_of_birth,
                'phm' => [
                    'id' => $assignedPhm->id,
                    'name' => User::find($assignedPhm->id)->name,
                ],
                'created_at' => $child->created_at,
                'division' => Area::find($child->area_id)->code ?? '',
            ];
        }

        $links = array_diff_key($children, ['items' => true]);

        return [$resource, $links];
    }

    public function getAccessControlData(Child $child, int $page)
    {
        $data = [];

        if ($child) {
            if ($page === 1) {
                $parentData = ParentChild::query()->where("child_id", "=", $child->id)->get();
    
                if ($parentData) {
                    foreach ($parentData as $parent) {
                        $parentDetails = ParentM::find($parent->parent_id);
                        $data["parents"][] = [
                            "id" => $parentDetails->id,
                            "name" => User::find($parentDetails->id)->name,
                            "type" => $parentDetails->type,
                        ];
                    }
                }
    
                $phmData = PublicHealthMidwife::find($child->phm_id);
    
                if ($phmData) {
                    $data["phm"] = [
                        [
                            "id" => $phmData->id,
                            "name" => User::find($phmData->id)->name,
                            "role" => "Public Health Midwife"
                        ],
                    ];
                }
            }

            $staffAccessControl = ChildAccessRequest::query()
                ->where("child_id", "=", $child->id)
                ->where("accepted", "=", 1)
                ->orderBy('id', 'ASC')
                ->paginate($page === 1 ? 6 : 9)
                ->toArray();

            if ($staffAccessControl) {
                foreach ($staffAccessControl['items'] as $accessRequest) {
                    $staffDetails = Staff::find($accessRequest->phm_id);
                    $userDetails = User::find($staffDetails->id);
                    $data["staff"][] = [
                        "id" => $staffDetails->id,
                        "name" => $userDetails->name,
                        "role" => $userDetails->role ?? "Staff"
                    ];
                }
            }

            $links = array_diff_key($staffAccessControl, ['items' => true]);

        }

        return [$data, $links];
    }
}