<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;

/**
 * Migration: 20251125171614_insert_data_for_parents_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20251125171614_insert_data_for_parents_table implements \Library\Framework\Database\Migration
{

    private string $parentMail = "keeththi2003@gmail.com";
    public function up(): void
    {
        $commonPassword = password_hash("qwerty123", PASSWORD_DEFAULT);
        QueryBuilder::raw(
            "INSERT INTO users (name, email, password_hash, role)
            VALUES
            ('Keeththi', '{$this->parentMail}', '{$commonPassword}', 'parent');"
        );

        QueryBuilder::raw(
            "INSERT INTO parents (id, type, nic, address, area_id)
            VALUES
            ((SELECT id FROM users WHERE email = '{$this->parentMail}'),'mother', '200315300887', 'Jaffna', 1 );"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw(
            "DELETE FROM parents p
            USING users u
            WHERE p.id = u.id
            AND email = '{$this->parentMail}';"
        );

        QueryBuilder::raw(
            "DELETE FROM users WHERE email = '{$this->parentMail}';"
        );
    }
}