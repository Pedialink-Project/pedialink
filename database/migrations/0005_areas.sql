CREATE TABLE IF NOT EXISTS areas (
    id SERIAL PRIMARY KEY,
    code TEXT NOT NULL
);

ALTER TABLE parents
ADD COLUMN address TEXT NOT NULL;

ALTER TABLE parents
ADD COLUMN area_id INT REFERENCES areas(id) ON DELETE CASCADE;

ALTER TABLE public_health_midwives
ADD COLUMN area_id INT REFERENCES areas(id) ON DELETE CASCADE;

INSERT INTO areas (code) VALUES
('Paraduwa'),
('Mawawwa'),
('Kiyaduwa'),
('Galabadahena'),
('Poraba'),
('Iluppalla'),
('Ibulgoda'),
('Maraba'),
('Hulandawa'),
('Peddapitiya');