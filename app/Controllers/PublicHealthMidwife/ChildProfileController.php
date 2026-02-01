<?php

namespace App\Controllers\PublicHealthMidwife;

use App\Models\Child;
use App\Services\ChildService;
use Library\Framework\Http\Request;
use App\Services\ChildRecordService;

class ChildProfileController
{
    public ChildService $childService;

    public function __construct()
    {
        $this->childService = new ChildService();
    }

    public function index(Request $request)
    {
        $children = $this->childService->getAllChildren();
        
        return view("phm/childprofiles", ['children' => $children]);
    }

    public function createChild(Request $request)
    {
        $name = $request->input('name');
        $areaId = (int)$request->input('area');
        $dob = $request->input('date_of_birth');
        $birthCertificate = $request->input('birth_certificate');
        $gender = $request->input('gender');

        $errors = $this->childService->validateChildProfile($name, $areaId, $dob, $gender, $birthCertificate);

        if (count($errors) > 0) {
            return redirect(route('phm.child.profiles'))
                ->withErrors($errors)
                ->withInput([
                    "name" => $name,
                    "area" => $areaId,
                    "date_of_birth" => $dob,
                    "gender" => $gender,
                    "birth_certificate" => $birthCertificate,
                ])
                ->with("create", true);
        }

        $this->childService->createChildProfile($name, $areaId, $dob, $gender, $birthCertificate);

        return redirect(route('phm.child.profiles'))
            ->withMessage(
                "Child profile was successfully created",
                "Success",
                "success",
            );

    }

    public function editChild(Request $request, int $id)
    {
        $name = $request->input('e_name');
        $areaId = (int)$request->input('e_area');
        $dob = $request->input('e_date_of_birth');
        $gender = $request->input('e_gender');
        $birthCertificate = $request->input('e_birth_certificate');

        $errors = $this->childService->validateChildProfile($name, $areaId, $dob, $gender, $birthCertificate, true);
        if (count($errors) > 0) {
            return redirect(route('phm.child.profiles'))
                ->withErrors($errors)
                ->withInput([
                    "e_name" => $name,
                    "e_area" => $areaId,
                    "e_date_of_birth" => $dob,
                    "e_gender" => $gender,
                    "e_birth_certificate" => $birthCertificate,
                ])
                ->with("edit", $id);
        }

        $this->childService->editChildProfile($id, $name, $areaId, $dob, $gender, $birthCertificate);

        return redirect(route('phm.child.profiles'))
            ->withMessage(
                "Changes successfully saved to the child profile",
                "Success",
                "success",
            );   
    }

    public function deleteChild(Request $request, int $id)
    {
        $error = $this->childService->validateDeleteProfile($id);

        if ($error !== NULL) {
            return redirect(route('phm.child.profiles'))
                ->withMessage(
                    $error,
                    "Error",
                    "error",
                );
        }

        $this->childService->deleteChildProfile($id);
        return redirect(route('phm.child.profiles'))
                ->withMessage(
                    "Deleted successfully",
                    "Success",
                    "success",
                );
    }
}