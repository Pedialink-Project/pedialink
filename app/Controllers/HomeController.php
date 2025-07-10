<?php

namespace App\Controllers;

use Library\Framework\Http\Response;

class HomeController
{
    public function home()
    {
        // return (new Response(['hello' => 'meow'], 200))->asJson();
        return new Response("hello CS 28", 200);
    }
}