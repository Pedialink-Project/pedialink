<?php

namespace App\Controllers\Doctor;

use Library\Framework\Http\Request;
use App\Services\MaternalService;

class MaternalProfileController
{

    protected $maternalService;

    public function __construct()
    {
        $this->maternalService = new MaternalService();
    }

    public function index(Request $request)
    {
        $maternals = $this->maternalService->getDoctorMaternalDetails();

        return view("doctor/maternalprofile", ['items' => $maternals]);
    }
}