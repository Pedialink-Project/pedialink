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
        return view("phm/childprofiles", ['children' => $children, 'links' => $links]);
    }

    public function createChild(Request $request)
    {
        $name = $request->input('name');
        $dob = $request->input('date_of_birth');
        $birthCertificate = $request->input('birth_certificate');
        $gender = $request->input('gender');
        $bloodType = $request->input('blood_type');
        $mother_nic = $request->input('mother_nic');
        $father_nic = $request->input('father_nic');

        $errors = $this->childService->validateChildProfile($name, $dob, $gender, $birthCertificate, $bloodType, $mother_nic, $father_nic);

        if (count($errors) > 0) {
            return redirect(route('phm.child.profiles'))
                ->withErrors($errors)
                ->withInput([
                    "name" => $name,
                    "date_of_birth" => $dob,
                    "gender" => $gender,
                    "birth_certificate" => $birthCertificate,
                    "blood_type" => $bloodType,
                    "mother_nic" => $mother_nic,
                    "father_nic" => $father_nic,
                ])
                ->with("create", true);
        }

        $this->childService->createChildProfile($name, $dob, $gender, $birthCertificate, $bloodType, $mother_nic, $father_nic);

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
        $dob = $request->input('e_date_of_birth');
        $gender = $request->input('e_gender');
        $bloodType = $request->input('e_blood_type');
       
        $errors = $this->childService->validateChildProfile($name, $dob, $gender, null, $bloodType, null, null, true);

        if (count($errors) > 0) {
            return redirect(route('phm.child.profiles'))
                ->withErrors($errors)
                ->withInput([
                    "e_name" => $name,
                    "e_date_of_birth" => $dob,
                    "e_gender" => $gender,
                    "e_blood_type" => $bloodType,
                ])
                ->with("edit", $id);
        }

        $this->childService->editChildProfile($id, $name, $dob, $gender, $bloodType);

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

    public function ArchiveChild(Request $request, int $id)
    {
        $archiveReason = $request->input('archive_reason') ?? '';

        $error = $this->childService->validateArchiveProfile($id, $archiveReason);

        if ($error !== NULL) {
            return redirect(route('phm.child.profiles'))
                ->withMessage(
                    $error,
                    "Error",
                    "error",
                );
        }

        $this->childService->archiveChildProfile($id, $archiveReason);
        return redirect(route('phm.child.profiles'))
                ->withMessage(
                    "Child profile archived successfully",
                    "Success",
                    "success",
                );
    }

    public function viewArchivedChildren(Request $request)
    {
        $archivedChildren = $this->childService->getArchivedChildren();
        
        return view("phm/archive", ['children' => $archivedChildren]);
    }

    public function restoreChild(Request $request, int $id)
    {
        $error = $this->childService->validateUnarchiveProfile($id);

        if ($error !== NULL) {
            return redirect(route('phm.child.archived'))
                ->withMessage(
                    $error,
                    "Error",
                    "error",
                );
        }

        $this->childService->unarchiveChildProfile($id);
        return redirect(route('phm.child.archived'))
                ->withMessage(
                    "Child profile restored successfully",
                    "Success",
                    "success",
                );
    }
}
