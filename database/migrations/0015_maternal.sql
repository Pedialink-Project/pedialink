

DROP TYPE IF EXISTS maternal_health_status_enum CASCADE;
CREATE TYPE maternal_health_status_enum AS ENUM ('good', 'critical', 'needs_attention');

DROP TYPE IF EXISTS maternal_type_enum CASCADE;
CREATE TYPE maternal_type_enum AS ENUM ('antenatal', 'postnatal');

DROP TYPE IF EXISTS maternal_stage_enum CASCADE;
CREATE TYPE maternal_stage_enum AS ENUM ('first_trimester', 'second_trimester', 'third_trimester');



ALTER TABLE parents
  ADD COLUMN age INT;


ALTER TABLE maternal
  ADD COLUMN type maternal_type_enum DEFAULT 'antenatal',
  ADD COLUMN stage maternal_stage_enum DEFAULT 'first_trimester',
  ADD COLUMN pregnancy_date DATE,
  ADD COLUMN health_status maternal_health_status_enum DEFAULT 'good',
  ADD COLUMN additional_info JSON;


UPDATE parents
SET 
    age = 31,
    type = 'mother'
WHERE id = (
    SELECT id
    FROM users
    WHERE email = 'keeththi2003@gmail.com'
    LIMIT 1
);


UPDATE maternal
SET 
    pregnancy_date = '2024-12-01',
    type = 'antenatal',
    stage = 'second_trimester',
    health_status = 'good',
    additional_info = '{"last_menstrual_period": "2024-11-15", "expected_delivery_date": "2025-08-22"}'
WHERE id = 1;
