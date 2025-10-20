<?php

namespace App\Controllers\Doctor;

use Library\Framework\Http\Request;

class ChildHealthController
{
    public function index(Request $request, int $id)
    {
        return view("doctor/childhealth", [
            "id" => $id,
        ]);
    }

    public function vaccinationIndex(Request $request, int $id)
    {
        return view("doctor/vaccinationrecord", [
            "id" => $id,
        ]);
    }
}