<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260201142419_insert_data_for_events_table_3
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260201142419_insert_data_for_events_table_3 implements \Library\Framework\Database\Migration
{

private $adminEmail = 'admin@gmail.com';
   public function up(): void
{
    QueryBuilder::raw("
        INSERT INTO events (
            admin_id,
            title,
            description,
            purpose,
            notes,
            event_date,
            start_time,
            end_time,
            event_location,
            max_count,
            is_cancelled
        ) VALUES
        (
            (SELECT id FROM users WHERE email = '{$this->adminEmail}' LIMIT 1),
            'Blood Donation Camp',
            'Donate blood and save lives.',
            'Encourage voluntary blood donation.',
            'Drink plenty of water before donating.',
            '2026-05-10',
            '09:00',
            '12:00',
            'District Hospital',
            100,
            FALSE
        ),
        (
            (SELECT id FROM users WHERE email = '{$this->adminEmail}' LIMIT 1),
            'Nutrition Seminar',
            'Learn about balanced diet and healthy eating.',
            'Promote healthy nutrition habits.',
            'Free consultation available after session.',
            '2026-01-10',
            '11:00',
            '13:00',
            'Town Hall',
            60,
            FALSE
        ),
        (
            (SELECT id FROM users WHERE email = '{$this->adminEmail}' LIMIT 1),
            'Mental Health Workshop',
            'Awareness on stress and mental well-being.',
            'Reduce stigma and improve mental health knowledge.',
            'Confidential counseling sessions available.',
            '2026-04-05',
            '13:30',
            '16:00',
            'Youth Center',
            35,
            FALSE
        ),
        (
            (SELECT id FROM users WHERE email = '{$this->adminEmail}' LIMIT 1),
            'Elder Care Program',
            'Support and care tips for senior citizens.',
            'Educate families on elder care.',
            'Bring family members.',
            '2026-04-20',
            '10:30',
            '12:30',
            'Community Welfare Office',
            25,
            FALSE
        ),
        (
            (SELECT id FROM users WHERE email = '{$this->adminEmail}' LIMIT 1),
            'First Aid Training',
            'Basic first aid and emergency response training.',
            'Equip community with life-saving skills.',
            'Certificate will be provided.',
            '2026-05-01',
            '08:30',
            '15:00',
            'Red Cross Center',
            45,
            FALSE
        );
    ");
}


   public function down(): void
{
    QueryBuilder::raw("
        DELETE FROM events
        WHERE title IN (
            'Blood Donation Camp',
            'Nutrition Seminar',
            'Mental Health Workshop',
            'Elder Care Program',
            'First Aid Training'
        )
        AND admin_id = (
            SELECT id FROM users WHERE email = '{$this->adminEmail}' LIMIT 1
        );
    ");
}

}