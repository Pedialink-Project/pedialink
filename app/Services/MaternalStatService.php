<?php

namespace App\Services;
use App\Models\MaternalStat;
use App\Models\Maternal;
class MaternalStatService
{
    public function getAllMaternalStats()
    {
        $maternalStats = MaternalStat::all();

        $resource = [];
        foreach ($maternalStats as $stat) {
            $resource[] = [
                'id' => $stat->id,
                'maternal_id' => $stat->maternal_id,
                'visit_date' => $stat->visit_date,
                'trimester' => $stat->trimester,
                'bmi' => $stat->bmi,
                'weight' => $stat->weight,
                'height' => $stat->height,
                'blood_sugar' => $stat->blood_sugar,
                'blood_pressure' => $stat->blood_pressure,
                'health_status' => $stat->health_status,
                'fundal_height' => $stat->fundal_height,
                'notes' => json_decode($stat->notes),
            ];
        }


        return $resource;
    }



    public function getMaternalStatByMaternalId($id)
    {
        $maternalStats = MaternalStat::query()->where('maternal_id', '=',$id)->get();
        $healthStatus = Maternal::query()->where('id', '=',$id)->first()->health_status;
        $resource = [];
        foreach ($maternalStats as $stat) {
            $resource[] = [
                'id' => $stat->id,
                'maternal_id' => $stat->maternal_id,
                'visit_date' => $stat->visit_date,
                'trimester' => $stat->trimester,
                'bmi' => $stat->bmi,
                'weight' => $stat->weight,
                'height' => $stat->height,
                'blood_sugar' => $stat->blood_sugar,
                'blood_pressure' => $stat->blood_pressure,
                'health_status' => $healthStatus,
                'fundal_height' => $stat->fundal_height,
                'notes' => json_decode($stat->notes),
            ];
        }

        return $resource;
    }

}