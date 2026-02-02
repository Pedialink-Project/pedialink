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

        return view("doctor/childprofile", ["children" => $childern]);
    }

    public function requestAccess(Request $request)
    {
        $staffId = auth()->user()->id;
        $childId = $request->input("child_id");
        $reasonTitle = $request->input('reason_title');
        $reasonDescription = $request->input('reason_description');


        $error = $this->childService->requestChildAccess(
            $staffId,
            $childId,
            $reasonTitle,
            $reasonDescription
        );

        if ($error) {
            return redirect(route('doctor.childprofile'))->withMessage ($error, "Request Failed", "info");
        }

        return redirect(route('doctor.childprofile'))->withMessage(
            "Access request sent successfully",
            "Request Sent",
            "success"
        );
    }
}
