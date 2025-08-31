<?php

namespace App\Controllers;

use Library\Framework\Http\Request;

class TestController
{
    public function testPortal(Request $request)
    {
        return view('test/test');
    }
}