<?php

namespace App\Models;

use Library\Framework\Core\Model;

class ParentM extends Model
{
    protected static string $table = "parents";
    protected array $fillable = ["id", "nic", "type", "address", "area_id", "date_of_birth"];

    public function getArea()
    {
        return Area::find($this->area_id);
    }
}