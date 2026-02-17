<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260217143553_alter_maternal_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260217143553_alter_maternal_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw("
            ALTER TABLE maternal
            ADD COLUMN IF NOT EXISTS height REAL,
            ADD COLUMN IF NOT EXISTS blood_group blood_type,
            ADD COLUMN IF NOT EXISTS created_at TIMESTAMPTZ DEFAULT now();
        ");
    }

    public function down(): void
    {
        QueryBuilder::raw("
            ALTER TABLE maternal
            DROP COLUMN IF EXISTS height,
            DROP COLUMN IF EXISTS blood_group,
            DROP COLUMN IF EXISTS created_at;
        ");
    }
}