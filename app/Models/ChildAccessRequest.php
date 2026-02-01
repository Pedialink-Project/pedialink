<?php
namespace App\Models;

use Library\Framework\Core\Model;

class ChildAccessRequest extends Model
{
    protected static string $table = "children_access_requests";

    protected array $fillable = ["staff_id", "child_id", "reason_title", "accepted", "reason_description"];

    public function getChild()
    {
        $child = Child::find($this->child_id);
        return $child;
    }

    public function getStaff()
    {
        $staff = Staff::find($this->staff_id);
        return $staff;
    }
}