<?php

namespace App\Models;

use Library\Framework\Core\Model;

class Admin extends Model
{
    protected static string $table = "admins";
    protected array $fillable = ["id", "admin_type_id"];

    public function getAdminType()
    {
        return AdminType::find($this->admin_type_id)
            ->type;
    }
}