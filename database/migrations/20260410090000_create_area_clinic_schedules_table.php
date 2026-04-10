<?php
namespace Database\Migrations;

use Library\Framework\Database\QueryBuilder;

/**
 * Migration: 20260410090000_create_area_clinic_schedules_table
 */
class Migration_20260410090000_create_area_clinic_schedules_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
            "CREATE TABLE IF NOT EXISTS area_clinic_schedules (
                id SERIAL PRIMARY KEY,
                area_id INT REFERENCES areas (id) NOT NULL,
                weekday INT NOT NULL,
                ordinal_week INT NOT NULL,
                active BOOLEAN NOT NULL DEFAULT TRUE,
                CONSTRAINT ux_area_weekday_ordinal UNIQUE (area_id, weekday, ordinal_week)
            );"
        );

        QueryBuilder::raw(
            "INSERT INTO area_clinic_schedules (area_id, weekday, ordinal_week, active)
            SELECT a.id,
                   CASE WHEN a.id % 2 = 0 THEN 2 ELSE 4 END AS weekday,
                   w.ordinal_week,
                   TRUE
            FROM areas a
            CROSS JOIN (VALUES (1), (3)) AS w(ordinal_week)
            WHERE NOT EXISTS (
                SELECT 1
                FROM area_clinic_schedules acs
                WHERE acs.area_id = a.id
                  AND acs.weekday = CASE WHEN a.id % 2 = 0 THEN 2 ELSE 4 END
                  AND acs.ordinal_week = w.ordinal_week
            );"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw("DROP TABLE IF EXISTS area_clinic_schedules;");
    }
}
