<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260217150404_create_appointments
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260217150404_create_appointments implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw("CREATE TYPE appointment_status AS ENUM ('confirmed', 'pending', 'attended', 'cancelled', 'no-show')");
        QueryBuilder::raw(
            "CREATE TABLE appointments (
                id serial PRIMARY KEY,
                slot_id int NOT NULL REFERENCES appointment_slots (id) ON DELETE CASCADE,
                maternal_id int REFERENCES maternal (id),
                child_id int REFERENCES children (id),
                reason varchar(128) NULL,
                status appointment_status NOT NULL DEFAULT 'confirmed',
                attended_at timestamptz NULL,
                notes text
            );"
        );
        QueryBuilder::raw(
            "CREATE UNIQUE INDEX ux_appointment_slot_child ON appointments (slot_id, child_id) WHERE child_id IS NOT NULL;"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw("DROP TABLE IF EXISTS appointments;");
        QueryBuilder::raw("DROP TYPE IF EXISTS appointment_status;");
    }
}