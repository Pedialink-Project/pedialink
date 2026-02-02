<?php

namespace App\Controllers;

use App\Services\NotificationService;

class NotificationController
{
    private $notificationService;
    public function __construct()
    {
        $this->notificationService = new NotificationService();
    }
    public function index()
    {
        $user = auth()->user()->id;
        $notifications = $this->notificationService->getUserNotifications($user);
        return view("auth/notification",["notifications"=> $notifications]);
    }

    public function markAsRead($request, $id)
    {
        $user = auth()->user()->id;
        $error = $this->notificationService->markAsRead($id, $user);

        $userRole = auth()->user()->role;

        if ($error) {
            return redirect(route("{$userRole}.notification"))->withMessage($error, 'Notification not marked as read.', 'error');
        }

            return redirect(route("{$userRole}.notification"))->withMessage('success', 'Notification marked as read.', 'success');
    }
}
