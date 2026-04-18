<?php

use App\Controllers\Doctor\ChildHealthController;
use App\Controllers\Doctor\DashboardController;
use App\Controllers\Doctor\ChildProfileController;
use App\Controllers\Doctor\MaternalProfileController;
use App\Controllers\Doctor\CalendarController;
use App\Controllers\Doctor\MaternalHealthController;
use App\Controllers\Doctor\AppointmentController;
use App\Controllers\NotificationController;
use App\Controllers\SettingController;

return [
    ['GET', '/doctor/dashboard', [DashboardController::class, 'index'], 'doctor.dashboard', ['doctor', 'verified']],
    
    //Child profile routes
    ['GET', '/doctor/child-profiles', [ChildProfileController::class, 'index'], 'doctor.child.profiles', ['doctor', 'verified']],
    ['GET', '/doctor/child-profiles/{id}/health-records', [ChildHealthController::class, 'index'], 'doctor.child.health', ['doctor', 'verified']],
    ['POST', '/doctor/child-profiles/{id}/health-records/add', [ChildHealthController::class, 'addHealthRecord'], 'doctor.child.health.add', ['doctor', 'verified']],
    ['POST', '/doctor/child-profiles/{id}/health-records/{recordId}/edit', [ChildHealthController::class, 'editHealthRecord'], 'doctor.child.health.edit', ['doctor', 'verified']],
    ['POST','/doctor/child-profiles/{id}/health-records/{recordId}/mark-as-invalid', [ChildHealthController::class,'markAsInvalid'], 'doctor.child.health.markinvalid', ['doctor', 'verified']],
    // ['POST', '/doctor/childprofile/request-access', [ChildProfileController::class, 'requestAccess'], 'doctor.childprofile.requestAccess', ['doctor', 'verified']],
    // ['POST', '/doctor/childprofile/{id}/cancel-request-access', [ChildProfileController::class, 'cancelAccessRequest'], 'doctor.childprofile.cancel.requestAccess', ['doctor', 'verified']],
    ['GET', '/doctor/child-profiles/{id}/vaccination-records', [ChildHealthController::class, 'vaccinationIndex'], 'doctor.child.vaccination', ['doctor', 'verified']],
    ['POST', '/doctor/child-profiles/{id}/health-records/{recordId}/add-notes', [ChildHealthController::class, 'addNotes'], 'doctor.child.health.add.notes', ['doctor', 'verified']],

    //Maternal profile routes
    ['GET', '/doctor/maternal-profiles', [MaternalProfileController::class, 'index'], 'doctor.maternal.profiles', ['doctor', 'verified']],
    // ['POST', '/doctor/maternalprofile/request-access', [MaternalProfileController::class, 'requestAccess'], 'doctor.maternalprofile.requestAccess', ['doctor', 'verified']],
    // ['POST', '/doctor/maternalprofile/{id}/cancel-request-access', [MaternalProfileController::class, 'cancelAccessRequest'], 'doctor.maternalprofile.cancel.requestAccess', ['doctor', 'verified']],
    ['GET', '/doctor/maternal-profiles/{id}/health-records', [MaternalHealthController::class, 'index'], 'doctor.maternal.health', ['doctor', 'verified']],
    ['POST', '/doctor/maternal-profile/{id}/health-record/add', [MaternalHealthController::class, 'addHealthRecord'], 'doctor.maternal.health.add', ['doctor', 'verified']],
    ['POST', '/doctor/maternal-profiles/{id}/health-records/{recordId}/edit', [MaternalHealthController::class, 'editHealthRecord'], 'doctor.maternal.health.edit', ['doctor', 'verified']],
    ['POST', '/doctor/maternal-profiles/{id}/health-records/{recordId}/mark-as-invalid', [MaternalHealthController::class, 'markAsInvalid'], 'doctor.maternal.health.markinvalid', ['doctor', 'verified']],
        ['POST', '/doctor/maternal-profiles/{id}/health-records/{recordId}/add-notes', [MaternalHealthController::class, 'addNotes'], 'doctor.maternal.health.add.notes', ['doctor', 'verified']],

    //Appointment rotues
    ['GET', '/doctor/appointments/overview', [AppointmentController::class, 'overview'], 'doctor.appointments.overview', ['doctor', 'verified']],
    ['GET', '/doctor/appointments/{id}/{type}/history', [AppointmentController::class, 'viewHistory'], 'doctor.appointments.history', ['doctor', 'verified']],
    ['GET', '/doctor/appointments/configure', [AppointmentController::class, 'configure'], 'doctor.appointments.configure', ['doctor', 'verified']],
    ['POST', '/doctor/appointments/configure/create', [AppointmentController::class, 'createAvailability'], 'doctor.appointments.configure.create', ['doctor', 'verified']],
    ['POST', '/doctor/appointments/configure/{id}/disable', [AppointmentController::class, 'disableAvailability'], 'doctor.appointments.configure.disable', ['doctor', 'verified']],
    ['POST', '/doctor/appointments/configure/{id}/enable', [AppointmentController::class, 'enableAvailability'], 'doctor.appointments.configure.enable', ['doctor', 'verified']],
    ['POST', '/doctor/appointments/configure/{id}/edit', [AppointmentController::class, 'editAvailability'], 'doctor.appointments.configure.edit', ['doctor', 'verified']],

    //Others
    ['GET', '/doctor/notification', [NotificationController::class, 'index'], 'doctor.notification', ['doctor', 'verified']],
    ['GET', '/doctor/settings', [SettingController::class, 'index'], 'doctor.settings', ['doctor', 'verified']],
    ['GET', '/doctor/calendar', [CalendarController::class, 'index'], 'doctor.calendar', ['doctor', 'verified']],
];