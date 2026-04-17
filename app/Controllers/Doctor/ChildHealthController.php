<?php

namespace App\Controllers\Doctor;

use App\Models\Child;
use App\Services\VaccinationService;
use Library\Framework\Http\Request;
use App\Services\ChildRecordService;

class ChildHealthController
{
    private $childRecordService;
    private VaccinationService $vaccinationService;

    public function __construct()
    {
        $this->childRecordService = new ChildRecordService();
        $this->vaccinationService = new VaccinationService();
    }
    public function index(Request $request, int $id)
    {

        $search = $request->input("search");
        $filters = $request->input("filters");
        [$records, $links] = $this->childRecordService->getChildRecordsByChildId($id, $search, $filters);
        $name = $this->childRecordService->getChildNameById($id);

}