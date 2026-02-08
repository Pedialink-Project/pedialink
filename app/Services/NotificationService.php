<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{

    /**
     * Summary of notification_time
     * @param string $datetime
     * @return string
     */
    public function notification_time(string $datetime): string
    {
        $timestamp = strtotime($datetime);
        $now = time();

        $diff = $now - $timestamp;

        // Less than 1 minute
        if ($diff < 60) {
            return 'Just now';
        }

        // Minutes
        if ($diff < 3600) {
            return floor($diff / 60) . 'm ago';
        }

        // Hours (today)
        if (date('Y-m-d') === date('Y-m-d', $timestamp)) {
            return floor($diff / 3600) . 'h ago';
        }

        // Yesterday
        if (date('Y-m-d', strtotime('yesterday')) === date('Y-m-d', $timestamp)) {
            return 'Yesterday';
        }

        // Example: Feb 05
        return date('M d', $timestamp);
    }


    /**
     * Send notification to a single user
     */
    public function notify(
        int $recipientId,
        string $title,
        string $message,
        ?string $entityType = null,
        ?int $entityId = null
    ): void {
        $notification = new Notification();
        $notification->recipient_id = $recipientId;
        $notification->title = $title;
        $notification->message = $message;
        $notification->entity_type = $entityType;
        $notification->entity_id = $entityId;
        $notification->save();
    }

    /**
     * Send notification to multiple users
     */
    public function notifyMany(
        array $recipientIds,
        string $title,
        string $message,
        ?string $entityType = null,
        ?int $entityId = null
    ): void {
        foreach ($recipientIds as $userId) {
            $this->notify($userId, $title, $message, $entityType, $entityId);
        }
    }

    public function notifyAdmins(
        string $title,
        string $message,
        ?string $entityType = null,
        ?int $entityId = null
    ): void {
        $admins = User::query()
            ->where('role', '=', 'admin')
            ->get();

        foreach ($admins as $admin) {
            $this->notify(
                $admin->id,
                $title,
                $message,
                $entityType,
                $entityId
            );
        }
    }


    /**
     * Get notifications for logged-in user (latest first)
     */
    public function getUserNotifications(int $userId,): array
    {
        $notifications = Notification::query()
            ->where('recipient_id', '=', $userId)
            ->orderBy('created_at', 'DESC')
            ->get();

        $resource = [];
        foreach ($notifications as $notification) {
            $resource[] = [
                'id' => $notification->id,
                'title' => $notification->title,
                'message' => $notification->message,
                'time' => $this->notification_time($notification->created_at),
                'is_read' => $notification->is_read,
            ];
        }

        return $resource;
    }

    /**
     * Get latest notifications for logged-in user (latest first)
     */
    public function markAsRead(int $notificationId, int $userId)
    {
        $notification = Notification::query()
            ->where('id', '=', $notificationId)
            ->where('recipient_id', '=', $userId)
            ->first();

        if (!$notification) {
            return "Notification not found.";
        }

        if ($notification->is_read) {
            return "Notification is already marked as read.";
        }

        $notification->is_read = true;
        $notification->save();
    }

    /**
     * Get latest notifications for logged-in user (latest first)
     */
    public function countUnread(int $userId): int
    {
        return count(
            Notification::query()
                ->where('recipient_id', '=', $userId)
                ->where('is_read', '=', 0)
                ->get()
        );
    }
    /**
     * Get latest notifications for logged-in user (latest first)
     */
    public function markAllAsRead(int $userId)
    {
        Notification::query()
            ->where('recipient_id', '=', $userId)
            ->where('is_read', '=', false)
            ->update([
                'is_read' => true
            ]);
    }

    /**
     * Delete a notification
     */

    public function deleteNotification(int $notificationId, int $userId): ?string
    {
        $notification = Notification::find($notificationId);

        if (!$notification) {
            return "Notification not found";
        }

        if ($notification->recipient_id !== $userId) {
            return "Unauthorized action";
        }

        $notification->delete();

        return null;
    }
    /**
     * Get latest notifications for logged-in user (latest first)
     */
    public function getNavbarData($userId)
    {
        $query = Notification::query()
            ->where('recipient_id', '=', $userId)
            ->orderBy('created_at', 'DESC');

        $unreadNotifucations = $query
            ->where('is_read', '=', 0)
            ->get();

        $notifications = $query->limit(3)->get();

        $resource = [];
        foreach ($notifications as $notification) {
            $resource[] = [
                'id' => $notification->id,
                'title' => $notification->title,
                'message' => $notification->message,
                'time' => $this->notification_time($notification->created_at),
                'is_read' => $notification->is_read,
            ];
        }


        return [
            'notifications' => $resource,
            'unreadCount' => count($unreadNotifucations),
        ];
    }

    /**
     * Delete all notifications for a user
     */
    public function deleteAllNotification(int $userId): void
    {
        Notification::query()
            ->where('recipient_id', '=', $userId)
            ->delete();
    }
}
