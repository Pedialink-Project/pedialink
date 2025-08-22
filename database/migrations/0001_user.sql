CREATE TYPE user_role AS ENUM ('parent','doctor','phm','admin');

CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    role user_role NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT now()
);

CREATE TYPE parent_type AS ENUM ('mother','father', 'guardian');

CREATE TABLE IF NOT EXISTS parents (
    id INT PRIMARY KEY REFERENCES users(id) ON DELETE CASCADE,
    type parent_type NOT NULL,
    nic TEXT NOT NULL UNIQUE
);