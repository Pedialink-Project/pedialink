<?php

namespace App\Controllers\Doctor;

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
        $doctorId = auth()->user()->id;
        [$maternals, $links] = $this->maternalService->getMaternalByDoctorId($doctorId, $search, $filters);
        $unaccesedMaternals = $this->maternalService->getUnaccessedMaternalForStaff($doctorId);
        $accessReasons = config('data.accessReason');

        return view("doctor/maternalprofile", ['maternals' => $maternals, 'links' => $links, 'unacessedMaternals' => $unaccesedMaternals, 'accessReasons' => $accessReasons]);
    }
}
