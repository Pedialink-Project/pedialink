<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260217134629_create_doctor_weekly_availability
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260217134629_create_doctor_weekly_availability implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
            "CREATE TABLE IF NOT EXISTS doctor_weekly_availability (
                id serial PRIMARY KEY,
                doctor_id int REFERENCES doctors (id),                       -- FK to users/doctors if you have them
                weekday smallint NOT NULL CHECK (weekday BETWEEN 0 AND 6),
                active boolean NOT NULL DEFAULT true,
                start_time time NOT NULL,
                end_time time NOT NULL,
                slot_length_minutes int NOT NULL DEFAULT 60,
                created_at timestamptz DEFAULT now()
            );"
        );

        QueryBuilder::raw(
            "INSERT INTO doctor_weekly_availability (
                doctor_id, weekday, active, start_time, end_time, slot_length_minutes
            )
            VALUES
            ((SELECT id FROM users WHERE email = 'sarah@gmail.com' LIMIT 1), 1, true, '09:00', '12:00', 60),
            ((SELECT id FROM users WHERE email = 'sarah@gmail.com' LIMIT 1), 3, true, '09:00', '12:00', 60);"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw(
            "DROP TABLE IF EXISTS doctor_weekly_availability;"
        );
    }
}