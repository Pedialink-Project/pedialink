<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20251222073807_insert_data_for_events_registration_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20251222073807_insert_data_for_events_registration_table implements \Library\Framework\Database\Migration
{

    private string $userEmail = 'keeththi2003@gmail.com';
    public function up(): void
    {
        QueryBuilder::raw(
            "INSERT INTO events_registration (event_id, user_id,name, email,phone)
            VALUES 
            ((SELECT id FROM events WHERE title = 'Health Awareness Campaign' LIMIT 1),(SELECT id FROM users WHERE email = '{$this->userEmail}' LIMIT 1),'Keeththigan','keeththi2003@gmail.com','+9434567890')
            ;"
        );
    }

    public function down(): void
    {
        // TODO: revert changes made in up()
    }
}