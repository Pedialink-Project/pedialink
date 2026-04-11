<?php

namespace App\Controllers\Parent;

use App\Services\MaternalService;

class PregnancyController
{
    private MaternalService $maternalService;

    public function __construct()
    {
        $this->maternalService = new MaternalService();
    }

   
    public function myPregnancy()
    {
        $parentId = auth()->user()->id;

        [$maternal,$pregnacies,$records] = $this->maternalService->getPregnancyDetailsByParentId($parentId);

        return view('parent/my-pregnancy', [
            'maternal' => $maternal,
            'pregnancies' => $pregnacies,
            'records' => $records,
        ]);
    }
}
