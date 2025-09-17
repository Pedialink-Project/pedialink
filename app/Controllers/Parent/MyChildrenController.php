<?php

namespace App\Controllers\Parent;
use Library\Framework\Http\Request;
use Library\Framework\Http\RedirectResponse;


class MyChildrenController
{
    private
    $childDetails = [
        [
            'id' => "CHD001",
            "image" => "",
            "name" => "Sara Johnson",
            "nickname" => "Baby Sara",
            "status" => "Good",
            "dob" => "22-10-2023",
            "age" => "2 Years old",
            "phm" => "Dr. Smith",
            "appointments" => 1,
            "vaccinations" => 0
        ],
        [
            "id" => "CHD002",
            "image" => "",
            "name" => "Sara Johnson",
            "nickname" => "Baby Sara",
            "status" => "Critical",
            "dob" => "22-10-2023",
            "age" => "2 Years old",
            "phm" => "Dr. Smith",
            "appointments" => 1,
            "vaccinations" => 0
        ],
        [
            "id" => "CHD003",
            "image" => "",
            "name" => "Sara Johnson",
            "nickname" => "Baby Sara",
            "status" => "Good",
            "dob" => "22-10-2023",
            "age" => "2 Years old",
            "phm" => "Dr. Smith",
            "appointments" => 1,
            "vaccinations" => 0
        ],

        [
            "id" => "CHD004",
            "image" => "",
            "name" => "Sara Johnson",
            "nickname" => "Baby Sara",
            "status" => "Critical",
            "dob" => "22-10-2023",
            "age" => "2 Years old",
            "phm" => "Dr. Smith",
            "appointments" => 1,
            "vaccinations" => 0
        ],
    ];

    public function index()
    {
        $childDetails = $this->childDetails;
        return view("parent/my-children",['childDetails' => $childDetails]);
    }

    public function viewChildDetails(Request $request, $id)
    {
        $child = current(array_filter($this->childDetails, function ($child) use ($id) {
            return $child['id'] === $id;
        }));
        
        if (empty($child)) {
            // Handle case where child with given ID is not found
            return redirect('/parent/my-children')->withErrors(['error' => 'Child not found.']);
        }
        return view("parent/my-child-details", data: ['child' => $child]);

    }
}