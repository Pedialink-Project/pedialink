<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20251217163157_add_foreign_key_children_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20251217163157_add_foreign_key_children_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
            "ALTER TABLE children
            ADD COLUMN phm_id INT REFERENCES public_health_midwives(id) ON DELETE CASCADE;"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw("ALTER TABLE children DROP phm_id;");
    }
}