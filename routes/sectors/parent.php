<?php

use App\Controllers\Parent\AppointmentController;
use App\Controllers\Parent\DashboardController;
use App\Controllers\Parent\MyChildrenController;
use App\Controllers\ParentController;

return [
    ['GET', '/parent/dashboard', [DashboardController::class, 'index'], 'parent.dashboard', ['parent']],
    ['GET', '/parent/my-children', [MyChildrenController::class, 'index'], 'parent.my.children', ['parent']],
    ['GET', '/parent/vaccination', [ParentController::class, 'vaccination'], 'parent.vaccination', ['parent']],
    ['GET', '/parent/nutrition-tracking', [ParentController::class, 'nutritionTracking'], 'parent.nutrition.tracking', ['parent']],
    ['GET', '/parent/appointments', [AppointmentController::class, 'index'], 'parent.appointments', ['parent']],
    ['GET', '/parent/events-campaigns', [ParentController::class, 'eventsCampaigns'], 'parent.events.campaigns', ['parent']],
    ['GET', '/parent/notifications', [ParentController::class, 'notifications'], 'parent.notifications', ['parent']],
    ['GET', '/parent/settings', [ParentController::class, 'settings'], 'parent.settings', ['parent']],
];