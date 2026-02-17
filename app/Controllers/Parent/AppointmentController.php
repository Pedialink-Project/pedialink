<?php

namespace App\Controllers\Parent;
use App\Services\AppointmentService;
use Library\Framework\Http\Request;

class AppointmentController
{

    public function myAppointments()
    {

        return view("parent/my-appointments");
    }

    public function childAppointments()
    {
        return view("parent/child-appointments");
    }

}