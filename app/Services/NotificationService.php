<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
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
}
