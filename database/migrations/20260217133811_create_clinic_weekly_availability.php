<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260217133811_create_clinic_weekly_availability
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260217133811_create_clinic_weekly_availability implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
            "CREATE TABLE IF NOT EXISTS clinic_weekly_availability (
                id serial PRIMARY KEY,
                weekday smallint NOT NULL CHECK (weekday BETWEEN 0 AND 6),
                active boolean NOT NULL DEFAULT true,
                start_time time NOT NULL,
                end_time time NOT NULL,
                slot_length_minutes int NOT NULL DEFAULT 60,
                created_at timestamptz DEFAULT now()
            );"
        );

        QueryBuilder::raw(
            "INSERT INTO clinic_weekly_availability (
                weekday, active, start_time, end_time, slot_length_minutes
            )
            VALUES
            (0, false, '09:00', '17:00', 60),
            (1, true, '09:00', '17:00', 60),
            (2, false, '09:00', '17:00', 60),
            (3, true, '09:00', '17:00', 60),
            (4, false, '09:00', '17:00', 60);"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw("DROP TABLE IF EXISTS clinic_weekly_availability");
    }
}