<?php

namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;

class Migration_20260124100002_alter_blood_pressure_column_to_varchar implements \Library\Framework\Database\Migration
{
    // public function up()
    // {
    //     // Alter the blood_pressure column to VARCHAR type
    //     $sql = "ALTER TABLE maternal_records ALTER COLUMN blood_pressure TYPE VARCHAR(20)";
    //     db()->statement($sql);
    // }

    // public function down()
    // {
    //     // Revert to integer if needed
    //     $sql = "ALTER TABLE maternal_records ALTER COLUMN blood_pressure TYPE INTEGER";
    //     db()->statement($sql);
    // }
    public function up(): void
    {
        QueryBuilder::raw(
            "ALTER TABLE maternal_records
            ALTER COLUMN blood_pressure TYPE VARCHAR(20);"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw(
            "ALTER TABLE maternal_records
            ALTER COLUMN blood_pressure TYPE VARCHAR(20);"
        );
    }
}
