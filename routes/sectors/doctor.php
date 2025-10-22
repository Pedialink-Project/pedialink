<?php

use App\Controllers\Doctor\ChildHealthController;
use App\Controllers\Doctor\DashboardController;
use App\Controllers\Doctor\ChildProfileController;
use App\Controllers\Doctor\MaternalProfileController;
use App\Controllers\Doctor\MaternalHealthController;
use App\Controllers\Doctor\AppointmentController;
use App\Controllers\NotificationController;
use App\Controllers\SettingController;

return [
    ['GET', '/doctor/dashboard', [DashboardController::class, 'index'], 'doctor.dashboard', ['doctor']],
    ['GET', '/doctor/child-profiles', [ChildProfileController::class, 'index'], 'doctor.child.profiles', ['doctor']],
    ['GET', '/doctor/child-profiles/{id}/health-records', [ChildHealthController::class, 'index'], 'doctor.child.health', ['doctor']],
    ['GET', '/doctor/child-profiles/{id}/vaccination-records', [ChildHealthController::class, 'vaccinationIndex'], 'doctor.child.vaccination', ['doctor']],
    ['GET', '/doctor/maternal-profiles', [MaternalProfileController::class, 'index'], 'doctor.maternal.profiles', ['doctor']],
    ['GET', '/doctor/maternal-profiles/{id}/health-records', [MaternalHealthController::class, 'index'], 'doctor.maternal.health', ['doctor']],
    ['POST', '/doctor/maternal-profiles/{id}/health-records/add', [MaternalHealthController::class, 'createMaternalRecord'], 'doctor.maternal.health.add', ['doctor']],
    ['POST', '/doctor/maternal-profiles/{id}/health-records/{recordId}/edit', [MaternalHealthController::class, 'editMaternalRecord'], 'doctor.maternal.health.edit', ['doctor']],
    ['POST', '/doctor/maternal-profiles/{id}/health-records/{recordId}/delete', [MaternalHealthController::class, 'deleteMaternalRecord'], 'doctor.maternal.health.delete', ['doctor']],
    ['GET', '/doctor/appointments', [AppointmentController::class, 'index'], 'doctor.appointments', ['doctor']],
    ['GET', '/doctor/notification', [NotificationController::class, 'index'], 'doctor.notification', ['doctor']],
    ['GET', '/doctor/settings', [SettingController::class, 'index'], 'doctor.settings', ['doctor']],
];
