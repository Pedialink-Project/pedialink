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
}
