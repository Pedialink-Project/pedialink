<?php

namespace App\Models;
use Library\Framework\Core\Model;

class Staff extends Model
{

    protected static string $table = "staffs";

    protected array $fillable = ["id", "nic", "license_no"];


    public function getUser()
    {
        $user = User::find($this->id);

        return $user;
    }

    public function getRole()
    {
        /**
         * @var User
         */
        $user = $this->getUser();

        if ($user->isDoctor()) {
            return Doctor::find($this->id);
        } else if ($user->isPublicHealthMidwife()) {
            return PublicHealthMidwife::find($this->id);
        }

        return null;
    }
}


?>