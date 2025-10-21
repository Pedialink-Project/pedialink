UPDATE children
SET
    gs_division = 'Borella',
    notes = '[{"note": "All vaccinations up to date"}]',
    vaccination_status = 'completed'
WHERE name = 'Alex Hales';

INSERT INTO child_stats (
    child_id,
    visit_date,
    age_recorded_at,
    height,
    weight,
    head_circum,
    risk_flags,
    notes
) VALUES
(
    (SELECT id FROM children WHERE name = 'Alex Hales'),
    '2025-01-05',
    '6 months',
    68.4,
    7.2,
    42.1,
    'normal',
    '{"notes": "Healthy growth", "recorded_by": "PHM_001"}'
),
(
    (SELECT id FROM children WHERE name = 'Alex Hales'),
    '2025-03-10',
    '8 months',
    72.0,
    8.1,
    43.3,
    'normal',
    '{"notes": "Normal progress", "recorded_by": "PHM_001"}'
);
