<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20251221091441_add_dob_column_parents_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20251221091441_add_dob_column_parents_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
            "ALTER TABLE parents
            ADD COLUMN date_of_birth DATE;"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw("ALTER TABLE parents DROP COLUMN IF EXISTS date_of_birth;");
    }
}