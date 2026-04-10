<?php

namespace App\Services\PublicHealthMidwife;


use Library\Framework\Database\QueryBuilder;
use App\Models\ChildAccessRequest;
use App\Models\Child;

class GrowthService
{
    public function getAllChildrenGrowthData($phmId)
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

     public function getChildGrowthDataByChildId(int $childId)
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
    JOIN child_records h ON h.child_id = c.id
    WHERE c.id = :childId
    ORDER BY h.visit_date
    ";

    $rows = QueryBuilder::rawGet($sql, [
        ':childId' => $childId
    ]);

    $child = [
        'id' => $childId,
        'name' => '',
        'bmi' => [],
        'height' => [],
        'weight' => [],
        'labels' => []
    ];

    foreach ($rows as $row) {

        $child['name'] = $row['child_name'];

        $child['labels'][] = date("M", strtotime($row['visit_date']));
        $child['bmi'][] = (float)$row['bmi'];
        $child['height'][] = (float)$row['height'];
        $child['weight'][] = (float)$row['weight'];
    }

    return $child;
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
            return $resource;
        }
    }

    public function getChildById(int $childId)
    {
        $child = Child::find($childId);

         $resource = [
            'id' => $child->id,
            'name' => $child->name,
        ];
        return $resource;
    }
}
