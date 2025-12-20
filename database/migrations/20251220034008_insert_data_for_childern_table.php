<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;

/**
 * Migration: 20251220034008_insert_data_for_childern_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20251220034008_insert_data_for_childern_table implements \Library\Framework\Database\Migration
{

    private string $parentEmail = "keeththi2003@gmail.com";

    private string $phmEmail = "nirmal@gmail.com";

    public function up(): void
    {
        QueryBuilder::raw(
            "INSERT INTO children (name, date_of_birth, parent_id, phm_id, gender, blood_type, birth_certificate)
            VALUES 
            ('Sara Johnson','2023-01-01',(SELECT id FROM users WHERE email = '{$this->parentEmail}' LIMIT 1),(SELECT id FROM users WHERE email = '{$this->phmEmail}' LIMIT 1), 'f', 'O+', 'BC-4567'),
            ('Liam Smith','2025-12-10',(SELECT id FROM users WHERE email = '{$this->parentEmail}' LIMIT 1),(SELECT id FROM users WHERE email = '{$this->phmEmail}' LIMIT 1), 'm', 'A+', 'BC-1234')
            ;"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw(
            "DELETE FROM children
            WHERE name IN ('Sara Johnson', 'Liam Smith')
            AND parent_id = (SELECT id FROM users WHERE email = '{$this->parentEmail}' LIMIT 1);"
        );
    }
}