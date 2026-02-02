<?php

namespace App\Services\Admin;

use App\Models\Area;
use App\Models\ParentM;
use App\Models\User;

class ParentApprovalService
{
    public function getPendingParentDetails()
    {
        $parents = ParentM::query()
            ->where('verified', '=', 0)
            ->where('birth_certificate', '!=', \PDO::NULL_NATURAL)
            ->where('marriage_certificate', '!=', \PDO::NULL_NATURAL)
            ->where('nic_copy', '!=', \PDO::NULL_NATURAL)
            ->orderBy('id', 'ASC')
            ->paginate(10)
            ->toArray();

        $resource = [];

        foreach ($parents['items'] as $parent) {
            $user = User::find($parent->id);
            $resource[] = [
                'id' => $parent->id,
                'name' => $user->name,
                'nic' => $parent->nic,
                'division' => Area::find($parent->area_id)->code,
                'type' => $parent->type,
                'created_at' => $user->created_at,
            ];
        }

        $links = array_diff_key($parents, ['items' => true]);
        return [$resource, $links];
    }
}