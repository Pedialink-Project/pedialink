<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260204092547_create_pregnancy_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260204092547_create_pregnancy_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        // Calculate trimester by calculating gestational
        // weeks from start_date.
        // end_date is initially NULL
        QueryBuilder::raw(
            "CREATE TABLE IF NOT EXISTS pregnancy (
                id SERIAL PRIMARY KEY,
                end_at DATE,
                maternal_id INT REFERENCES maternal (id)
            );"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw("DROP TABLE IF EXISTS pregnancy;");
    }
}