<?php

namespace App\Controllers\PublicHealthMidwife;

use App\Models\Child;
use App\Services\ChildService;
use App\Helpers\AreaHelper;
use Library\Framework\Http\Request;

class ChildProfileController
{
    use AreaHelper;
    public ChildService $childService;

    public function __construct()
    {
        $this->childService = new ChildService();
    }

    public function index(Request $request)
    {
        $search = $request->input("search");
        $filters = $request->input("filters");
        $phmId = auth()->user()->id;
        [$children, $links] = $this->childService->getChildrenByPhmId($phmId, $search, $filters);
        $areas = $this->getAllAreaDetails();
        return view("phm/childprofiles", ['children' => $children, 'areas' => $areas, 'links' => $links]);
    }

    public function createChild(Request $request)
    {
        $name = $request->input('name');
        $areaId = $request->input('area');
        $dob = $request->input('date_of_birth');
        $birthCertificate = $request->input('birth_certificate');
        $gender = $request->input('gender');
        $bloodType = $request->input('blood_type');
        $mother_nic = $request->input('mother_nic');
        $father_nic = $request->input('father_nic');

        $errors = $this->childService->validateChildProfile($name, $areaId, $dob, $gender, $birthCertificate, $bloodType, $mother_nic, $father_nic);

        if (count($errors) > 0) {
            return redirect(route('phm.child.profiles'))
                ->withErrors($errors)
                ->withInput([
                    "name" => $name,
                    "area" => $areaId,
                    "date_of_birth" => $dob,
                    "gender" => $gender,
                    "birth_certificate" => $birthCertificate,
                    "blood_type" => $bloodType,
                    "mother_nic" => $mother_nic,
                    "father_nic" => $father_nic,
                ])
                ->with("create", true);
        }

        $this->childService->createChildProfile($name, $areaId, $dob, $gender, $birthCertificate, $bloodType, $mother_nic, $father_nic);

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
        $areaId = $request->input('e_area');
        $dob = $request->input('e_date_of_birth');
        $gender = $request->input('e_gender');
        $bloodType = $request->input('e_blood_type');
        $errors = $this->childService->validateChildProfile($name, $areaId, $dob, $gender, null, $bloodType, null, true);

        if (count($errors) > 0) {
            return redirect(route('phm.child.profiles'))
                ->withErrors($errors)
                ->withInput([
                    "e_name" => $name,
                    "e_area" => $areaId,
                    "e_date_of_birth" => $dob,
                    "e_gender" => $gender,
                    "e_blood_type" => $bloodType,
                ])
                ->with("edit", $id);
        }

        $this->childService->editChildProfile($id, $name, $areaId, $dob, $gender, $bloodType);

        return redirect(route('phm.child.profiles'))
            ->withMessage(
                "Changes successfully saved to the child profile",
                "Success",
                "success",
            );
    }

    // public function deleteChild(Request $request, int $id)
    // {
    //     $error = $this->childService->validateDeleteProfile($id);

    //     if ($error !== NULL) {
    //         return redirect(route('phm.child.profiles'))
    //             ->withMessage(
    //                 $error,
    //                 "Error",
    //                 "error",
    //             );
    //     }

    //     $this->childService->deleteChildProfile($id);
    //     return redirect(route('phm.child.profiles'))
    //             ->withMessage(
    //                 "Deleted successfully",
    //                 "Success",
    //                 "success",
    //             );
    // }
}
