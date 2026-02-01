<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260126053345_add_health_status_column_child_records_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260126053345_add_health_status_column_child_records_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        {
        QueryBuilder::raw(
            "ALTER TABLE child_records
            ADD COLUMN health_status TEXT;"
        );
    }
    }

    public function down(): void
    {
        QueryBuilder::raw("ALTER TABLE child_records DROP COLUMN IF EXISTS health_status;");
    }
}