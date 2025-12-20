<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;
;

/**
 * Migration: 20251220061914_insert_data_for_children_records_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20251220061914_insert_data_for_children_records_table implements \Library\Framework\Database\Migration
{

    private string $staffEmail1 = "nirmal@gmail.com";
    private string $staffEmail2 = "sarah@gmail.com";
    private string $childName1 = "Sara Johnson";
    private string $childName2 = "Liam Smith";
    public function up(): void
    {
        QueryBuilder::raw(
            "INSERT INTO child_records
                  (child_id, staff_id, visit_date, age_recorded_at, height, weight, bmi, head_circumference, notes)
                  VALUES
                  ((SELECT id FROM users WHERE name = '{$this->childName1}' LIMIT 1), (SELECT s.id AS staff_id
                  FROM users u
                  JOIN staffs s ON s.user_id = u.id
                  WHERE u.email = '{$this->staffEmail1}';
                  ), '2025-01-10', 24, 85.5, 12.3, 16.8, 47.2, 'Normal growth for age'),
                  ((SELECT id FROM users WHERE name = '{$this->childName2}' LIMIT 1), (SELECT s.id AS staff_id
                  FROM users u
                  WHERE u.email = '{$this->staffEmail2}';
                  ), '2025-01-15', 36, 95.0, 14.8, 16.4, 49.0, 'Slight underweight, advised balanced diet'),
                  ((SELECT id FROM users WHERE name = '{$this->childName2}' LIMIT 1), (SELECT s.id AS staff_id
                  FROM users u
                  JOIN staffs s ON s.user_id = u.id
                  WHERE u.email = '{$this->staffEmail2}';
                  ), '2025-01-20', 18, 78.2, 10.5, 17.1, 45.5, 'Vaccination visit, no issues'),
                  ((SELECT id FROM users WHERE name = '{$this->childName1}' LIMIT 1), (SELECT s.id AS staff_id
                  FROM users u
                  JOIN staffs s ON s.user_id = u.id
                  WHERE u.email = '{$this->staffEmail1}';
                  ), '2025-01-25', 48, 102.4, 17.9, 17.0, 50.8, 'Healthy child, follow-up in 6 months');"

        );
    }

    public function down(): void
    {
        
    }
}