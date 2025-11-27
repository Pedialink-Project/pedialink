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

}

?>