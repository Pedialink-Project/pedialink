ALTER TABLE admin_permissions DROP COLUMN IF EXISTS active;

ALTER TABLE admin_type_permissions DROP COLUMN IF EXISTS status;

INSERT INTO permissions (type)
VALUES
('user management'), /* 1 */
('admin management'), /* 2 */
('child profile'), /* 3 */
('maternal profile'), /* 4 */
('vaccination'), /* 5 */
('appointment'); /* 6 */

INSERT INTO admin_type_permissions (admin_type_id, permission_id)
VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(2, 3),
(2, 4),
(2, 5),
(2, 6),
(3, 1),
(3, 2);

INSERT INTO admin_permissions (admin_id, permission_id, granted_by)
VALUES
((SELECT id FROM users WHERE email = 'manimehalan400@gmail.com'), 1, NULL),
((SELECT id FROM users WHERE email = 'manimehalan400@gmail.com'), 2, NULL),
((SELECT id FROM users WHERE email = 'manimehalan400@gmail.com'), 3, NULL),
((SELECT id FROM users WHERE email = 'manimehalan400@gmail.com'), 4, NULL),
((SELECT id FROM users WHERE email = 'manimehalan400@gmail.com'), 5, NULL),
((SELECT id FROM users WHERE email = 'manimehalan400@gmail.com'), 6, NULL);