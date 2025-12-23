<?php

namespace App\Models;

use Library\Framework\Core\Model;

class MaternalRecord extends Model
{
    protected static string $table = "maternal_records";
    protected array $fillable = ["parent_id","staff_id", "visit_date", "trimester", "weight", "created_at","blood_pressure","blood_sugar","notes","health_status"];

    public function getMaternal(): object|null
    {
        return MaternalRecord::find($this->parent_id);
    }

    public function getStaff(): object|null
    {
        return Staff::find($this->staff_id);
    }
}