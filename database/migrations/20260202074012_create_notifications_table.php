<?php

namespace Database\Migrations;

use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260202074012_create_notifications_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260202074012_create_notifications_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw("
        CREATE TABLE notifications (
            id SERIAL PRIMARY KEY,

            recipient_id INT NOT NULL
                REFERENCES users(id)
                ON DELETE CASCADE,

            title VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,

            entity_type VARCHAR(50),
            entity_id INT,

            is_read BOOLEAN DEFAULT FALSE,

            created_at TIMESTAMP WITH TIME ZONE DEFAULT now()
        );
    ");
    }

    public function down(): void
    {
        QueryBuilder::raw("DROP TABLE IF EXISTS notifications;");
    }
}
