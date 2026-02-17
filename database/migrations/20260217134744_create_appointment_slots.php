<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260217134744_create_appointment_slots
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260217134744_create_appointment_slots implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
            "CREATE TABLE IF NOT EXISTS appointment_slots (
                id serial PRIMARY KEY,
                slot_date date NOT NULL,
                start_time time NOT NULL,
                end_time time NOT NULL,
                doctor_id int REFERENCES doctors (id),
                capacity int NOT NULL DEFAULT 1,
                booked_count int NOT NULL DEFAULT 0,
                status varchar(16) NOT NULL DEFAULT 'open',
                created_at timestamptz DEFAULT now()
            );"
        );

        QueryBuilder::raw(
            "CREATE UNIQUE INDEX ux_slot_date_time_doctor ON appointment_slots (slot_date, start_time, doctor_id);"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw("DROP TABLE IF EXISTS appointment_slots;");
    }
}