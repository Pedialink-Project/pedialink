<?php
namespace Database\Migrations;

use Library\Framework\Database\QueryBuilder;

/**
 * Migration: 20251217135855_create_child_access_requests_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20251217135855_create_child_access_requests_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
            "CREATE TABLE IF NOT EXISTS child_access_requests(
                staff_id INT REFERENCES staffs(id) ON DELETE CASCADE,
                children_id INT REFERENCES children(id) ON DELETE CASCADE,
                reason_title TEXT NOT NULL,
                reason_description TEXT NOT NULL,
                PRIMARY KEY (staff_id, children_id)
            );"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw("DROP TABLE IF EXISTS child_access_requests");
    }
}