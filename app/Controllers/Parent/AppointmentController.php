<?php

namespace App\Controllers\Parent;
use App\Services\AppointmentService;
use Library\Framework\Http\Request;

class AppointmentController
{

    private $appointmentService;

    public function __construct()
    {
        $this->appointmentService = new AppointmentService();
    }
    public function index()
    {

        return view("parent/appointments");
    }

 






}