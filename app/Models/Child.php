<?php

namespace App\Models;

use Library\Framework\Core\Model;

class Child extends Model
{
    protected static string $table = "children";
    protected array $fillable = ["parent_id","phm_id", "date_of_birth", "gender", "birth_certificate", "blood_type","area_id"];

    
    public function getParent(): object|null
    {
        return ParentM::find($this->parent_id);
    }
    
    public function getPHM(): object|null
    {
        return PublicHealthMidwife::find($this->phm_id);
    }
    public function getArea(): object|null
    {
        return Area::find($this->area_id);
    }
}