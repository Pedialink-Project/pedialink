<?php

namespace App\Models;

use Library\Framework\Core\Model;

class Doctor extends Model
{
    protected static string $table = "doctors";
    protected array $fillable = [];

    public function getStaff()
    {
        return Staff::find($this->id);
    }
}