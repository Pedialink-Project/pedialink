<?php


use App\Controllers\PublicHealthMidwife\ChildProfileController;
use App\Controllers\PublicHealthMidwife\DashboardController;
use App\Controllers\PublicHealthMidwife\MaternalProfileController;
use App\Controllers\TestController;
use App\Controllers\PublicHealthMidwife\GrowthMonitorController;
use App\Controllers\PublicHealthMidwife\VaccinationController;
use App\Controllers\PublicHealthMidwife\AppointmentsController;
use App\Controllers\PublicHealthMidwife\ChildHealthController;
use App\Controllers\PublicHealthMidwife\ChildVaccinationController;
use App\Controllers\PublicHealthMidwife\MaternalHealthController;


return [
    ['GET', '/phm/dashboard', [DashboardController::class, 'index'], 'phm.dashboard', ['phm']],
    ['GET', '/phm/child-profiles', [ChildProfileController::class, 'index'], 'phm.child.profiles', ['phm']],
    ['GET', '/phm/child-profiles/{id}/health-records', [ChildHealthController::class, 'index'], 'phm.child.health.records', ['phm']],
    ['GET', '/phm/maternal-profiles', [MaternalProfileController::class, 'index'], 'phm.maternal.profiles', ['phm']],
    ['GET', '/phm/maternal-profiles/{id}/health-records', [MaternalHealthController::class, 'index'], 'phm.maternal.health', ['phm']],
    ['GET', '/phm/growth-monitoring', [GrowthMonitorController::class, 'index'], 'phm.growth.monitoring', ['phm']],
    ['GET', '/phm/vaccination', [VaccinationController::class, 'index'], 'phm.vaccination', ['phm']],
    ['GET', '/phm/nutrition-tracking', [TestController::class, 'nutritionTracking'], 'phm.nutrition.tracking', ['phm']],
    ['GET', '/phm/appointments', [AppointmentsController::class, 'index'], 'phm.appointments', ['phm']],
    ['GET', '/phm/notifications', [TestController::class, 'notifications'], 'phm.notifications', ['phm']],
    ['GET', '/phm/settings', [TestController::class, 'settings'], 'phm.settings', ['phm']],
];