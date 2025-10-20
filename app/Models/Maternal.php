<?php

namespace App\Models;
use Library\Framework\Core\Model;

class Maternal extends Model{
    
protected static string $table = "maternal";

protected array $fillable = ["parent_id"];

}



?>