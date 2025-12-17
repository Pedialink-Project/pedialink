<?php
namespace Database\Migrations;

use Library\Framework\Database\QueryBuilder;

/**
 * Migration: 20251217181653_add_stats_children_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20251217181653_add_stats_children_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {

        QueryBuilder::raw(
            "CREATE TYPE blood_type AS ENUM ('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-');"
        );
            
        QueryBuilder::raw(
            "ALTER TABLE children
            ADD COLUMN blood_type blood_type;"
        );

    }

    public function down(): void
    {
        QueryBuilder::raw(
            "ALTER TABLE children
            DROP COLUMN IF EXISTS blood_type;"
        );

        QueryBuilder::raw(
            "DROP TYPE IF EXISTS blood_type;"
        );
        
    }
}