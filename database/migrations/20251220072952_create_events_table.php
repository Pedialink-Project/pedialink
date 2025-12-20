<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20251220072952_create_events_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20251220072952_create_events_table implements \Library\Framework\Database\Migration
{

    public function up(): void
    {
        QueryBuilder::raw("CREATE TYPE event_status as ENUM ('upcoming', 'ongoing', 'completed','cancelled');");

        QueryBuilder::raw(
            "CREATE TABLE IF NOT EXISTS events (
                id SERIAL PRIMARY KEY,
                admin_id INT REFERENCES admins(id) ON DELETE RESTRICT,
                title VARCHAR(255) NOT NULL, 
                description TEXT,
                purpose TEXT,
                notes TEXT,
                event_status event_status DEFAULT 'upcoming',
                event_date DATE NOT NULL,
                event_time TIME NOT NULL,
                event_location VARCHAR(255),
                max_count INT,
                visible BOOLEAN DEFAULT TRUE,
                created_at TIMESTAMP WITH TIME ZONE DEFAULT now(),
                updated_at TIMESTAMP WITH TIME ZONE DEFAULT now()
            );"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw("DROP TABLE IF EXISTS events;");
        QueryBuilder::raw("DROP TYPE IF EXISTS event_status;");
    }
}