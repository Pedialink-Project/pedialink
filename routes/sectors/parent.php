<?php

use App\Controllers\NotificationController;
use App\Controllers\Parent\AppointmentController;
use App\Controllers\Parent\DashboardController;
use App\Controllers\Parent\EventController;
use App\Controllers\Parent\MyChildrenController;
use App\Controllers\Parent\NutritionController;
use App\Controllers\Parent\VaccinationController;
use App\Controllers\ParentController;
use App\Controllers\SettingController;

return [
    ['GET', '/parent/dashboard', [DashboardController::class, 'index'], 'parent.dashboard', ['parent']],
    ['GET', '/parent/my-children', [MyChildrenController::class, 'index'], 'parent.my.children', ['parent']],
    ['GET', '/parent/my-children/{id}', [MyChildrenController::class, 'viewChildDetails'], 'parent.child.details', ['parent']],
    ['GET', '/parent/vaccination', [VaccinationController::class, 'index'], 'parent.vaccination', ['parent']],
    ['GET', '/parent/nutrition-tracking', [NutritionController::class, 'index'], 'parent.nutrition.tracking', ['parent']],
    ['GET', '/parent/appointments', [AppointmentController::class, 'index'], 'parent.appointments', ['parent']],
    ['POST', '/parent/appointment/request', [AppointmentController::class, 'requestAppointment'], 'parent.appointment.request', ['parent']],
    ['POST', '/parent/appointment/{id}/reschedule', [AppointmentController::class, 'requestRescheduleAppointment'], 'parent.appointment.reschedule', ['parent']],
    ['POST', '/parent/appointment/{id}/cancel', [AppointmentController::class, 'requestCancelAppointment'], 'parent.appointment.cancel', ['parent']],
    ['GET', '/parent/events-campaigns', [EventController::class, 'index'], 'parent.events.campaigns', ['parent']],
    ['POST', '/parent/events-campaigns/{id}/book', [EventController::class, 'bookEvent'], 'parent.events.campaigns.book', ['parent']],
    ['POST', '/parent/events-campaigns/{id}/cancel', [EventController::class, 'cancelEventBooking'], 'parent.events.campaigns.cancel', ['parent']],
    ['GET', '/parent/notification', [NotificationController::class, 'index'], 'parent.notification', ['parent']],
    ['GET', '/parent/settings', [SettingController::class, 'index'], 'parent.settings', ['parent']],
];