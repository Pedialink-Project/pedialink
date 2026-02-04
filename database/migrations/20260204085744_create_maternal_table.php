<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260204085744_create_maternal_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260204085744_create_maternal_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw("CREATE TYPE maternal_type AS ENUM('pregnant', 'none')");
        QueryBuilder::raw(
            "CREATE TABLE IF NOT EXISTS maternal (
                id SERIAL PRIMARY KEY,
                parent_id INT REFERENCES users (id),
                type maternal_type NOT NULL,
            )"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw("DROP TYPE IF EXISTS maternal_type");
        QueryBuilder::raw("DROP TABLE IF EXISTS maternal");
    }
}