<?php

use App\Controllers\NotificationController;
use App\Controllers\Parent\AppointmentController;
use App\Controllers\Parent\DashboardController;
use App\Controllers\Parent\EventController;
use App\Controllers\Parent\MyChildrenController;
use App\Controllers\Parent\GrowthController;
use App\Controllers\Parent\VaccinationController;
use App\Controllers\SettingController;

return [
    //Dashboard routes
    ['GET', '/parent/dashboard', [DashboardController::class, 'index'], 'parent.dashboard', ['parent', 'verified']],

    //Child routes
    ['GET', '/parent/my-children', [MyChildrenController::class, 'index'], 'parent.my.children', ['parent']],
    ['GET', '/parent/my-children/{id}', [MyChildrenController::class, 'viewChildDetails'], 'parent.child.details', ['parent']],

    //Vaccination routes
    ['GET', '/parent/vaccination', [VaccinationController::class, 'index'], 'parent.vaccination', ['parent']],

    //Growth tracking routes
    ['GET', '/parent/growth-tracking', [GrowthController::class, 'index'], 'parent.growth.tracking', ['parent']],

    //Appointment routes
    ['GET', '/parent/appointments/my-appointments', [AppointmentController::class, 'myAppointments'], 'parent.appointments.my', ['parent']],
    ['GET', '/parent/appointments/child', [AppointmentController::class, 'childAppointments'], 'parent.appointments.child', ['parent']],
    ['POST', '/parent/appointment/{id}/cancel', [AppointmentController::class, 'cancelMyAppointment'], 'parent.appointment.cancel', ['parent']],
    ['POST', '/parent/appointment/{id}/child/cancel', [AppointmentController::class, 'cancelChildAppointment'], 'parent.appointment.child.cancel', ['parent']],
    
    //Event routes
    ['GET', '/parent/events-campaigns', [EventController::class, 'index'], 'parent.events.campaigns', ['parent']],
    ['POST', '/parent/events-campaigns/{id}/book', [EventController::class, 'bookEvent'], 'parent.events.campaigns.book', ['parent']],
    ['POST', '/parent/events-campaigns/{id}/cancel', [EventController::class, 'cancelEventBooking'], 'parent.events.campaigns.cancel', ['parent']],

    //Other routes
    ['GET', '/parent/notification', [NotificationController::class, 'index'], 'parent.notification', ['parent','verified']],
    ['GET', '/parent/settings', [SettingController::class, 'index'], 'parent.settings', ['parent','verified']],
];