<?php

namespace App\Services\PublicHealthMidwife;


use Library\Framework\Database\QueryBuilder;
use App\Models\ChildAccessRequest;
use App\Models\Child;

class GrowthService
{
    public function getGrowthData($phmId)
    {
        $sql = "
SELECT 
    c.id AS child_id,
    c.name AS child_name,
    h.visit_date,
    h.height,
    h.weight,
    h.bmi
FROM children c
JOIN children_access_requests car ON car.child_id = c.id
JOIN child_records h ON h.child_id = c.id
WHERE car.staff_id = :phmId
AND car.accepted = true
ORDER BY h.visit_date
";

        $rows = QueryBuilder::rawGet($sql, [
            ':phmId' => $phmId
        ]);

        $children = [];

        foreach ($rows as $row) {

            $childId = $row['child_id'];

            if (!isset($children[$childId])) {
                $children[$childId] = [
                    'name' => $row['child_name'],
                    'id' => $childId,
                    'bmi' => [],
                    'height' => [],
                    'weight' => [],
                    'labels' => []
                ];
            }

            $children[$childId]['labels'][] = date("M", strtotime($row['visit_date']));
            $children[$childId]['bmi'][] = (float)$row['bmi'];
            $children[$childId]['height'][] = (float)$row['height'];
            $children[$childId]['weight'][] = (float)$row['weight'];
        }

        return array_values($children);
    }

    public function getChildrenByPhmId(int $phmId,)
    {

        $requests = ChildAccessRequest::query()
            ->where('staff_id', '=', $phmId)
            ->where('accepted', '=', true)
            ->get();

        foreach ($requests as $child) {


            $childData = $child->getChild();
            $resource[] = [
                'id' => $childData->id,
                'name' => $childData->name,
            ];
        }
        return $resource;
    }
}
