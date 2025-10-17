<?php

namespace App\Controllers\Admin;

use Library\Framework\Http\Request;

class ChildController
{
    public function overview(Request $request)
    {
        return view("admin/child/overview");
    }

    public function accessControl(Request $request, int $id)
    {
        return view("admin/child/control", [
            "id" => $id,
            "name" => "Nancy Jenkins"
        ]);
    }

    public function linkageRequests(Request $request)
    {
        return view("admin/child/linkage");
    }

    public function accessRequests(Request $request)
    {
        return view("admin/child/access");
    }
}