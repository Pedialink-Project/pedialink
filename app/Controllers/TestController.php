<?php

namespace App\Controllers;

use Library\Framework\Http\Request;
use App\Services\TestService;

class TestController
{

    private $testService;

    public function __construct( )
    {
        $this->testService = new TestService();
    }
    public function testPortal(Request $request)
    {
        $testDetails = $this->testService->getAllTestDetails();
        return view('test/test', ['items' => $testDetails]);
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