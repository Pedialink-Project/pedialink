<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;

/**
 * Migration: 20260416113331_alter_vaccination_reminders_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260416113331_alter_vaccination_reminders_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
            "ALTER TABLE vaccination_reminders
             ADD COLUMN IF NOT EXISTS notification_count INT NOT NULL DEFAULT 0;"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw(
            "ALTER TABLE vaccination_reminders
             DROP COLUMN IF EXISTS notification_count;"
        );
    }
}