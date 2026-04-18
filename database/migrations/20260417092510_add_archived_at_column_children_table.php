<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260417092510_add_archived_at_column_children_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260417092510_add_archived_at_column_children_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw("ALTER TABLE children ADD COLUMN archived_at TIMESTAMP NULL DEFAULT NULL");
    }

    public function down(): void
    {
        QueryBuilder::raw("ALTER TABLE children DROP COLUMN archived_at");
    }
}   