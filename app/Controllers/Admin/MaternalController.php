<?php

namespace App\Controllers\Admin;

use App\Models\MaternalAccessRequest;
use App\Services\Admin\MaternalService;
use Library\Framework\Http\Request;

class MaternalController
{
    private MaternalService $maternalService;

    public function __construct()
    {
        $this->maternalService = new MaternalService();
    }

    public function overview(Request $request)
    {
        $search = $request->query("search") ?? "";
        [$maternal, $links] = $this->maternalService->getMaternalData($search);

        return view("admin/maternal/overview", [
            "maternal" => $maternal,
            "links" => $links
        ]);
    }

    public function accessRequests()
    {
        [$accessRequests, $links] = $this->maternalService->getAccessRequestData();

        return view("admin/maternal/access", [
            "accessRequests" => $accessRequests,
            "links" => $links
        ]);
    }

    public function approveAccessRequest(Request $request, int $id)
    {
        /** @var MaternalAccessRequest */
        $accessRequest = MaternalAccessRequest::find($id);
        
        $accessRequest->accepted = 1;
        $accessRequest->save();

        return redirect(route("admin.maternal.access.requests"))
            ->withMessage(
                "Access approval granted successfully", 
                "Success", 
                "success"
            );
    }

    public function denyAccessRequest(Request $request, int $id)
    {
        $accessRequest = MaternalAccessRequest::find($id);
        
        $accessRequest->delete();

        return redirect(route("admin.maternal.access.requests"))
            ->withMessage(
                "Access approval rejected successfully",
                "Success",
                "success"
            );
    }
}