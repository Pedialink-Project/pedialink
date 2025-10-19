<?php

 namespace App\Models;

 use Library\Framework\Core\Model;

 class Patient extends Model {

    protected static string $table = "patients";

    protected array $fillable = ["patient_type"];

 }

 

 ?>
