<?php

use App\Controllers\PublicHealthMidwifeController;

return [
    ['GET', '/phm/dashboard', [PublicHealthMidwifeController::class, 'dashboard'], 'phm.dashboard', ['phm']],
    ['GET', '/phm/child-profiles', [PublicHealthMidwifeController::class, 'childProfiles'], 'phm.child.profiles', ['phm']],
    ['GET', '/phm/maternal-profiles', [PublicHealthMidwifeController::class, 'maternalProfiles'], 'phm.maternal.profiles', ['phm']],
    ['GET', '/phm/growth-monitoring', [PublicHealthMidwifeController::class, 'growthMonitoring'], 'phm.growth.monitoring', ['phm']],
    ['GET', '/phm/vaccination', [PublicHealthMidwifeController::class, 'vaccination'], 'phm.vaccination', ['phm']],
    ['GET', '/phm/nutrition-tracking', [PublicHealthMidwifeController::class, 'nutritionTracking'], 'phm.nutrition.tracking', ['phm']],
    ['GET', '/phm/appointments', [PublicHealthMidwifeController::class, 'appointments'], 'phm.appointments', ['phm']],
    ['GET', '/phm/notifications', [PublicHealthMidwifeController::class, 'notifications'], 'phm.notifications', ['phm']],
    ['GET', '/phm/settings', [PublicHealthMidwifeController::class, 'settings'], 'phm.settings', ['phm']],
];