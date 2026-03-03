<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260217201537_alter_maternal_type
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260217201537_alter_maternal_type implements \Library\Framework\Database\Migration
{
   public function up(): void
{
    QueryBuilder::raw("
        CREATE TYPE maternal_type AS ENUM(
            'antenatal',
            'postnatal',
            'none'
        );
    ");

    QueryBuilder::raw("
        ALTER TABLE maternal
        ADD COLUMN IF NOT EXISTS type maternal_type NOT NULL;;
    ");
}


    public function down(): void
    {
    QueryBuilder::raw("
        ALTER TABLE maternal
        DROP COLUMN IF EXISTS type;
    ");

    QueryBuilder::raw("
        DROP TYPE IF EXISTS maternal_type;
    ");
    }
}