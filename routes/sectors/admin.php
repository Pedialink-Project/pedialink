<?php

use App\Controllers\Admin\AppointmentController;
use App\Controllers\Admin\ChildController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\EventController;
use App\Controllers\Admin\MaternalController;
use App\Controllers\Admin\UserController;
use App\Controllers\Admin\VaccineController;
use App\Controllers\NotificationController;
use App\Controllers\SettingController;

return [
    // Admin dashboard
    ['GET', '/admin/dashboard', [DashboardController::class, 'index'], 'admin.dashboard', ['admin', 'verified']],

    // Admin user overview
    ['GET', '/admin/user/overview', [UserController::class, 'overview'], 'admin.user.overview', ['admin', 'verified']],
    ['POST', '/admin/user/register-staff', [UserController::class, 'registerStaff'], 'admin.user.register.staff', ['admin', 'verified']],

    // Admin parent
    ['GET', '/admin/user/parent', [UserController::class, 'parentAccountApproval'], 'admin.user.parent', ['admin', 'verified']],
    ['GET', '/admin/user/parent/{id}/{type}', [UserController::class, 'parentDocumentDownload'], 'admin.user.parent.download', ['admin', 'verified']],
    ['POST', '/admin/user/parent/{id}/approve', [UserController::class, 'parentApprove'], 'admin.user.parent.approve', ['admin', 'verified']],
    ['POST', '/admin/user/parent/{id}/deny', [UserController::class, 'parentDeny'], 'admin.user.parent.deny', ['admin', 'verified']],

    // Admin - admin
    ['GET', '/admin/user/admin', [UserController::class, 'admin'], 'admin.user.admin', ['admin', 'verified']],
    ['POST', '/admin/user/admin/create', [UserController::class, 'createAdmin'], 'admin.user.admin.create', ['admin', 'verified']],
    ['POST', '/admin/user/admin/{id}/edit', [UserController::class, 'editAdmin'], 'admin.user.admin.edit', ['admin', 'verified']],
    ['POST', '/admin/user/admin/{id}/delete', [UserController::class, 'deleteAdmin'], 'admin.user.admin.delete', ['admin', 'verified']],

    // Admin child profiles
    ['GET', '/admin/child-profiles/overview', [ChildController::class, 'overview'], 'admin.child.overview', ['admin', 'verified']],
    ['GET', '/admin/child/{id}/access-control', [ChildController::class, 'accessControl'], 'admin.child.access.control', ['admin', 'verified']],
    ['POST', '/admin/child/{id}/access-control/revoke', [ChildController::class, 'removeLinkage'], 'admin.child.access.control.revoke', ['admin', 'verified']],
    ['GET', '/admin/child-profiles/linkage-requests', [ChildController::class, 'linkageRequests'], 'admin.child.linkage.requests', ['admin', 'verified']],
    ['GET', '/admin/child-profiles/access-requests', [ChildController::class, 'accessRequests'], 'admin.child.access.requests', ['admin', 'verified']],
    ['POST', '/admin/child-profiles/access-requests/{id}/approve', [ChildController::class, 'approveAccessRequest'], 'admin.child.access.requests.approve', ['admin', 'verified']],
    ['POST', '/admin/child-profiles/access-requests/{id}/deny', [ChildController::class, 'denyAccessRequest'], 'admin.child.access.requests.deny', ['admin', 'verified']],

    // Admin maternal profile
    ['GET', '/admin/maternal-profiles/overview', [MaternalController::class, 'overview'], 'admin.maternal.overview', ['admin', 'verified']],
    ['GET', '/admin/maternal-profiles/access-requests', [MaternalController::class, 'accessRequests'], 'admin.maternal.access.requests', ['admin', 'verified']],
    ['POST', '/admin/maternal-profiles/access-requests/{id}/approve', [MaternalController::class, 'approveAccessRequest'], 'admin.maternal.access.requests.approve', ['admin', 'verified']],
    ['POST', '/admin/maternal-profiles/access-requests/{id}/deny', [MaternalController::class, 'denyAccessRequest'], 'admin.maternal.access.requests.deny', ['admin', 'verified']],

    // Admin vaccinations
    ['GET', '/admin/vaccination/vaccines', [VaccineController::class, 'vaccines'], 'admin.vaccination.vaccines', ['admin', 'verified']],
    ['POST', '/admin/vaccination/vaccines/create', [VaccineController::class, 'addVaccine'], 'admin.vaccination.vaccines.create', ['admin', 'verified']],
    ['POST', '/admin/vaccination/vaccines/{id}/edit', [VaccineController::class, 'editVaccine'], 'admin.vaccination.vaccines.edit', ['admin', 'verified']],
    ['POST', '/admin/vaccination/vaccines/{id}/delete', [VaccineController::class, 'deleteVaccine'], 'admin.vaccination.vaccines.delete', ['admin', 'verified']],
    ['GET', '/admin/vaccination/schedule', [VaccineController::class, 'schedule'], 'admin.vaccination.schedule', ['admin', 'verified']],
    ['POST', '/admin/vaccination/schedule/create', [VaccineController::class, 'addSchedule'], 'admin.vaccination.schedule.create', ['admin', 'verified']],
    ['POST', '/admin/vaccination/schedule/{id}/edit', [VaccineController::class, 'editSchedule'], 'admin.vaccination.schedule.edit', ['admin', 'verified']],
    ['POST', '/admin/vaccination/schedule/{id}/delete', [VaccineController::class, 'deleteSchedule'], 'admin.vaccination.schedule.delete', ['admin', 'verified']],
    ['POST', '/admin/vaccination/schedule/{id}/enable', [VaccineController::class, 'enableSchedule'], 'admin.vaccination.schedule.enable', ['admin', 'verified']],
    ['POST', '/admin/vaccination/schedule/{id}/disable', [VaccineController::class, 'disableSchedule'], 'admin.vaccination.schedule.disable', ['admin', 'verified']],
    ['GET', '/admin/vaccination/schedule/{schedule_id}/manage', [VaccineController::class, 'manageSchedule'], 'admin.vaccination.schedule.manage', ['admin', 'verified']],
    ['POST', '/admin/vaccination/schedule/{schedule_id}/manage/add', [VaccineController::class, 'addVaccineToSchedule'], 'admin.vaccination.schedule.manage.add', ['admin', 'verified']],

    // Admin appointment
    ['GET', '/admin/appointment', [AppointmentController::class, 'index'], 'admin.appointment', ['admin', 'verified']],

    // Admin events and campaigns
    ['GET', '/admin/events-and-campaigns', [EventController::class, 'index'], 'admin.event', ['admin', 'verified']],
    ['POST', '/admin/events-and-campaigns/create', [EventController::class, 'createEvent'], 'admin.event.create', ['admin', 'verified']],
    ['POST', '/admin/events-and-campaigns/{id}/edit', [EventController::class, 'editEvent'], 'admin.event.edit', ['admin', 'verified']],
    ['POST', '/admin/events-and-campaigns/{id}/delete', [EventController::class, 'deleteEvent'], 'admin.event.delete', ['admin', 'verified']],
    ['POST', '/admin/events-and-campaigns/{id}/edit-event-visible', [EventController::class, 'editEventVisible'], 'admin.event.edit.visible', ['admin', 'verified']],

    // Admin settings
    ['GET', '/admin/settings', [SettingController::class, 'index'], 'admin.settings', ['admin']],

    // Admin notification
    ['GET', '/admin/notification', [NotificationController::class, 'index'], 'admin.notification', ['admin', 'verified']]
];