<?php

use App\Controllers\AuthController;
use App\Controllers\SettingController;
use App\Controllers\VerifyController;

return [
    ['GET', '/parent/register', [AuthController::class, 'parentRegisterInitial'], 'parent.register', ['guest']],
    ['POST', '/parent/register', [AuthController::class, 'parentVerifyInitial'], 'parent.register.submit', ['guest']],
    ['GET', '/parent/register/final', [AuthController::class, 'parentRegisterFinal'], 'parent.register.final', ['guest']],
    ['POST', '/parent/register/final', [AuthController::class, 'parentVerifyFinal'], 'parent.register.final.submit', ['guest']],
    ['GET', '/parent/login', [AuthController::class, 'parentLogin'], 'parent.login', ['guest']],
    ['POST', '/parent/login', [AuthController::class, 'loginAsParent'], 'parent.login.submit', ['guest']],
    ['GET', '/staff/login', [AuthController::class, 'staffLogin'], 'staff.login', ['guest']],
    ['POST', '/staff/login', [AuthController::class, 'loginAsStaff'], 'staff.login.submit', ['guest']],
    ['GET', '/forgot-password', [AuthController::class, 'forgotPassword'], 'forgot.password', ['guest']],
    ['POST', '/send-password-reset', [AuthController::class, 'sendResetPassword'], 'reset.password.send', ['guest']],
    ['GET', '/reset-password', [AuthController::class, 'resetPasswordView'], 'reset.password', ['guest']],
    ['POST', '/reset-password', [AuthController::class, 'resetPassword'], 'reset.password.submit', ['guest']],
    ['POST', '/logout', [AuthController::class, 'logout'], 'logout', ['auth']],
    ['POST', '/profile/update-profile', [SettingController::class, 'updateName'], 'update.profile', ['auth']],
    ['POST', '/profile/update-password', [SettingController::class, 'updatePassword'], 'update.password', ['auth']],
    ['GET', '/verify/email', [VerifyController::class, 'emailUnverified'], 'email.unverified', ['auth']],
    ['POST', '/verify/email/send', [VerifyController::class, 'verifyEmailSend'], 'email.verify.send', ['auth']],
    ['GET', '/verify-email', [VerifyController::class, 'verifyEmail'], 'email.verify', ['auth']],

    // Parent verification
    ['GET', '/parent/verify/documents', [VerifyController::class, 'parentUnverified'], 'parent.unverified', ['parent']],
    ['POST', '/parent/documents/submit', [VerifyController::class, 'submitParentDocuments'], 'parent.document.submit', ['parent']],

    // Staff registration
    ['GET', '/staff/register', [AuthController::class, 'registerStaffView'], 'staff.register', ['guest']],
    ['POST', '/staff/register', [AuthController::class, 'registerStaff'], 'staff.register.submit', ['guest']],
];