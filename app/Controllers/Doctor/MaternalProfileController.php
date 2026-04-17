<?php

namespace App\Controllers\Doctor;

use App\Models\Area;
use Library\Framework\Http\Request;
use App\Models\ParentM;
use App\Models\User;
use App\Services\MaternalService;

class MaternalProfileController
{
    protected $maternalService;

     public function __construct()
    {
        $this->maternalService = new MaternalService();
    }

    public function index(Request $request)
    {
        $search = $request->input("search");
        $filters = $request->input("filters");
        $areas = Area::query()->orderBy('code', 'ASC')->get();
        $areaFilters = [];

        foreach ($areas as $area) {
            $areaFilters[] = $area->code;
        }

        $doctorId = auth()->user()->id;
        [$maternals, $links] = $this->maternalService->getMaternalByDoctorId($doctorId, $search, $filters);

        return view("doctor/maternalprofile", ['maternals' => $maternals, 'links' => $links, 'areaFilters' => $areaFilters]);
    }
}