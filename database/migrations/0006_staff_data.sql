INSERT INTO users (name, email, password_hash, role)
VALUES
('Nirmal', 'nirmal@gmail.com', '$2y$10$..NMr8N3Q2dbPEmN3eRnV.yGESO0WfWtJX2o4zPSvffC0.vbPF8iu', 'phm'),
('Sarah', 'sarah@gmail.com', '$2y$10$..NMr8N3Q2dbPEmN3eRnV.yGESO0WfWtJX2o4zPSvffC0.vbPF8iu', 'doctor');

INSERT INTO public_health_midwives (id, area_id)
VALUES ((SELECT id FROM users WHERE email = 'nirmal@gmail.com' LIMIT 1), 1);

INSERT INTO doctors (id)
VALUES ((SELECT id FROM users WHERE email = 'sarah@gmail.com' LIMIT 1));

INSERT INTO staffs (id, nic, license_no)
VALUES
((SELECT id FROM users WHERE email = 'nirmal@gmail.com' LIMIT 1), '198510212346', 'PHM-00543'),
((SELECT id FROM users WHERE email = 'sarah@gmail.com' LIMIT 1), '199070134567', 'REG-DR-2025-0123');
