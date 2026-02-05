<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260203181120_add_column_health_status_child_record_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260203181120_add_column_health_status_child_record_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
            "ALTER TABLE child_records
            ADD COLUMN health_status VARCHAR(20) DEFAULT 'unknown';"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw(
            "ALTER TABLE child_records
            DROP COLUMN health_status;"
        );
    }
}