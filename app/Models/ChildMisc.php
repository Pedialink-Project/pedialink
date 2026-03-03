<?php

namespace App\Models;

use Library\Framework\Core\Model;

class ChildMisc extends Model
{
    protected static string $table = "child_miscs";
    protected array $fillable = ["children_id", "parent_nic","accepeted"];

    public function getChild(): object|null
    {
        return Child::find($this->children_id);
    }

    public function getParent(): object|null
    {
        return ParentM::query()
            ->where("nic", "=", $this->parent_nic)
            ->first();
    }

}
