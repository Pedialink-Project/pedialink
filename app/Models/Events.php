<?php

namespace App\Models;

use Library\Framework\Core\Model;

class Events extends Model
{
    protected static string $table = "events";
    protected array $fillable = ["admin_id","title", "description", "purpose", "notes", "event_date", "event_time", "event_location", "max_count"];

    
    public function getAdmin(): object|null
    {
        return User::find($this->admin_id);
    }
}