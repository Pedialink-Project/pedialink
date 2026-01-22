<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260113184857_add_area_to_children_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260113184857_add_area_to_children_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
            "ALTER TABLE children
            ADD COLUMN area_id INT REFERENCES areas (id);"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw(
            "ALTER TABLE children
            DROP COLUMN area_id;"
        );
    }
}