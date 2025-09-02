<?php

use App\Controllers\ParentController;

return [
    ['GET', '/parent/dashboard', [ParentController::class, 'dashboard'], 'parent.dashboard', ['parent']],
    ['GET', '/parent/my-children', [ParentController::class, 'myChildren'], 'parent.my.children', ['parent']],
    ['GET', '/parent/vaccination', [ParentController::class, 'vaccination'], 'parent.vaccination', ['parent']],
    ['GET', '/parent/nutrition-tracking', [ParentController::class, 'nutritionTracking'], 'parent.nutrition.tracking', ['parent']],
    ['GET', '/parent/appointments', [ParentController::class, 'appointments'], 'parent.appointments', ['parent']],
    ['GET', '/parent/events-campaigns', [ParentController::class, 'eventsCampaigns'], 'parent.events.campaigns', ['parent']],
    ['GET', '/parent/notifications', [ParentController::class, 'notifications'], 'parent.notifications', ['parent']],
    ['GET', '/parent/settings', [ParentController::class, 'settings'], 'parent.settings', ['parent']],
];