<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20251220074702_create_events_registration_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20251220074702_create_events_registration_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {  
        QueryBuilder::raw("CREATE TYPE booking_status as ENUM ('booked', 'cancelled','expired');");

        QueryBuilder::raw(
            "CREATE TABLE IF NOT EXISTS events_registrations (
                id SERIAL PRIMARY KEY,
                event_id INT REFERENCES events(id) ON DELETE CASCADE,
                user_id INT REFERENCES users(id) ON DELETE CASCADE,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                phone VARCHAR(20),
                booking_status booking_status DEFAULT 'booked',
                cancel_reason TEXT,
                cancelled_at TIMESTAMP WITH TIME ZONE,
                registration_date TIMESTAMP WITH TIME ZONE DEFAULT now(),
            );"
        );
    }

    public function down(): void
    {
        // TODO: revert changes made in up()
    }
}