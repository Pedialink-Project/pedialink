<?php

namespace App\Controllers\PublicHealthMidwife;

use App\Services\MaternalRecordService;
use Library\Framework\Http\Request;

class MaternalHealthController
{
private $maternalRecordService;

    public function __construct()
    {
        $this->maternalRecordService = new MaternalRecordService();
    }
    public function index(Request $request, int $id)
    {

        $search = $request->input("search");
        $filters = $request->input("filters");
        [$records, $links] = $this->maternalRecordService->getMaternalRecordsByMaternalId($id, $search, $filters);
        $name = 'gh';

        return view("phm/maternalhealth", [
            "id" => $id,
            'name' => $name,
            "records" => $records,
            "links" => $links
        ]);
    }
}