<?php
namespace Database\Migrations;

use Library\Framework\Database\QueryBuilder;

/**
 * Migration: 20251125121834_insert_data_for_admin_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20251125121834_insert_data_for_admin_table implements \Library\Framework\Database\Migration
{
    private string $adminMail = "admin@gmail.com";

    public function up(): void
    {
        $commonPassword = password_hash("qwerty123", PASSWORD_DEFAULT);
        QueryBuilder::raw(
            "INSERT INTO users (name, email, password_hash, role, email_verified)
            VALUES
            ('Mani', '{$this->adminMail}', '{$commonPassword}', 'admin', true);"
        );

        QueryBuilder::raw(
            "INSERT INTO admins (id, admin_type_id)
            VALUES
            ((SELECT id FROM users WHERE email = '{$this->adminMail}'), (SELECT id FROM admin_types WHERE type = 'super'));"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw(
            "DELETE FROM admins a
            USING users u
            WHERE a.id = u.id
            AND email = '{$this->adminMail}';"
        );

        QueryBuilder::raw(
            "DELETE FROM users WHERE email = '{$this->adminMail}';"
        );
    }
}