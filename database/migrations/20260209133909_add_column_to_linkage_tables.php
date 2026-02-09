<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260209133909_add_column_to_linkage_tables
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260209133909_add_column_to_linkage_tables implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
            "ALTER TABLE child_miscs
                ADD COLUMN IF NOT EXISTS accepted BOOLEAN DEFAULT FALSE;"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw(
            "ALTER TABLE child_miscs
                DROP COLUMN IF EXISTS accepted;"
        );
    }
}