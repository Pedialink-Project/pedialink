<?php

use App\Controllers\AuthController;

return [
    ['GET', '/parent/register', [AuthController::class, 'parentRegister'], 'parent.register', ['guest']],
    ['GET', '/parent/login', [AuthController::class, 'parentLogin'], 'parent.login', ['guest']],
    ['GET', '/staff/login', [AuthController::class, 'staffLogin'], 'staff.login', ['guest']],
    ['GET', '/forgot-password', [AuthController::class, 'forgotPassword'], 'forgot.password', ['guest']],
];