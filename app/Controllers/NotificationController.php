<?php

namespace App\Controllers;

use App\Services\NotificationService;
use App\Models\Notification;

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
        return view("auth/notification", ["notifications" => $notifications]);
    }

    public function markAsRead($request, $id)
    {
        $user = auth()->user()->id;
        $error = $this->notificationService->markAsRead($id, $user);

        $userRole = auth()->user()->role;

        if ($error) {
            return redirect(route("{$userRole}.notification"))->withMessage($error, 'Notification not marked as read.', 'error');
        }

        return redirect(route("{$userRole}.notification"))->withMessage('Success', 'Notification marked as read.', 'success');
    }

    public function markAsReadAll($request)
    {
        $user = auth()->user()->id;
       $updated = $this->notificationService->markAllAsRead($user);
        $userRole = auth()->user()->role;

        if ($updated) {
        return redirect(url: route("{$userRole}.notification"))->withMessage('You have no unread notifications', 'You have no unread notifications', 'success');

        }

        return redirect(url: route("{$userRole}.notification"))->withMessage('Success', 'All notifications have been marked as read', 'success');
    }



    public function deleteNotification($request, $notificationId)
    {

        $user = auth()->user()->id;
        $error = $this->notificationService->deleteNotification($notificationId, $user);
        $userRole = auth()->user()->role;

        if ($error) {
            return redirect(route("{$userRole}.notification"))->withMessage($error, 'Notification not Deleted.', 'error');
        }

        return redirect(route("{$userRole}.notification"))->withMessage('Success', 'Notification deleted successfully.', 'success');
    }

     public function deleteAllNotification($request)
    {

        $user = auth()->user()->id;
     $this->notificationService->deleteAllNotification($user);
        $userRole = auth()->user()->role;

        
        return redirect(route("{$userRole}.notification"))->withMessage('Success', 'All Notifications deleted successfully.', 'success');
    }

    
}
