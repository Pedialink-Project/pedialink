<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;

/**
 * Migration: 20260127000001_fix_bmi_column_type_maternal_records_table
 * 
 * Changes BMI column from INT to REAL to properly store decimal values
 */
class Migration_20260127000001_fix_bmi_column_type_maternal_records_table implements \Library\Framework\Database\Migration
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
