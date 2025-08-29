<?php

use App\Controllers\DoctorController;

return [
    ['GET', '/doctor/dashboard', [DoctorController::class, 'dashboard'], 'doctor.dashboard', ['doctor']],
];