<?php


use App\Controllers\PublicHealthMidwife\ChildProfileController;
use App\Controllers\PublicHealthMidwife\DashboardController;
use App\Controllers\TestController;

return [
    ['GET', '/phm/dashboard', [DashboardController::class, 'index'], 'phm.dashboard', ['phm']],
    ['GET', '/phm/child-profiles', [ChildProfileController::class, 'index'], 'phm.child.profiles', ['phm']],
    ['GET', '/phm/maternal-profiles', [TestController::class, 'maternalProfiles'], 'phm.maternal.profiles', ['phm']],
    ['GET', '/phm/growth-monitoring', [TestController::class, 'growthMonitoring'], 'phm.growth.monitoring', ['phm']],
    ['GET', '/phm/vaccination', [TestController::class, 'vaccination'], 'phm.vaccination', ['phm']],
    ['GET', '/phm/nutrition-tracking', [TestController::class, 'nutritionTracking'], 'phm.nutrition.tracking', ['phm']],
    ['GET', '/phm/appointments', [TestController::class, 'appointments'], 'phm.appointments', ['phm']],
    ['GET', '/phm/notifications', [TestController::class, 'notifications'], 'phm.notifications', ['phm']],
    ['GET', '/phm/settings', [TestController::class, 'settings'], 'phm.settings', ['phm']],
];