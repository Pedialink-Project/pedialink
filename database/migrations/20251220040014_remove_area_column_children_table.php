<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;

/**
 * Migration: 20251220040014_remove_area_column_children_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20251220040014_remove_area_column_children_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
            QueryBuilder::raw("ALTER TABLE children DROP area_id;");

    }

    public function down(): void
    {
            QueryBuilder::raw("ALTER TABLE children ADD area_id INT REFERENCES areas(id) ON DELETE RESTRICT;");
    }
}