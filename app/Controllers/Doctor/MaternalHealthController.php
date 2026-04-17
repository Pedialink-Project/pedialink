<?php

namespace App\Controllers\Doctor;
use App\Services\MaternalRecordService;
use Library\Framework\Http\Request;
use App\Models\ParentM;


class MaternalHealthController
{
    protected $maternalRecordService;

    public function __construct()
    {
        $this->maternalRecordService = new MaternalRecordService();
    }

    public function index(Request $request, int $id)
    {

        $search = $request->input("search");
        $filters = $request->input("filters");
        [$records, $links] = $this->maternalRecordService->getMaternalRecordsByMaternalId($id, $search, $filters);
        $name = $this->maternalRecordService->getMaternalNameByMaternalId($id);

        return view("doctor/maternalhealth", [
            "id" => $id,
            'name' => $name,
            "records" => $records,
            "links" => $links
        ]);
    }

}