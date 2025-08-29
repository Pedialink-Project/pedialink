<?php

use App\Controllers\AdminController;

return [
    ['GET', '/admin/dashboard', [AdminController::class, 'dashboard'], 'admin.dashboard', ['admin']],
];