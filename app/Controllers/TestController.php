<?php

namespace App\Controllers;

use Library\Framework\Http\Request;

class TestController
{
    public function testPortal(Request $request)
    {
        return view('test/test');
    }

    public function testCalendar(Request $request)
    {
        return view('test/calendartest');
    }

    public function testMessage(Request $request)
    {
        return redirect(route("home"))
            ->withMessage(
                "hello", 
                'Hello Bro', 
                'info', 
                ['link' => route('test.portal'), 'text' => 'Go to portal']
            );
    }
}