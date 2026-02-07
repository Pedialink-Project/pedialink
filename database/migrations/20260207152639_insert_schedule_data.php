<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;;

/**
 * Migration: 20260207152639_insert_schedule_data
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20260207152639_insert_schedule_data implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
            "-- ===== Vaccines =====
            -- (Do NOT include id if your tables have triggers that generate ids differently;
            --  these explicit ids assume standard serial sequences named vaccines_id_seq, etc.)
            INSERT INTO vaccines (code, name) VALUES
            ('BCG', 'Bacillus Calmette-Guérin (BCG)'),
            ('HepB', 'Hepatitis B (birth dose)'),
            ('OPV', 'Oral Polio Vaccine (OPV)'),
            ('DTP', 'DTP (Diphtheria - Tetanus - Pertussis)'),
            ('Hib', 'Haemophilus influenzae type b (Hib)'),
            ('PCV', 'Pneumococcal Conjugate Vaccine (PCV)'),
            ('ROT', 'Rotavirus vaccine'),
            ('MMR', 'Measles-Mumps-Rubella (MMR)'),
            ('JE', 'Japanese Encephalitis (JE)');"
        );

        QueryBuilder::raw(
            " -- ===== Schedules =====
            INSERT INTO schedules (name, version, effective_from, active) VALUES
            ('MOH National Schedule', '1', '2020-01-01', TRUE),
            ('MOH Older Schedule',  '0', '2010-01-01', FALSE);"
        );

       QueryBuilder::raw(<<<'SQL'
INSERT INTO schedule_vaccines
  (vaccine_id, schedule_id, dose_number, min_age_days, due_age_days, min_age_gap_days, additional_information)
VALUES
  -- birth doses (day 0)
  (
    (SELECT id FROM vaccines WHERE code = 'BCG'  LIMIT 1),
    (SELECT id FROM schedules WHERE active = TRUE LIMIT 1),
    1, 0, 0, 0, 'BCG at birth'
  ),
  (
    (SELECT id FROM vaccines WHERE code = 'HepB' LIMIT 1),
    (SELECT id FROM schedules WHERE active = TRUE LIMIT 1),
    1, 0, 0, 0, 'HepB birth dose'
  ),

  -- 2-month primary series (~60 days)
  (
    (SELECT id FROM vaccines WHERE code = 'DTP' LIMIT 1),
    (SELECT id FROM schedules WHERE active = TRUE LIMIT 1),
    1, 60, 60, 28, 'DTP dose 1 at 2 months'
  ),
  (
    (SELECT id FROM vaccines WHERE code = 'OPV' LIMIT 1),
    (SELECT id FROM schedules WHERE active = TRUE LIMIT 1),
    1, 60, 60, 28, 'OPV dose 1 at 2 months'
  ),
  (
    (SELECT id FROM vaccines WHERE code = 'Hib' LIMIT 1),
    (SELECT id FROM schedules WHERE active = TRUE LIMIT 1),
    1, 60, 60, 28, 'Hib dose 1 at 2 months'
  ),
  (
    (SELECT id FROM vaccines WHERE code = 'PCV' LIMIT 1),
    (SELECT id FROM schedules WHERE active = TRUE LIMIT 1),
    1, 60, 60, 28, 'PCV dose 1 at 2 months'
  ),
  (
    (SELECT id FROM vaccines WHERE code = 'ROT' LIMIT 1),
    (SELECT id FROM schedules WHERE active = TRUE LIMIT 1),
    1, 60, 60, 28, 'Rotavirus dose 1 at 2 months'
  ),

  -- 4-month (second doses ~120 days)
  (
    (SELECT id FROM vaccines WHERE code = 'DTP' LIMIT 1),
    (SELECT id FROM schedules WHERE active = TRUE LIMIT 1),
    2, 120, 120, 28, 'DTP dose 2 at 4 months'
  ),
  (
    (SELECT id FROM vaccines WHERE code = 'OPV' LIMIT 1),
    (SELECT id FROM schedules WHERE active = TRUE LIMIT 1),
    2, 120, 120, 28, 'OPV dose 2 at 4 months'
  ),
  (
    (SELECT id FROM vaccines WHERE code = 'Hib' LIMIT 1),
    (SELECT id FROM schedules WHERE active = TRUE LIMIT 1),
    2, 120, 120, 28, 'Hib dose 2 at 4 months'
  ),
  (
    (SELECT id FROM vaccines WHERE code = 'PCV' LIMIT 1),
    (SELECT id FROM schedules WHERE active = TRUE LIMIT 1),
    2, 120, 120, 28, 'PCV dose 2 at 4 months'
  ),
  (
    (SELECT id FROM vaccines WHERE code = 'ROT' LIMIT 1),
    (SELECT id FROM schedules WHERE active = TRUE LIMIT 1),
    2, 120, 120, 28, 'Rotavirus dose 2 at 4 months'
  ),

  -- 6-month (third doses ~180 days)
  (
    (SELECT id FROM vaccines WHERE code = 'DTP' LIMIT 1),
    (SELECT id FROM schedules WHERE active = TRUE LIMIT 1),
    3, 180, 180, 28, 'DTP dose 3 at 6 months'
  ),
  (
    (SELECT id FROM vaccines WHERE code = 'OPV' LIMIT 1),
    (SELECT id FROM schedules WHERE active = TRUE LIMIT 1),
    3, 180, 180, 28, 'OPV dose 3 at 6 months'
  ),
  (
    (SELECT id FROM vaccines WHERE code = 'PCV' LIMIT 1),
    (SELECT id FROM schedules WHERE active = TRUE LIMIT 1),
    3, 180, 180, 28, 'PCV dose 3 at 6 months'
  ),

  -- 9 / 18 / 12 month boosters
  (
    (SELECT id FROM vaccines WHERE code = 'MMR' LIMIT 1),
    (SELECT id FROM schedules WHERE active = TRUE LIMIT 1),
    1, 270, 270, 0, 'MMR dose 1 at 9 months'
  ),
  (
    (SELECT id FROM vaccines WHERE code = 'MMR' LIMIT 1),
    (SELECT id FROM schedules WHERE active = TRUE LIMIT 1),
    2, 548, 548, 180, 'MMR dose 2 at 18 months (booster)'
  ),
  (
    (SELECT id FROM vaccines WHERE code = 'JE' LIMIT 1),
    (SELECT id FROM schedules WHERE active = TRUE LIMIT 1),
    1, 365, 365, 0, 'JE (if in programme) at 12 months'
  );
SQL
);
    }

    public function down(): void
    {
        // skip: no need to revert data (table revert is enough)
    }
}