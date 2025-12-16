<?php
namespace Database\Migrations;

use Library\Framework\Database\QueryBuilder;

/**
 * Migration: 20251216175014_patients_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20251216175014_patients_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(sql: "CREATE TYPE patient_type AS ENUM ('maternal','child');");

        QueryBuilder::raw("CREATE TABLE patients (
    id              SERIAL PRIMARY KEY,
    parent_id       INT NOT NULL REFERENCES parents(id) ON DELETE RESTRICT,
    area_id         INT NOT NULL REFERENCES areas(id) ON DELETE RESTRICT,

    patient_type    patient_type NOT NULL,
    status          VARCHAR(50),

    created_at      TIMESTAMP WITH TIME ZONE DEFAULT now(),
    updated_at      TIMESTAMP WITH TIME ZONE DEFAULT now()
);
");
    }

    public function down(): void
    {
        // TODO: revert changes made in up()
    }
}