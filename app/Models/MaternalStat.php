<?php

namespace App\Models;
use Library\Framework\Core\Model;

class MaternalStat extends Model{

protected static string $table = "maternal_stats";

protected array $fillable = ["maternal_id","visit_date","trimester","weight","height","bmi","blood_pressure","blood_sugar","fundal_height","notes"];


}



?>