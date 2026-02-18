<?php

namespace App\Models;

use Library\Framework\Core\Model;

class Maternal extends Model
{
    protected static string $table = "maternal";
    protected array $fillable = ["parent_id","type", "height", "blood_group", "created_at"];

    public function getParent(): object|null
    {
        return ParentM::find($this->parent_id);
    }

    public function getUser()
    {
        return User::find($this->id);
    }
}