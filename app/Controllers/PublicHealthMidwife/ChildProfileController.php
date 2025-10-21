<?php

namespace App\Controllers\PublicHealthMidwife;

use App\Models\Child;
use App\Services\ChildService;
use Library\Framework\Http\Request;

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
        $division = $request->input('division');
        $dob = $request->input('dob');
        $gender = $request->input('gender');

        $errors = $this->childService->validateChildProfile($name, $division, $dob, $gender);

        if (count($errors) > 0) {
            return redirect(route('phm.child.profiles'))
                ->withErrors($errors)
                ->withInput([
                    "name" => $name,
                    "division" => $division,
                    "dob" => $dob,
                    "gender" => $gender,
                ])
                ->with("create", true);
        }

        $this->childService->createChildProfile($name, $division, $dob, $gender);

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
        $division = $request->input('e_division');
        $dob = $request->input('e_dob');
        $gender = $request->input('e_gender');

        $errors = $this->childService->validateChildProfile($name, $division, $dob, $gender, true);

        if (count($errors) > 0) {
            return redirect(route('phm.child.profiles'))
                ->withErrors($errors)
                ->withInput([
                    "e_name" => $name,
                    "e_division" => $division,
                    "e_dob" => $dob,
                    "e_gender" => $gender,
                ])
                ->with("edit", $id);
        }

        $this->childService->editChildProfile($id, $name, $division, $dob, $gender);

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