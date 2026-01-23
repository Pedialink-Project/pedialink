<?php

namespace App\Services\Admin;

use App\Models\Area;
use App\Models\Child;
use App\Models\ParentM;
use App\Models\PublicHealthMidwife;
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

    public function getAccessControlData(Child $child)
    {
        $data = [];

        if ($child) {
            # Incorrect, child must be linked to both parents
            # NOTE: currnetly only a child is linked to one parent
            $parentData = ParentM::find($child->parent_id);

            if ($parentData) {
                $data["parents"] = [
                    [
                        "id" => $parentData->id,
                        "name" => User::find($parentData->id)->name,
                        "type" => $parentData->type,
                    ],
                ];
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

        return $data;
    }
}