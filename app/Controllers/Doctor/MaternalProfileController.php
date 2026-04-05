<?php

namespace App\Controllers\Doctor;

use Library\Framework\Http\Request;
use App\Models\ParentM;
use App\Models\User;
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
        $search = $request->input("search");
        $filters = $request->input("filters");
        $doctorId = auth()->user()->id;
        [$maternals, $links] = $this->maternalService->getMaternalByDoctorId($doctorId, $search, $filters);

        return view("doctor/maternalprofile", ['maternals' => $maternals, 'links' => $links]);
    }

    public function requestAccess(Request $request)
    {
        $staffId = auth()->user()->id;
        $maternalId = $request->input("maternal_id");
        $reasonTitle = $request->input('reason_title');
        $reasonDescription = $request->input('reason_description');

        $validateError = $this->maternalService->validateRequestAccess($maternalId, $reasonTitle, $reasonDescription);
        if (count(value: $validateError) !== 0) {
            return redirect(route("doctor.maternal.profiles"))
                ->withInput([
                    "maternal_id" => $maternalId,
                    "reason_title" => $reasonTitle,
                    "reason_description" => $reasonDescription,

                ])
                ->withErrors($validateError)
                ->with("request", true);
        }


        $error = $this->maternalService->requestMaternalAccess(
            $staffId,
            $maternalId,
            $reasonTitle,
            $reasonDescription
        );

        if ($error) {
            return redirect(route('doctor.maternal.profiles'))->withMessage($error, "Request Failed", "info");
        }

        return redirect(route('doctor.maternal.profiles'))->withMessage(
            "Access request sent successfully",
            "Request Sent",
            "success"
        );
    }

     public function cancelAccessRequest(Request $request,$id)
    {
        $staffId = auth()->id();

        $error = $this->maternalService->cancelMaternalAccessRequest($staffId, $id);

        if ($error) {
            return redirect(route('doctor.maternal.profiles'))->withMessage('', $error, 'error');
        }

        return redirect(route('doctor.maternal.profiles'))->withMessage('Request Cancelled', 'Access request cancelled successfully','success');
    }
}
