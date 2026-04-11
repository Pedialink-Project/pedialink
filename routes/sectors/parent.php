<?php

use App\Controllers\NotificationController;
use App\Controllers\Parent\AppointmentController;
use App\Controllers\Parent\CalendarController;
use App\Controllers\Parent\DashboardController;
use App\Controllers\Parent\EventController;
use App\Controllers\Parent\MyChildrenController;
use App\Controllers\Parent\GrowthController;
use App\Controllers\Parent\VaccinationController;
use App\Controllers\Parent\PregnancyController;
use App\Controllers\SettingController;

return [
    //Dashboard routes
    ['GET', '/parent/dashboard', [DashboardController::class, 'index'], 'parent.dashboard', ['parent', 'verified']],

    //Child routes
    ['GET', '/parent/my-children', [MyChildrenController::class, 'index'], 'parent.my.children', ['parent','verified']],
    ['GET', '/parent/my-children/{id}', [MyChildrenController::class, 'viewChildDetails'], 'parent.child.details', ['parent','verified' ]],

    // Maternal / pregnancy routes
    ['GET', '/parent/my-pregnancy', [PregnancyController::class, 'myPregnancy'], 'parent.my.pregnancy', ['parent','verified']],

    //Vaccination routes
    ['GET', '/parent/vaccination', [VaccinationController::class, 'index'], 'parent.vaccination', ['parent','verified']],

    //Growth tracking routes
    ['GET', '/parent/growth-tracking', [GrowthController::class, 'index'], 'parent.growth.tracking', ['parent','verified']],

    //Appointment routes
    ['GET', '/parent/appointments/my-appointments', [AppointmentController::class, 'myAppointments'], 'parent.appointments.my', ['parent','verified']],
    ['GET', '/parent/appointments/child', [AppointmentController::class, 'childAppointments'], 'parent.appointments.child', ['parent','verified']],
    ['POST', '/parent/appointment/{id}/cancel', [AppointmentController::class, 'cancelMyAppointment'], 'parent.appointment.cancel', ['parent','verified']],
    ['POST', '/parent/appointment/{id}/child/cancel', [AppointmentController::class, 'cancelChildAppointment'], 'parent.appointment.child.cancel', ['parent','verified']],
    
    //Event routes
    ['GET', '/parent/events-campaigns', [EventController::class, 'index'], 'parent.events.campaigns', ['parent','verified']],
    ['POST', '/parent/events-campaigns/{id}/book', [EventController::class, 'bookEvent'], 'parent.events.campaigns.book', ['parent','verified']],
    ['POST', '/parent/events-campaigns/{id}/cancel', [EventController::class, 'cancelEventBooking'], 'parent.events.campaigns.cancel', ['parent','verified']],

    //Other routes
    ['GET', '/parent/calendar', [CalendarController::class, 'index'], 'parent.calendar', ['parent','verified']],
    ['GET', '/parent/notification', [NotificationController::class, 'index'], 'parent.notification', ['parent','verified']],
    ['GET', '/parent/settings', [SettingController::class, 'index'], 'parent.settings', ['parent','verified']],
];