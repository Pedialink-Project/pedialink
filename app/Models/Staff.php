<?php

namespace App\Models;
use Library\Framework\Core\Model;

class Staff extends Model
{

    protected static string $table = "staffs";

    protected array $fillable = ["id", "nic", "license_no"];



}


?>