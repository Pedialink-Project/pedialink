<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260201054329_create_access_requests_table_php
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260201054329_create_access_requests_table_php implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
            "CREATE TABLE IF NOT EXISTS maternal_access_requests(
                id SERIAL PRIMARY KEY,
                staff_id INT REFERENCES staffs(id) NOT NULL,
                maternal_id INT REFERENCES parents(id) NOT NULL,
                reason_title TEXT NOT NULL,
                reason_description TEXT NOT NULL,
                accepted BOOLEAN DEFAULT FALSE,
                created_at TIMESTAMP WITH TIME ZONE DEFAULT now()
            );"
        );

        QueryBuilder::raw(
            "CREATE TABLE IF NOT EXISTS children_access_requests(
                id SERIAL PRIMARY KEY,
                staff_id INT REFERENCES staffs(id) NOT NULL,
                child_id INT REFERENCES children(id) NOT NULL,
                reason_title TEXT NOT NULL,
                reason_description TEXT NOT NULL,
                accepted BOOLEAN DEFAULT FALSE,
                created_at TIMESTAMP WITH TIME ZONE DEFAULT now()
            );"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw(
            "DROP TABLE IF EXISTS children_access_requests;"
        );
        QueryBuilder::raw(
            "DROP TABLE IF EXISTS maternal_access_requests;"
        );
    }
}