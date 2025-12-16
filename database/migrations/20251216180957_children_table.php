<?php
namespace Database\Migrations;

use Library\Framework\Database\QueryBuilder;

/**
 * Migration: 20251216180957_children_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20251216180957_children_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
            sql: "CREATE TABLE children (
    patient_id     INT PRIMARY KEY REFERENCES patients(id) ON DELETE CASCADE,

    name           VARCHAR(100),
    date_of_birth  DATE NOT NULL,
    gender         VARCHAR(10),
    child_type     VARCHAR(50),
    status         VARCHAR(50),
    area_id INT REFERENCES areas(id) ON DELETE RESTRICT,
    created_at     TIMESTAMP WITH TIME ZONE DEFAULT now()
);"
        );
    }

    public function down(): void
    {
        // TODO: revert changes made in up()
    }
}