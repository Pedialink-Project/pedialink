<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260303175429_add_mark_as_invalid_maternal_records_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260303175429_add_mark_as_invalid_maternal_records_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
         QueryBuilder::raw(
            "ALTER TABLE maternal_records
            ADD COLUMN mark_as_invalid BOOLEAN DEFAULT 'false';"
        );

    }

    public function down(): void
    {
        QueryBuilder::raw("ALTER TABLE maternal_records DROP COLUMN IF EXISTS mark_as_invalid;");
    }
}