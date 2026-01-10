<?php

use App\Controllers\TestController;

/* Contains routes for testing purposes */

return [
    ['GET', '/test', [TestController::class, 'testPortal'], 'test.portal'],
    ['GET', '/test/calendar', [TestController::class, 'testCalendar'], 'test.calendar'],
    ['GET', '/test/message', [TestController::class, 'testMessage'], 'test.message'],
    ['POST', '/test/mail/send', [TestController::class, 'sendMail'], 'test.mail'],
];