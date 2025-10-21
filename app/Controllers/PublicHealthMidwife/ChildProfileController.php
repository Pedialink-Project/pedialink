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
}