<?php

use App\Controllers\Doctor\DashboardController;
use App\Controllers\Doctor\ChildProfileController;
use App\Controllers\Doctor\MaternalProfileController;
use App\Controllers\Doctor\MaternalHealthController;
use App\Controllers\Doctor\AppointmentController;
use App\Controllers\Doctor\NotificationController;
use App\Controllers\Doctor\SettingsController;
use App\Controllers\DoctorController;

return [
    ['GET', '/doctor/dashboard', [DashboardController::class, 'index'], 'doctor.dashboard', ['doctor']],
    ['GET', '/doctor/child-profiles', [ChildProfileController::class, 'index'], 'doctor.child.profiles', ['doctor']],
    ['GET', '/doctor/maternal-profiles', [MaternalProfileController::class, 'index'], 'doctor.maternal.profiles', ['doctor']],
    ['GET', '/doctor/maternal-profiles/{id}/health-records', [MaternalHealthController::class, 'index'], 'doctor.maternal.health', ['doctor']],
    ['GET', '/doctor/appointments', [AppointmentController::class, 'index'], 'doctor.appointments', ['doctor']],
    ['GET', '/doctor/notifications', [NotificationController::class, 'index'], 'doctor.notifications', ['doctor']],
    ['GET', '/doctor/settings', [SettingsController::class, 'index'], 'doctor.settings', ['doctor']],
];
