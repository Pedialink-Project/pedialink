<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260203183204_alter_table_child_record_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260203183204_alter_table_child_record_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
            "ALTER TABLE child_records
            DROP COLUMN IF EXISTS age_recorded_at;"
        );

        QueryBuilder::raw(
            "ALTER TABLE child_records
            ADD COLUMN mark_as_invalid BOOLEAN DEFAULT 'false';"
        );


    }

    public function down(): void
    {
        QueryBuilder::raw(
            "ALTER TABLE child_records
            ADD COLUMN age_recorded_at INT NULL;"
        );

        QueryBuilder::raw(
            "ALTER TABLE child_records
            DROP COLUMN IF EXISTS mark_as_invalid;"
        );
    }
}