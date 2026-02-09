<?php

namespace App\Controllers\Admin;

use App\Models\Child;
use App\Models\ChildAccessRequest;
use App\Services\Admin\ChildAccessRequestsService;
use App\Services\Admin\ChildService;
use Library\Framework\Http\Request;

class ChildController
{
    private ChildService $childService;
    private ChildAccessRequestsService $childAccessRequestsService;

    public function __construct()
    {
        $this->childService = new ChildService();
        $this->childAccessRequestsService = new ChildAccessRequestsService();
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
        $child = Child::find((int)$id);
        if (!$child) {
            return view("error/404");
        }

        $page = $request->query("page") ?? 1;

        [$accessData, $links] = $this->childService->getAccessControlData(
            $child, (int)$page
        );

        return view("admin/child/control", [
            "id" => $id,
            "name" => $child->name,
            "primaryAccess" => $accessData["parents"] ?? [],
            "secondaryAccess" => $accessData["phm"] ?? [],
            "staffAccess" => $accessData["staff"] ?? [],
            "links" => $links
        ]);
    }

    public function removeLinkage(Request $request, int $id)
    {
        $child = Child::find($id);
        $data = [
            "id" => $request->input("id") ?? "",
            "type" => $request->input("type") ?? ""
        ];

        if ($child) {
            if ($data["type"] === "phm") {
                $child->phm_id = null;
                $child->save();
            } else if ($data["type"] === "parent") {
                $child->parent_id = null;
                $child->save();
            }

            return redirect(route("admin.child.access.control", ["id" => $id]))
                ->withMessage("Successfully removed link", "Success", "success");
        }

        return redirect(route("admin.child.access.control", ["id" => $id]))
            ->withMessage("Failed to removed link", "Fail", "error");
    }

    public function linkageRequests(Request $request)
    {
        return view("admin/child/linkage");
    }

    public function accessRequests(Request $request)
    {
        [$accessRequests, $links] = $this->childAccessRequestsService
            ->getAccessRequestsData();

        return view("admin/child/access", [
            "accessRequests" => $accessRequests,
            "links" => $links
        ]);
    }

    public function approveAccessRequest(Request $request, int $id)
    {
        /** @var ChildAccessRequest */
        $accessRequest = ChildAccessRequest::find($id);
        
        $accessRequest->accepted = 1;
        $accessRequest->save();

        return redirect(route("admin.child.access.requests"))
            ->withMessage(
                "Access approval granted successfully", 
                "Success", 
                "success"
            );
    }

    public function denyAccessRequest(Request $request, $id)
    {
        $accessRequest = ChildAccessRequest::find($id);
        
        $accessRequest->delete();

        return redirect(route("admin.child.access.requests"))
            ->withMessage(
                "Access approval rejected successfully",
                "Success",
                "success"
            );
    }
}