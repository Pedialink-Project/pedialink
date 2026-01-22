<?php

namespace App\Controllers\Admin;

use App\Services\Admin\ChildService;
use Library\Framework\Http\Request;

class ChildController
{
    private ChildService $childService;

    public function __construct()
    {
        $this->childService = new ChildService();
    }

    public function overview(Request $request)
    {
        $search = $request->query("search") ?? '';
        [$children, $links]  = $this->childService->getChildren($search);
        return view("admin/child/overview", [
            "children" => $children,
            "links" => $links
        ]);
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