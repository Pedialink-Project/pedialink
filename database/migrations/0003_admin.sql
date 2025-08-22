CREATE TYPE admin_type as ENUM ('super', 'data', 'user');

CREATE TABLE IF NOT EXISTS admin_types (
    id SERIAL PRIMARY KEY,
    type admin_type NOT NULL
);

INSERT INTO admin_types (type) VALUES
('super'),
('data'),
('user');

CREATE TABLE IF NOT EXISTS admins (
    id INT PRIMARY KEY REFERENCES users(id) ON DELETE CASCADE,
    admin_type_id INT REFERENCES admin_types(id)
);