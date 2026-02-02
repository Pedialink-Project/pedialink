<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20251229050817_alter_table_parents_add_verified
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20251229050817_alter_table_parents_add_verified implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
            "ALTER TABLE parents
            ADD COLUMN verified BOOLEAN NOT NULL DEFAULT FALSE;"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw(
            "ALTER TABLE parents
            DROP COLUMN verified;"
        );
    }
}