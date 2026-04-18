<?php
namespace Database\Migrations;

use Library\Framework\Database\QueryBuilder;

/**
 * Migration: 20251125111120_create_areas_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20251125111120_create_areas_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
            "CREATE TABLE IF NOT EXISTS areas (
                id SERIAL PRIMARY KEY,
                code TEXT NOT NULL
            );"
        );

        QueryBuilder::raw(
            "INSERT INTO areas (code) VALUES
            ('Dehiwala East'),
            ('Dehiwala West'),
            ('Karagampitiya'),
            ('Kalubowila'),
            ('Galwala'),
            ('Kohuwala'),
            ('Udyana'),
            ('Nedimala'),
            ('Vilawala');"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw("DROP TABLE IF EXISTS areas;");
    }
}