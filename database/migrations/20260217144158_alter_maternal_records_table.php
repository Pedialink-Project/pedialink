<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260217144158_alter_maternal_records_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260217144158_alter_maternal_records_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw("
            ALTER TABLE maternal_records
            ADD COLUMN IF NOT EXISTS bmi REAL,
            ADD COLUMN IF NOT EXISTS blood_pressure INT,
            ADD COLUMN IF NOT EXISTS hemoglobin REAL,
            ADD COLUMN IF NOT EXISTS glucose REAL,
            ADD COLUMN IF NOT EXISTS fetal_heart_rate INT,
            ADD COLUMN IF NOT EXISTS fundal_height REAL,
            ADD COLUMN IF NOT EXISTS health_status VARCHAR(20) DEFAULT 'normal'
        ");

    }

    public function down(): void
    {
        QueryBuilder::raw("
            ALTER TABLE maternal_records
            DROP COLUMN IF EXISTS bmi,
            DROP COLUMN IF EXISTS blood_pressure,
            DROP COLUMN IF EXISTS hemoglobin,
            DROP COLUMN IF EXISTS glucose,
            DROP COLUMN IF EXISTS fetal_heart_rate,
            DROP COLUMN IF EXISTS fundal_height,
            DROP COLUMN IF EXISTS health_status,
         ");
    }
}