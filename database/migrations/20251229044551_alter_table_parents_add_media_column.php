<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20251229044551_alter_table_parents_add_media_column
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20251229044551_alter_table_parents_add_media_column implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
            "ALTER TABLE parents
            ADD COLUMN birth_certificate TEXT,
            ADD COLUMN marriage_certificate TEXT,
            ADD COLUMN nic_copy TEXT;"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw(
            "ALTER TABLE parents
            DROP COLUMN nic_copy,
            DROP COLUMN marriage_certificate,
            DROP COLUMN birth_certificate;"
        );
    }
}