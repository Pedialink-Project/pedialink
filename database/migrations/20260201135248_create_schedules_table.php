<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260201135248_create_schedules_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260201135248_create_schedules_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
            "CREATE TABLE IF NOT EXISTS schedules (
                id SERIAL PRIMARY KEY,
                name TEXT NOT NULL,
                version TEXT NOT NULL,
                effective_from DATE NOT NULL,
                active BOOLEAN DEFAULT FALSE
            );"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw("DROP TABLE IF EXISTS schedules;");
    }
}