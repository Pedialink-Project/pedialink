<?php

namespace App\Models;

use Library\Framework\Core\Model;

class ParentChild extends Model
{
    protected static string $table = "parent_children";

    protected array $fillable = [
        "child_id",
        "parent_id"
    ];

    public function getChild()
    {
        return Child::find($this->child_id);
    }

    public function getParent()
    {
        return ParentM::find($this->parent_id);
    }
}