<?php

namespace App\Controllers\Doctor;
use App\Services\MaternalStatService;

use Library\Framework\Http\Request;

class MaternalHealthController
{
    protected $MaternalStatService;

    public function __construct()
    {
        $this->MaternalStatService = new MaternalStatService();
    }

    public function index(Request $request, int $id)
    {
        
        $maternalStats = $this->MaternalStatService->getMaternalStatByMaternalId($id);
        return view("doctor/maternalhealth", [
            "items"=>$maternalStats
        ]);
    }
}