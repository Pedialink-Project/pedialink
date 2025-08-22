CREATE TABLE IF NOT EXISTS permissions (
    id SERIAL PRIMARY KEY,
    type TEXT 
);

CREATE TABLE IF NOT EXISTS admin_type_permissions (
    admin_type_id INT NOT NULL REFERENCES admin_types(id),
    permission_id INT NOT NULL REFERENCES permissions(id),
    status BOOLEAN DEFAULT false,
    PRIMARY KEY (admin_type_id, permission_id)
);

CREATE TABLE IF NOT EXISTS admin_permissions (
    admin_id INT NOT NULL REFERENCES admins(id),
    permission_id INT NOT NULL REFERENCES permissions(id),
    active BOOLEAN DEFAULT false,
    granted_by INT REFERENCES admins(id),
    granted_at TIMESTAMP WITH TIME ZONE DEFAULT now(),
    PRIMARY KEY (admin_id, permission_id)
);