<?php

namespace App\Controllers\Doctor;

use Library\Framework\Http\Request;
use App\Services\ChildRecordService;

class ChildHealthController
{

    private $childRecordService;

    public function __construct()
    {
        $this->childRecordService = new ChildRecordService();
    }
    public function index(Request $request, int $id)
    {
        $records = $this->childRecordService->getChildRecordsByChildId($id);


        return view("doctor/childhealth", [
            "id" => $id,
            "records" => $records,
        ]);
    }

    public function vaccinationIndex(Request $request, int $id)
    {
        return view("doctor/vaccinationrecord", [
            "id" => $id,
        ]);
    }
}
