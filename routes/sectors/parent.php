<?php

use App\Controllers\Parent\AppointmentController;
use App\Controllers\Parent\DashboardController;
use App\Controllers\Parent\EventController;
use App\Controllers\Parent\MyChildrenController;
use App\Controllers\Parent\NutritionController;
use App\Controllers\Parent\VaccinationController;
use App\Controllers\ParentController;

return [
    ['GET', '/parent/dashboard', [DashboardController::class, 'index'], 'parent.dashboard', ['parent']],
    ['GET', '/parent/my-children', [MyChildrenController::class, 'index'], 'parent.my.children', ['parent']],
    ['GET', '/parent/my-children/{id}', [MyChildrenController::class, 'viewChildDetails'], 'parent.child.details', ['parent']],
    ['GET', '/parent/vaccination', [VaccinationController::class, 'index'], 'parent.vaccination', ['parent']],
    ['GET', '/parent/nutrition-tracking', [NutritionController::class, 'index'], 'parent.nutrition.tracking', ['parent']],
    ['GET', '/parent/appointments', [AppointmentController::class, 'index'], 'parent.appointments', ['parent']],
    ['POST', '/parent/request-appointment', [AppointmentController::class, 'requestAppointment'], 'parent.request.appointment', ['parent']],
    ['GET', '/parent/events-campaigns', [EventController::class, 'index'], 'parent.events.campaigns', ['parent']],
    ['GET', '/parent/notifications', [ParentController::class, 'notifications'], 'parent.notifications', ['parent']],
    ['GET', '/parent/settings', [ParentController::class, 'settings'], 'parent.settings', ['parent']],
];