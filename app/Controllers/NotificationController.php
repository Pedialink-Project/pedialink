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

        return redirect(route("{$userRole}.notification"))->withMessage('Success', 'Notification marked as read.', 'info');
    }



    public function markAsReadAll($request)
    {
        $user = auth()->user()->id;
        $userRole = auth()->user()->role;
        $unreadCount = $this->notificationService->countUnread($user);

        if ($unreadCount === 0) {
            return redirect(route("{$userRole}.notification"))
                ->withMessage(
                    'No unread notifications',
                    'You have no unread notifications',
                    'info'
                );
        }

        $this->notificationService->markAllAsRead($user);





        return redirect(url: route("{$userRole}.notification"))->withMessage('Success', 'All notifications have been marked as read', 'info');
    }



    public function deleteNotification($request, $notificationId)
    {

        $user = auth()->user()->id;
        $error = $this->notificationService->deleteNotification($notificationId, $user);
        $userRole = auth()->user()->role;

        if ($error) {
            return redirect(route("{$userRole}.notification"))->withMessage($error, 'Notification not Deleted.', 'error');
        }

        return redirect(route("{$userRole}.notification"))->withMessage('Success', 'Notification deleted successfully.', 'info');
    }

    public function deleteAllNotification($request)
    {

        $user = auth()->user()->id;
        $this->notificationService->deleteAllNotification($user);
        $userRole = auth()->user()->role;


        return redirect(route("{$userRole}.notification"))->withMessage('Success', 'All Notifications deleted successfully.', 'info');
    }
}
