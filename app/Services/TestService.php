<?php

namespace App\Services;
use App\Models\Test;



class TestService
{

    public function getAllTestDetails()
    {
        $tests = Test::query()->get();

        $resource = [];

        foreach ($tests as $test) {
            $resource[] = [
                "id" => $test->id,
                "name" => $test->name,
                "category" => $test->category,
                "stock" => $test->stock,
                "price" => $test->price,
                "created_at" => $test->created_at,

            ];
        }

        return $resource;
    }


    public function getSearchResults($search)
    {

        // $tests = Test::query()->get();

        // $searchResults = array_filter($tests, function ($row) use ($search) {
        //     $s = strtolower($search);
        //     return str_contains(strtolower($row['name']), $s)
        //         || str_contains(strtolower($row['category']), $s)
        //         || str_contains(strtolower($row['price']), $s);
        // });

        $like = "%$search%";

        $sql = "SELECT * FROM test
            WHERE CAST(name AS TEXT) ILIKE ?
            OR CAST(category AS TEXT) ILIKE ?
            OR CAST(price AS TEXT) ILIKE ?";


        $searchResults = Test::query()->rawGet($sql, [$like, $like, $like]);

        $resource = [];
        foreach ($searchResults as $row) {
            $resource[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'category' => $row['category'],
                'stock' => $row['stock'],
                'price' => $row['price'],
                'created_at' => $row['created_at'],
            ];
        }

        return $resource;
    }

    public function getFilteredResults($filters)
{
    $query = Test::query();

    foreach ($filters as $filterName => $filterValues) {
        if (!empty($filterValues) && is_array($filterValues)) {

            $column = strtolower($filterName);

            $values = array_map('strtolower', $filterValues);

            $query->whereIn($column, $values);
        }
    }

    $filteredResults = $query->get();

    $resource = [];
    foreach ($filteredResults as $row) {
        $resource[] = [
            'id' => $row->id,
            'name' => $row->name,
            'category' => $row->category,
            'stock' => $row->stock,
            'price' => $row->price,
            'created_at' => $row->created_at,
        ];
    }

    return $resource;
}


}



?>