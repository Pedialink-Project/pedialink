<?php
namespace Database\Migrations;

use Library\Framework\Database\QueryBuilder;

/**
 * Migration: 20251216180957_children_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20251216180957_children_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
         "CREATE TABLE IF NOT EXISTS children (
                id SERIAL PRIMARY KEY,
                name VARCHAR(100),
                date_of_birth DATE NOT NULL,
                gender CHAR(1),
                birth_certificate TEXT NOT NULL,
                area_id INT REFERENCES areas(id) ON DELETE RESTRICT,
                created_at TIMESTAMP WITH TIME ZONE DEFAULT now()
            );"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw("DROP TABLE IF EXISTS children;");
    }
}