<?php

namespace App\Models;
use Library\Framework\Core\Model;

class Child extends Model{

    protected static string $table = "children";

    protected array $fillable = ["id","parent_id","phm_id","first_name","last_name","date_of_birth","gender","health_status"];
}

?>