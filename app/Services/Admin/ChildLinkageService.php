<?php

namespace App\Services\Admin;

use App\Models\ChildMisc;

class ChildLinkageService
{
    public function getLinkageData()
    {
        $data = [];

        $childMiscs = ChildMisc::query()
            ->orderBy("id", "ASC")
            ->paginate(10)
            ->toArray();

        if ($childMiscs && count($childMiscs["items"]) > 0) {
            foreach ($childMiscs["items"] as $misc) {
                $child = $misc->getChild();
                $parent = $misc->getParent();
                $user = $parent ? $parent->getUser() : null;

                if ($child && $parent) {
                    $data[] = [
                        "id" => $misc->id,
                        "child" => [
                            "id" => $child->id,
                            "name" => $child->name,
                            "parent_count" => count($child->getParents() ?? []),
                            "division" => $child->getArea()?->code,
                        ],
                        "parent" => [
                            "id" => $parent->id,
                            "name" => $user ? $user->name : null,
                            "nic" => $parent->nic,
                            "type" => $parent->type,
                            "address" => $parent->address,
                            "created_at" => $user ? $user->created_at : null,
                            "division" => $parent->getArea()?->code,
                        ],
                        "accepted" => $misc->accepted
                    ];
                }
            }
        }

        $links = array_diff_key($childMiscs, ['items' => true]);

        return [$data, $links];
    }
}