INSERT INTO appointments (
    id, patient_id, staff_id, requested_by, appointment_datetime, location, status, purpose, notes
) VALUES
(1,1, ( SELECT id FROM users WHERE email = 'sarah@gmail.com' LIMIT 1), 'parent', '2025-10-20 10:00:00', 'HealthCare Center', 'pending', 'Routine Checkup', '[{"note": "Bring previous reports"}]'),
(2,2, ( SELECT id FROM users WHERE email = 'nirmal@gmail.com' LIMIT 1), 'staff', '2025-10-21 11:30:00', 'HealthCare Center', 'pending', 'Eye Examination', '[{"note": "Avoid wearing contact lenses"}]'),