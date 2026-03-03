<?php

namespace App\Models;

use Library\Framework\Core\Model;

class Pregnancy extends Model
{
    protected static string $table = "pregnancy";
    protected array $fillable = ["maternal_id","end_at", "lmp", "edd", "gravida", "para", "delivery_outcome", "created_at"];

    public function getParent(): object|null
    {
        return Maternal::find($this->maternal_id);
    }

    
}