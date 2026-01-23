<?php

namespace App\Models;

use Library\Framework\Core\Model;

class ParentM extends Model
{
    protected static string $table = "parents";
    protected array $fillable = [
        "nic",
        "type",
        "address",
        "area_id",
        "verified",
        "date_of_birth",
        "birth_certificate",
        "marriage_certificate",
        "nic_copy"
    ];

    public function getArea()
    {
        return Area::find($this->area_id);
    }
}