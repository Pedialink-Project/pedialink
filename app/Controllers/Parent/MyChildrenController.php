<?php

namespace App\Controllers\Parent;
use App\Services\ChildService;
use Library\Framework\Http\Request;
use Library\Framework\Http\RedirectResponse;


class MyChildrenController
{

    private $childService;

    public function __construct()
    {

        $this->childService = new ChildService();

    }
    public function index()
    {
        $childDetails = $this->childService->getChildernByParentId(auth()->id());

        return view("parent/my-children", ['childDetails' => $childDetails]);
    }

    public function viewChildDetails(Request $request, $id)
    {
        $child = $this->childService->getChildernById($id);


        if (empty($child)) {
            return redirect('/parent/my-children')->withMessage(
                "Child Not Found",
                "No child record was found for the provided ID. Please verify the ID and try again.",
                "error",
            );
        }
        return view("parent/my-child-details", data: ['child' => $child]);

    }
}