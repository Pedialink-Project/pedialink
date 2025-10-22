<?php


use App\Controllers\NotificationController;
use App\Controllers\PublicHealthMidwife\ChildProfileController;
use App\Controllers\PublicHealthMidwife\DashboardController;
use App\Controllers\PublicHealthMidwife\MaternalProfileController;
use App\Controllers\SettingController;
use App\Controllers\TestController;
use App\Controllers\PublicHealthMidwife\GrowthMonitorController;
use App\Controllers\PublicHealthMidwife\VaccinationController;
use App\Controllers\PublicHealthMidwife\AppointmentsController;
use App\Controllers\PublicHealthMidwife\ChildHealthController;
use App\Controllers\PublicHealthMidwife\AppointmentRequestController;
use App\Controllers\PublicHealthMidwife\ChildVaccinationController;
use App\Controllers\PublicHealthMidwife\MaternalHealthController;


return [
    ['GET', '/phm/dashboard', [DashboardController::class, 'index'], 'phm.dashboard', ['phm']],
    ['GET', '/phm/child-profiles', [ChildProfileController::class, 'index'], 'phm.child.profiles', ['phm']],
    ['GET', '/phm/child-profiles/{id}/health-records', [ChildHealthController::class, 'index'], 'phm.child.health.records', ['phm']],
    ['POST', '/phm/child-profile/create', [ChildProfileController::class, 'createChild'], 'phm.child.create', ['phm']],
    ['POST', '/phm/child-profile/{id}/edit', [ChildProfileController::class, 'editChild'], 'phm.child.edit', ['phm']],
    ['POST', '/phm/child-profile/{id}/delete', [ChildProfileController::class, 'deleteChild'], 'phm.child.delete', ['phm']],
    ['GET', '/phm/maternal-profiles', [MaternalProfileController::class, 'index'], 'phm.maternal.profiles', ['phm']],
    ['GET', '/phm/maternal-profiles/{id}/health-records', [MaternalHealthController::class, 'index'], 'phm.maternal.health', ['phm']],
    ['GET', '/phm/child-vaccinations/{id}/records', [ChildHealthController::class, 'vaccinationIndex'], 'phm.child.vaccinations', ['phm']],
    ['GET', '/phm/growth-monitoring', [GrowthMonitorController::class, 'index'], 'phm.growth.monitoring', ['phm']],
    ['GET', '/phm/growth-monitoring/{id}', [GrowthMonitorController::class, 'childGrowthIndex'], 'phm.growth.monitoring.child', ['phm']],
    ['GET', '/phm/vaccination', [VaccinationController::class, 'index'], 'phm.vaccination', ['phm']],
    ['GET', '/phm/nutrition-tracking', [TestController::class, 'nutritionTracking'], 'phm.nutrition.tracking', ['phm']],
    ['GET', '/phm/appointments', [AppointmentsController::class, 'index'], 'phm.appointments', ['phm']],
    ['GET', '/phm/appointment-requests', [AppointmentRequestController::class, 'index'], 'phm.appointments.requests', ['phm']],
    ['GET', '/phm/notification', [NotificationController::class, 'index'], 'phm.notification', ['phm']],
    ['GET', '/phm/settings', [SettingController::class, 'index'], 'phm.settings', ['phm']],
];