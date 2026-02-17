<?php

namespace App\Models;

use Library\Framework\Core\Model;

class MaternalRecord extends Model
{
    protected static string $table = "maternal_records";
    protected array $fillable = ["parent_id","staff_id", "visit_date", "trimester", "weight", "bmi", "created_at","blood_pressure","glucose","hemoglobin","fetal_heart_rate","fundal_height","notes","health_status"];

   
    public function getParent(): object|null
    {
        return ParentM::find($this->parent_id);
    }
    public function getStaff(): object|null
    {
        return Staff::find($this->staff_id);
    }
}