CREATE TYPE patient_type AS ENUM ('maternal','child');

CREATE TABLE IF NOT EXISTS patients (
    id SERIAL PRIMARY KEY,
    type patient_type NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS maternal (
    id INT PRIMARY KEY REFERENCES patients(id) ON DELETE CASCADE,
    parent_id INT REFERENCES parents (id) ON DELETE SET NULL
);

CREATE TYPE child_status AS ENUM ('good','critical');

CREATE TYPE child_gender AS ENUM ('male','female');

CREATE TABLE IF NOT EXISTS children (
    id INT PRIMARY KEY REFERENCES patients(id) ON DELETE CASCADE,
    parent_id INT REFERENCES parents (id) ON DELETE CASCADE,
    phm_id INT REFERENCES public_health_midwives (id) ON DELETE SET NULL,
    name VARCHAR(50) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender child_gender NOT NULL,
    health_status child_status
);
