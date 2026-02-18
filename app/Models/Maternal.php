<?php

namespace App\Models;

use Library\Framework\Core\Model;

class Maternal extends Model
{
    protected static string $table = "maternal";
    protected array $fillable = [
        "parent_id",
        "type"
    ];

    public function getParent()
    {
        return ParentM::find($this->parent_id);
    }

    public function getUser()
    {
        return User::find($this->id);
    }
}