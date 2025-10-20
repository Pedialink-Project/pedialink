<?php

namespace App\Controllers\PublicHealthMidwife;

use Library\Framework\Http\Request;

class ChildHealthController
{
    public function index(Request $request, int $id)
    {
        return view("phm/childhealth", [
            "id" => $id,
        ]);
    }
}