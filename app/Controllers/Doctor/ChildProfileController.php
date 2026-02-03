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

        $search = $request->input('search');
        $filters = $request->input('filters');

        $staffId = auth()->user()->id;
        [$children,$links] = $this->childService->getChildrenByStaffId($staffId, $search, $filters);
        $unacessedChildren = $this->childService->getUnaccessedChildrenForStaff($staffId);
        $accessReasons = config('data.accessReason');

        return view("doctor/childprofile", ["children" => $children, "unacessedChildren" => $unacessedChildren, "accessReasons" => $accessReasons]);
    }

    public function requestAccess(Request $request)
    {
        $staffId = auth()->user()->id;
        $childId = $request->input("child_id");
        $reasonTitle = $request->input('reason_title');
        $reasonDescription = $request->input('reason_description');

        $validateError = $this->childService->validateRequestAccess($childId, $reasonTitle, $reasonDescription);
        if (count(value: $validateError) !== 0) {
            return redirect(route("doctor.child.profiles"))
                ->withInput([
                    "child_id" => $childId,
                    "reason_title" => $reasonTitle,
                    "reason_description" => $reasonDescription,

                ])
                ->withErrors($validateError)
                ->with("request", true);
        }


        $error = $this->childService->requestChildAccess(
            $staffId,
            $childId,
            $reasonTitle,
            $reasonDescription
        );

        if ($error) {
            return redirect(route('doctor.child.profiles'))->withMessage($error, "Request Failed", "info");
        }

        return redirect(route('doctor.child.profiles'))->withMessage(
            "Access request sent successfully",
            "Request Sent",
            "success"
        );
    }

    public function cancelAccessRequest(Request $request,$id)
    {
        $staffId = auth()->id();

        $error = $this->childService->cancelChildAccessRequest($staffId, $id);

        if ($error) {
            return redirect(route('doctor.child.profiles'))->withMessage('', $error, 'error');
        }

        return redirect(route('doctor.child.profiles'))->withMessage('Request Cancelled', 'Access request cancelled successfully','success');
    }
}
