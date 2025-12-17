<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;

/**
 * Migration: 20251217184211_create_maternal_records_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20251217184211_create_maternal_records_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw("CREATE TYPE trimester_type AS ENUM ('first', 'second', 'third');");
        QueryBuilder::raw(
         "CREATE TABLE IF NOT EXISTS maternal_records (
                id SERIAL PRIMARY KEY,
                parent_id INT REFERENCES parents(id) ON DELETE CASCADE,
                staff_id INT REFERENCES staffs(id) ON DELETE SET NULL,
                visit_date DATE NOT NULL,
                trimester trimester_type NOT NULL,
                weight REAL,
                notes TEXT,
                created_at TIMESTAMP WITH TIME ZONE DEFAULT now()
            );"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw("DROP TABLE IF EXISTS maternal_records;");
        QueryBuilder::raw("DROP TYPE IF EXISTS trimester_type;");
    }
}