<?php

namespace App\Helpers;
use App\Models\Area;
use Library\Framework\Database\QueryBuilder;



trait AreaHelper
{


    public function getAllAreaDetails()
    {
        $areas = Area::query()->get();

        $resource = [];

        foreach ($areas as $area) {
            $resource[] = [
                "id" => $area->id,
                "name" => $area->code,
                
            ];
        }



        return $resource;
    }

}


        
?>