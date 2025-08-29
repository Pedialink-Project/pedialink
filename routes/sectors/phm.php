<?php

use App\Controllers\PublicHealthMidwifeController;

return [
    ['GET', '/phm/dashboard', [PublicHealthMidwifeController::class, 'dashboard'], 'phm.dashboard', ['phm']],
];