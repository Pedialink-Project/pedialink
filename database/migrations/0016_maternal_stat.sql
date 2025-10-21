DROP TABLE IF EXISTS maternal_stats CASCADE;

CREATE TABLE IF NOT EXISTS maternal_stats (
    id SERIAL PRIMARY KEY,
    maternal_id INT REFERENCES maternal(id) ON DELETE CASCADE,
    visit_date DATE NOT NULL,
    trimester maternal_stage_enum NOT NULL,                     
    weight DECIMAL(5,2),                                        
    height DECIMAL(5,2),                                     
    bmi DECIMAL(5,2),                                           
    blood_pressure VARCHAR(10),                                
    blood_sugar DECIMAL(5,2),                                  
    fundal_height DECIMAL(5,2),                                 
    notes TEXT,                                                 
    created_at TIMESTAMP DEFAULT NOW()                        
);

INSERT INTO maternal_stats (maternal_id, visit_date, trimester, weight, height, bmi, blood_pressure, blood_sugar, fundal_height, notes) VALUES
(1, '2024-01-15', 'first_trimester', 60.5, 165.0, 22.2, '120/80', 90.5, 12.0, '[{"note": "Initial visit"}]'),
(1, '2024-03-15', 'second_trimester', 65.0, 165.0, 23.9, '118/78', 95.0, 20.0, '[{"note": "Routine follow-up"}]'),
(1, '2024-06-15', 'third_trimester', 70.0, 165.0, 25.7, '115/75', 100.0, 30.0, '[{"note": "Pre-delivery assessment"}]');
