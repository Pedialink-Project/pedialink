<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260207121612_create_vaccinations_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260207121612_create_vaccinations_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
            "CREATE TABLE IF NOT EXISTS vaccinations (
                id SERIAL PRIMARY KEY,
                schedule_vaccine_id INT REFERENCES schedule_vaccines (id),
                child_id INT REFERENCES children (id),
                administered_at TIMESTAMP WITH TIME ZONE NOT NULL,
                recorded_at TIMESTAMP WITH TIME ZONE DEFAULT now()
            );"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw(
            "DROP TABLE IF EXISTS vaccinations;"
        );
    }
}