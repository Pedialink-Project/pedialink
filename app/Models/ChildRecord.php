<?php

namespace App\Models;

use Library\Framework\Core\Model;

class ChildRecord extends Model
{
    protected static string $table = "child_records";
    protected array $fillable = ["child_id","staff_id", "visit_date", "age_recorded_at", "weight", "height","bmi","head_circumference","notes"];

    public function getChild(): object|null
    {
        return Child::find($this->child_id);
    }

    public function getStaff(): object|null
    {
        return Staff::find($this->staff_id);
    }
}