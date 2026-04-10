<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260208045001_create_vaccination_reminders
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260208045001_create_vaccination_reminders implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
            "CREATE TABLE IF NOT EXISTS vaccination_reminders (
                id SERIAL PRIMARY KEY,
                child_id INT REFERENCES children (id),
                schedule_vaccine_id INT REFERENCES schedule_vaccines (id),
                scheduled_date DATE NOT NULL,
                CONSTRAINT ux_child_sv_date UNIQUE (child_id, schedule_vaccine_id, scheduled_date)
            );"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw(
            "DROP TABLE IF EXISTS vaccination_reminders;"
        );
    }
}