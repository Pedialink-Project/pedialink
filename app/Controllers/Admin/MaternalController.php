<?php

namespace App\Controllers\Admin;

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
        return view("admin/maternal/access");
    }
}