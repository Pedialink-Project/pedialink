<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260217145601_alter_pergnancy_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260217145601_alter_pergnancy_table implements \Library\Framework\Database\Migration
{
      public function up(): void
    {
        QueryBuilder::raw("
            ALTER TABLE pregnancy
            ADD COLUMN IF NOT EXISTS lmp DATE,
            ADD COLUMN IF NOT EXISTS edd DATE,
            ADD COLUMN IF NOT EXISTS gravida INT,
            ADD COLUMN IF NOT EXISTS para INT,
            ADD COLUMN IF NOT EXISTS delivery_outcome VARCHAR(20),
            ADD COLUMN IF NOT EXISTS created_at TIMESTAMPTZ DEFAULT now();
            DROP COLUMN IF EXISTS started_at;
        ");
    }

    public function down(): void
    {
        QueryBuilder::raw("
            ALTER TABLE pregnancy
            DROP COLUMN IF EXISTS lmp,
            DROP COLUMN IF EXISTS edd,
            DROP COLUMN IF EXISTS gravida,
            DROP COLUMN IF EXISTS para,
            DROP COLUMN IF EXISTS delivery_outcome,
            DROP COLUMN IF EXISTS created_at;
            ADD COLUMN IF NOT EXISTS started_at DATE;
        ");
    }
}