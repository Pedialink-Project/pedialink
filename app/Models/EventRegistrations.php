<?php

namespace App\Models;

use Library\Framework\Core\Model;

class EventRegistrations extends Model
{
    protected static string $table = "events_registrations";
    protected array $fillable = ["event_id","user_id", "name", "email", "phone", "booking_status", "cancel_reason", "canceld_at", "registration_date"];

    
    
}