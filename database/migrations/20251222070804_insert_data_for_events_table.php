<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;
;

/**
 * Migration: 20251222070804_insert_data_for_events_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20251222070804_insert_data_for_events_table implements \Library\Framework\Database\Migration
{

    private string $adminEmail = 'admin@gmail.com';


    public function up(): void
    {
        QueryBuilder::raw(
            "INSERT INTO events (admin_id,title, description, purpose,notes, event_date, event_time, event_location, max_count)
            VALUES 
            ((SELECT id FROM users WHERE email = '{$this->adminEmail}' LIMIT 1),'Health Awareness Campaign','A campaign to raise health awareness in the community.','To educate the public on health and wellness.','Bring your own materials.','2026-01-01','10:00:00','Community Center',50),
            ((SELECT id FROM users WHERE email = '{$this->adminEmail}' LIMIT 1),'Vaccination Workshop','Get your vaccinations done for a healthier community.','Increase vaccination coverage, especially among children and the elderly.','Please bring a valid ID and any previous vaccination records.','2026-02-15','14:00:00','MOH Office',30)
            ;"
        );
    }

    public function down(): void
    {
        // TODO: revert changes made in up()
    }
}