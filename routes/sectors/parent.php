<?php

use App\Controllers\ParentController;

return [
    ['GET', '/parent/dashboard', [ParentController::class, 'dashboard'], 'parent.dashboard', ['parent']],
];