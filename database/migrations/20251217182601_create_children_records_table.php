<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;

/**
 * Migration: 20251217182601_create_children_records_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20251217182601_create_children_records_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
         "CREATE TABLE IF NOT EXISTS child_records (
                id SERIAL PRIMARY KEY,
                child_id INT REFERENCES children(id) ON DELETE CASCADE,
                staff_id INT REFERENCES staffs(id) ON DELETE SET NULL,
                visit_date DATE NOT NULL,
                age_recorded_at INT NOT NULL,
                height REAL,
                weight REAL,
                bmi REAL,
                head_circumference REAL,
                notes TEXT,
                created_at TIMESTAMP WITH TIME ZONE DEFAULT now()
            );"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw("DROP TABLE IF EXISTS child_records;");
    }
}