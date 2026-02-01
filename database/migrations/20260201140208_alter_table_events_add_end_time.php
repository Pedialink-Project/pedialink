<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260201140208_alter_table_events_add_end_time
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260201140208_alter_table_events_add_end_time implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
         QueryBuilder::raw("
            ALTER TABLE events
            DROP COLUMN IF EXISTS event_status;
        ");

        QueryBuilder::raw("
            DROP TYPE IF EXISTS event_status;
        ");

        QueryBuilder::raw("
            ALTER TABLE events
            RENAME COLUMN event_time TO start_time;
        ");

        QueryBuilder::raw("
            ALTER TABLE events
            ADD COLUMN end_time TIME NOT NULL;
        ");
    }

    public function down(): void
    {
        // TODO: revert changes made in up()
    }
}