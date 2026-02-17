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
    ['GET', '/phm/dashboard', [DashboardController::class, 'index'], 'phm.dashboard', ['phm','verified']],

    // Child Profile Routes
    ['GET', '/phm/child-profiles', [ChildProfileController::class, 'index'], 'phm.child.profiles', ['phm','verified']],
    ['POST', '/phm/child-profile/create', [ChildProfileController::class, 'createChild'], 'phm.child.create', ['phm','verified']],
    ['POST', '/phm/child-profile/{id}/edit', [ChildProfileController::class, 'editChild'], 'phm.child.edit', ['phm','verified']],
    ['POST', '/phm/child-profile/{id}/delete', [ChildProfileController::class, 'deleteChild'], 'phm.child.delete', ['phm','verified']],

    // Child Health Record Routes
    ['GET', '/phm/child-profiles/{id}/health-records', [ChildHealthController::class, 'index'], 'phm.child.health', ['phm','verified']],
    ['POST', '/phm/child-profiles/{id}/health-records/add', [ChildHealthController::class, 'addHealthRecord'], 'phm.child.health.add', ['phm','verified']],
    ['POST', '/phm/child-profiles/{id}/health-records/{recordId}/edit', [ChildHealthController::class, 'editHealthRecord'], 'phm.child.health.edit', ['phm','verified']],
    ['POST','/phm/child-profiles/{id}/health-records/{recordId}/mark-as-invalid', [ChildHealthController::class,'markAsInvalid'], 'phm.child.health.markinvalid', ['phm','verified']],

    // Maternal Profile Routes
    ['GET', '/phm/maternal-profiles', [MaternalProfileController::class, 'index'], 'phm.maternal.profiles', ['phm','verified']],
    ['POST', '/phm/maternal-profile/create', [MaternalProfileController::class, 'createMaternal'], 'phm.maternal.create', ['phm','verified']],
    ['POST','/phm/maternal-profiles/{id}/end', [MaternalProfileController::class,'endAntenatal'], 'phm.maternal.end', ['phm','verified']],
    
    //Maternal Health Record Routes
    ['GET', '/phm/maternal-profiles/{id}/health-records', [MaternalHealthController::class, 'index'], 'phm.maternal.health', ['phm','verified']],
    ['GET', '/phm/child-vaccinations/{id}/records', [ChildHealthController::class, 'vaccinationIndex'], 'phm.child.vaccinations', ['phm','verified']],
    ['GET', '/phm/growth-monitoring', [GrowthMonitorController::class, 'index'], 'phm.growth.monitoring', ['phm','verified']],
    ['GET', '/phm/growth-monitoring/{id}', [GrowthMonitorController::class, 'childGrowthIndex'], 'phm.growth.monitoring.child', ['phm','verified']],
    ['GET', '/phm/vaccination', [VaccinationController::class, 'index'], 'phm.vaccination', ['phm','verified']],
    ['GET', '/phm/nutrition-tracking', [TestController::class, 'nutritionTracking'], 'phm.nutrition.tracking', ['phm','verified']],
    ['GET', '/phm/appointments', [AppointmentsController::class, 'index'], 'phm.appointments', ['phm','verified']],
    ['GET', '/phm/appointment-requests', [AppointmentRequestController::class, 'index'], 'phm.appointments.requests', ['phm','verified']],

    // Notification and Settings Routes
    ['GET', '/phm/notification', [NotificationController::class, 'index'], 'phm.notification', ['phm','verified']],
    ['GET', '/phm/settings', [SettingController::class, 'index'], 'phm.settings', ['phm','verified']],
];
