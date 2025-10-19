<?php

use App\Controllers\TestController;

/* Contains routes for testing purposes */

return [
    ['GET', '/test', [TestController::class, 'testPortal'], 'test.portal'],
    ['GET', '/test/message', [TestController::class, 'testMessage'], 'test.message'],
];