<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;

/**
 * Migration: 20251127105858_create_test_table_php
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20251127105858_create_test_table_php implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw("CREATE TYPE catergory AS ENUM ('furniture','lighting','kitchen','bedding','footwear');");
        QueryBuilder::raw(
            "CREATE TABLE IF NOT EXISTS test (
                id SERIAL PRIMARY KEY,
                name TEXT NOT NULL,
                category catergory NOT NULL,
                stock INT NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                created_at TIMESTAMP WITH TIME ZONE DEFAULT now()
            );"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw("DROP TABLE IF EXISTS test;");
        QueryBuilder::raw("DROP TYPE IF EXISTS catergory;");
    }
}