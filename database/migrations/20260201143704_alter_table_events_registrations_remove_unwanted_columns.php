<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260201143704_alter_table_events_registrations_remove_unwanted_columns
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260201143704_alter_table_events_registrations_remove_unwanted_columns implements \Library\Framework\Database\Migration
{
   public function up(): void
    {
        QueryBuilder::raw("
            ALTER TABLE events_registrations
            DROP COLUMN IF EXISTS name,
            DROP COLUMN IF EXISTS email,
            DROP COLUMN IF EXISTS phone;
        ");
    }

     public function down(): void
    {
        QueryBuilder::raw("
            ALTER TABLE events_registrations
            ADD COLUMN name VARCHAR(255) NOT NULL,
            ADD COLUMN email VARCHAR(255) NOT NULL,
            ADD COLUMN phone VARCHAR(20);
        ");
    }
}