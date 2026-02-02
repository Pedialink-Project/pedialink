<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260110102051_add_participants_count_column_events_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260110102051_add_participants_count_column_events_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
         QueryBuilder::raw(
            "ALTER TABLE events
            ADD COLUMN participants_count INT DEFAULT 0;"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw(
            "ALTER TABLE events
            DROP COLUMN participants_count;"
        );
    }
}