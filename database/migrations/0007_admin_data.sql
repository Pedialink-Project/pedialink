INSERT INTO users (name, email, password_hash, role)
VALUES
('Mani', 'manimehalan400@gmail.com', '$2y$10$..NMr8N3Q2dbPEmN3eRnV.yGESO0WfWtJX2o4zPSvffC0.vbPF8iu', 'admin');

INSERT INTO admins (id, admin_type_id)
VALUES
((SELECT id FROM users WHERE email = 'mani@gmail.com'), (SELECT id FROM admin_types WHERE type = 'super'));