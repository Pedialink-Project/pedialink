<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20251229044140_alter_table_users_add_email_verified
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20251229044140_alter_table_users_add_email_verified implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
            "ALTER TABLE users
            ADD COLUMN email_verified BOOLEAN NOT NULL DEFAULT FALSE;"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw(
            "ALTER TABLE users
            DROP COLUMN email_verified;"
        );
    }
}