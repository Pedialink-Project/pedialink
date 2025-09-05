<?php

use App\Controllers\DoctorController;

return [
    ['GET', '/doctor/dashboard', [DoctorController::class, 'dashboard'], 'doctor.dashboard', ['doctor']],
    ['GET', '/doctor/child-profiles', [DoctorController::class, 'childProfiles'], 'doctor.child.profiles', ['doctor']],
    ['GET', '/doctor/maternal-profiles', [DoctorController::class, 'maternalProfiles'], 'doctor.maternal.profiles', ['doctor']],
    ['GET', '/doctor/appointments', [DoctorController::class, 'appointments'], 'doctor.appointments', ['doctor']],
    ['GET', '/doctor/notifications', [DoctorController::class, 'notifications'], 'doctor.notifications', ['doctor']],
    ['GET', '/doctor/settings', [DoctorController::class, 'settings'], 'doctor.settings', ['doctor']],
];