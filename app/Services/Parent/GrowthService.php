<?php

namespace App\Services\Parent;


use Library\Framework\Database\QueryBuilder;
use App\Models\ParentChild;

class GrowthService
{
   public function getGrowthData($parentId)
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
        JOIN parent_children pc ON pc.child_id = c.id
        JOIN child_records h ON h.child_id = c.id
        WHERE pc.parent_id = :parentId
        ORDER BY h.visit_date
        ";

        $rows = QueryBuilder::rawGet($sql, [
            ':parentId' => $parentId
        ]);

        $children = [];

        foreach ($rows as $row) {

            $childId = $row['child_id'];

            if (!isset($children[$childId])) {
                $children[$childId] = [
                    'name' => $row['child_name'],
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

      public function getLinkedChildrenListByParentId(int $parentId)
    {
        $childrenParent = ParentChild::query()->where('parent_id', '=', $parentId)->get();

        $resource = [];
        foreach ($childrenParent as $childParent) {
            $child = $childParent->getChild();
            $resource[] = [
                'id' => $child->id,
                'name' => $child->name,           
            ];
        }

        return $resource;
    }


   
}