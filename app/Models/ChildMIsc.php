<?php

namespace App\Models;

use Library\Framework\Core\Model;

class ChildMisc extends Model
{
    protected static string $table = "child_miscs";
    protected array $fillable = ["children_id", "parent_nic"];

    public function getChild(): object|null
    {
        return Child::find($this->child_id);
    }

    public function getStaff(): object|null
    {
        return ParentM::find($this->parent_nic);
    }
}
