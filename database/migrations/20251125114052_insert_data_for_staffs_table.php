<?php
namespace Database\Migrations;

use Library\Framework\Database\QueryBuilder;

/**
 * Migration: 20251125114052_insert_data_for_staffs_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20251125114052_insert_data_for_staffs_table implements \Library\Framework\Database\Migration
{
    private string $phmName = "nirmal@gmail.com";
    private string $doctorName = "sarah@gmail.com";
    private string $commonPassword;

    public function __construct()
    {
        $this->commonPassword = password_hash("qwerty123", PASSWORD_DEFAULT);
    }

    public function up(): void
    {
        QueryBuilder::raw(
            "INSERT INTO users (name, email, password_hash, role, email_verified)
                VALUES
                ('Nirmal', '{$this->phmName}', '{$this->commonPassword}', 'phm', true),
                ('Sarah', '{$this->doctorName}', '{$this->commonPassword}', 'doctor', true);"
        );

        QueryBuilder::raw(
            "INSERT INTO public_health_midwives (id, area_id)
            VALUES ((SELECT id FROM users WHERE email = '{$this->phmName}' LIMIT 1), 1);"
        );

        QueryBuilder::raw(
            "INSERT INTO doctors (id)
            VALUES ((SELECT id FROM users WHERE email = '{$this->doctorName}' LIMIT 1));"
        );

        QueryBuilder::raw(
            "INSERT INTO staffs (id, nic, license_no)
            VALUES
            ((SELECT id FROM users WHERE email = '{$this->phmName}' LIMIT 1), '198510212346', 'PHM-00543'),
            ((SELECT id FROM users WHERE email = '{$this->doctorName}' LIMIT 1), '199070134567', 'REG-DR-2025-0123');"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw(
            "DELETE FROM staffs s
            USING users u
            WHERE s.id = u.id
            AND email = '{$this->doctorName}';"
        );

        QueryBuilder::raw(
            "DELETE FROM staffs s
            USING users u
            WHERE s.id = u.id
            AND email = '{$this->phmName}';"
        );

        QueryBuilder::raw(
            "DELETE FROM doctors d
            USING users u
            WHERE d.id = u.id
            AND email = '{$this->doctorName}';"
        );

        QueryBuilder::raw(
            "DELETE FROM public_health_midwives p
            USING users u
            WHERE p.id = u.id
            AND email = '{$this->phmName}';"
        );

        QueryBuilder::raw(
            "DELETE FROM users
            WHERE email IN ('{$this->doctorName}', '{$this->phmName}');"
        );
    }
}