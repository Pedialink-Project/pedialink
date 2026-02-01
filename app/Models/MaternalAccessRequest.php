<?php

namespace App\Models;

use Library\Framework\Core\Model;

class MaternalAccessRequest extends Model
{
    protected static string $table = "maternal_access_requests";

    protected array $fillable = ["staff_id", "maternal_id", "reason_title", "accepted", "reason_description"];

    public function getMaternal()
    {
        $parent = ParentM::find($this->maternal_id);
        return $parent;
    }

    public function getStaff()
    {
        $staff = Staff::find($this->staff_id);
        return $staff;
    }
}