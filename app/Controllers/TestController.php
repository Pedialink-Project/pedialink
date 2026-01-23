<?php

namespace App\Controllers;

use Exception;
use Library\Framework\Http\Request;
use App\Services\TestService;

class TestController
{

    private $testService;

    public function __construct()
    {
        $this->testService = new TestService();
    }
    public function testPortal(Request $request)
    {

        $search = $request->input("search");
        $filters = $request->input("filters");


        [$testDetails,$links] = $this->testService->getAllTestDetails($search, $filters);
        return view('test/test', ['items' => $testDetails, 'links' => $links]);
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

    public function sendMail(Request $request)
    {
        $message = "Successfully sent email";
        try {
            mailer()->sendTemplate(
                'manimehalan400@gmail.com',
                'welcome',
                [
                    'appName' => 'Pedialink',
                    'name' => 'Mani',
                    'ctaUrl' => 'localhost',
                ],
                'Welcome Mail from Pedialink',
            );
        } catch (Exception $e) {
            $message = "Failed to send email";
        }

        return redirect(route('test.portal'))
            ->withMessage($message, "Email", "success");
    }
}