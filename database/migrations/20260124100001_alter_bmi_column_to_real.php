<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;

/**
 * Migration: 20260124100001_alter_bmi_column_to_real
 *
 * Change BMI column type from INT to REAL to support decimal values
 */
class Migration_20260124100001_alter_bmi_column_to_real implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
            "ALTER TABLE maternal_records
            ALTER COLUMN bmi TYPE REAL;"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw(
            "ALTER TABLE maternal_records
            ALTER COLUMN bmi TYPE INT;"
        );
    }
}
