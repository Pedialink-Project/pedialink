<?php

use App\Controllers\Admin\AppointmentController;
use App\Controllers\Admin\ChildController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\EventController;
use App\Controllers\Admin\MaternalController;
use App\Controllers\Admin\UserController;
use App\Controllers\Admin\VaccineController;

return [
    ['GET', '/admin/dashboard', [DashboardController::class, 'index'], 'admin.dashboard', ['admin']],
    ['GET', '/admin/user/overview', [UserController::class, 'overview'], 'admin.user.overview', ['admin']],
    ['GET', '/admin/user/parent', [UserController::class, 'parentAccountApproval'], 'admin.user.parent', ['admin']],
    ['GET', '/admin/user/admin', [UserController::class, 'admin'], 'admin.user.admin', ['admin']],
    ['GET', '/admin/child-profiles/overview', [ChildController::class, 'overview'], 'admin.child.overview', ['admin']],
    ['GET', '/admin/child/{id}/access-control', [ChildController::class, 'accessControl'], 'admin.child.access.control', ['admin']],
    ['GET', '/admin/child-profiles/linkage-requests', [ChildController::class, 'linkageRequests'], 'admin.child.linkage.requests', ['admin']],
    ['GET', '/admin/child-profiles/access-requests', [ChildController::class, 'accessRequests'], 'admin.child.access.requests', ['admin']],
    ['GET', '/admin/maternal-profiles/overview', [MaternalController::class, 'overview'], 'admin.maternal.overview', ['admin']],
    ['GET', '/admin/maternal-profiles/access-requests', [MaternalController::class, 'accessRequests'], 'admin.maternal.access.requests', ['admin']],
    ['GET', '/admin/vaccination/vaccines', [VaccineController::class, 'vaccines'], 'admin.vaccination.vaccines', ['admin']],
    ['GET', '/admin/vaccination/schedule', [VaccineController::class, 'schedule'], 'admin.vaccination.schedule', ['admin']],
    ['GET', '/admin/vaccination/schedule/{schedule_id}/manage', [VaccineController::class, 'manageSchedule'], 'admin.vaccination.schedule.manage', ['admin']],
    ['GET', '/admin/appointment', [AppointmentController::class, 'index'], 'admin.appointment', ['admin']],
    ['GET', '/admin/events-and-campaigns', [EventController::class, 'index'], 'admin.event', ['admin']],
];