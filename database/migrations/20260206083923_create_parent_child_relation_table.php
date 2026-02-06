<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260206083923_create_parent_child_relation_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260206083923_create_parent_child_relation_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
            "CREATE TABLE IF NOT EXISTS parent_children(
                id SERIAL PRIMARY KEY,
                child_id INT REFERENCES children (id),
                parent_id INT REFERENCES parents (id)
            );"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw(
            "DROP TABLE IF EXISTS parent_children;"
        );
    }
}