<?php

namespace App\Models;

use Library\Framework\Core\Model;

class PublicHealthMidwife extends Model
{
    protected static string $table = "public_health_midwives";
    protected array $fillable = ["area_id"];

    public function getArea()
    {
        return Area::find($this->area_id);
    }

    public function getStaff()
    {
        return Staff::find($this->id);
    }
}