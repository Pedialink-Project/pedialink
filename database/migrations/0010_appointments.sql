CREATE TYPE appointment_status AS ENUM ('pending','confirmed','cancelled','reschedule_requested','rescheduled');

CREATE TYPE appointment_requested_by AS ENUM ('staff','parent');

CREATE TYPE appointment_cancelled_by AS ENUM ('parent', 'staff');

CREATE TABLE IF NOT EXISTS appointments (
    id SERIAL PRIMARY KEY,
    patient_id INT REFERENCES patients (id) ON DELETE SET NULL,
    staff_id INT REFERENCES staffs (id) ON DELETE SET NULL,
    requested_by appointment_requested_by NOT NULL,
    datetime TIMESTAMP NOT NULL,
    location VARCHAR(255) DEFAULT 'Not Allocated',
    status appointment_status DEFAULT 'pending',
    purpose VARCHAR(255),
    notes JSON,
    cancelled_by appointment_cancelled_by
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
