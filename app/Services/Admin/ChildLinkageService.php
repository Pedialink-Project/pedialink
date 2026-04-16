<?php

namespace App\Services\Admin;

use App\Models\ChildMisc;
use App\Models\ParentChild;
use App\Models\ParentM;

class ChildLinkageService
{
    public function getLinkageData()
    {
        $data = [];

        $childMiscs = ChildMisc::query()
            ->leftJoin("parents", "parents.nic", "=", "child_miscs.parent_nic")
            ->whereNotNull("parents.nic")
            ->where("accepted", "=", 0)
            ->orderBy("id", "ASC")
            ->paginate(10)
            ->toArray();

        foreach ($childMiscs["items"] as $misc) {
            $child = $misc->getChild();
            $parent = $misc->getParent();
            $user = $parent ? $parent->getUser() : null;

            
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

        $links = array_diff_key($childMiscs, ['items' => true]);

        return [$data, $links];
    }

    public function approveLinkage(int $id)
    {
        $childMisc = ChildMisc::find($id);
        if ($childMisc) {
            $childMisc->accepted = 1;
            $childMisc->save();


            $parentChild = new ParentChild();
            $parentChild->parent_id = $childMisc->getParent()->id;
            $parentChild->child_id = $childMisc->children_id;
            $parentChild->save();
            return true;
        }
        return false;
    }
    public function denyLinkage(int $id)
    {
        $childMisc = ChildMisc::find($id);
        if ($childMisc) {
            $childMisc->delete();
            return true;
        }
        return false;
    }
}