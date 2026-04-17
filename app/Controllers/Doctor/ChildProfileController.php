<?php

namespace App\Controllers\Doctor;

use App\Models\Area;
use App\Services\ChildService;
use Library\Framework\Http\Request;

class ChildProfileController
{
    private $childService;
    
    public function __construct()
    {
        $this->childService = new ChildService();
    }
    
     public function index(Request $request)
    {

        $search = $request->input('search');
        $filters = $request->input('filters');
        $areas = Area::query()->orderBy('code', 'ASC')->get();
        $areaFilters = [];

        foreach ($areas as $area) {
            $areaFilters[] = $area->code;
        }
}