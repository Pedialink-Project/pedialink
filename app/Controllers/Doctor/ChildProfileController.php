<?php

namespace App\Controllers\Doctor;

use App\Services\ChildService;
use Library\Framework\Http\Request;

class ChildProfileController
{
    private $childService;

    public function __construct()
    {
        $this->childService = new ChildService();
    }

    public function index(Request $request)
    {
        $staffId = auth()->user()->id;
        $childern = $this->childService->getChildrenByStaffId($staffId);

        return view("doctor/childprofile",["children"=> $childern]);
    }
}
