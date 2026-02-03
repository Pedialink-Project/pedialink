<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260201140214_create_schedule_vaccines_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260201140214_create_schedule_vaccines_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
            "CREATE TABLE IF NOT EXISTS schedule_vaccines (
                id SERIAL PRIMARY KEY,
                vaccine_id INT REFERENCES vaccines (id) NOT NULL,
                schedule_id INT REFERENCES schedules (id) NOT NULL,
                dose_number INT NOT NULL,
                min_age_days INT NOT NULL,
                due_age_days INT NOT NULL,
                min_age_gap_days INT NOT NULL,
                additional_information TEXT
            );"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw("DROP TABLE IF EXISTS schedule_vaccines;");
    }
}