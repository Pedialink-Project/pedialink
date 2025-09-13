<?php

namespace App\Controllers\Parent;

class AppointmentController
{
    public function index()
    {
        return view("parent/appointments");
    }
   public function requestAppointment()
    {
        return view("parent/request-appointment");
    }
 
}