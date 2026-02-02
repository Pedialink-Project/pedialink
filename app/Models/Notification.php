<?php

namespace App\Models;

use Library\Framework\Core\Model;

class Notification extends Model
{
    protected static string $table = "notifications";

    protected array $fillable = [
        "recipient_id",
        "title",
        "message",
        "entity_type",
        "entity_id",
        "is_read",
        "created_at",
    ];

    /**
     * Get the user who received the notification
     */
    public function getRecipient(): ?object
    {
        return User::find($this->recipient_id);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(): bool
    {
        $this->is_read = true;
        return $this->save();
    }

    /**
     * Check if notification is unread
     */
    public function isUnread(): bool
    {
        return !$this->is_read;
    }
}
