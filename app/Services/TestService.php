<?php

namespace App\Services;
use App\Models\Test;
use Library\Framework\Database\QueryBuilder;



class TestService
{

    private function applySearch(QueryBuilder $users, string $search)
    {
        $users->where('name', 'ILIKE', "$search%");

        return $users;
    }

    private function applyFilters(QueryBuilder $users, array $filters)
    {
        foreach ($filters as $filterName => $filterValue) {
            if ($filterValue && is_array($filterValue)) {
                $users->whereIn('category', $filterValue);
            }
        }

        return $users;
    }

    public function getAllTestDetails(?string $search,?array $filters)
    {
        $tests = Test::query();

   if ($search) {
            $tests = $this->applySearch($tests, $search);
        }

        if ($filters) {
            $tests = $this->applyFilters($tests, $filters);
        }

        $results = $tests
            ->orderBy('id', 'ASC')
            ->paginate(7);

        $resource = [];

        foreach ($results->items as $test) {
            $resource[] = [
                "id" => $test->id,
                "name" => $test->name,
                "category" => $test->category,
                "stock" => $test->stock,
                "price" => $test->price,
                "created_at" => $test->created_at,

            ];
        }

        $links = $results->toArray();


        return [$resource, $links];
    }

}



?>