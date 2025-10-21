CREATE TABLE IF NOT EXISTS child_stats (
    id SERIAL PRIMARY KEY,
    child_id INT REFERENCES children (id) ON DELETE CASCADE,
    visit_date DATE NOT NULL,
    age_recorded_at VARCHAR(20),        
    height DECIMAL(5,2),                
    weight DECIMAL(5,2),               
    head_circum DECIMAL(5,2),           
    risk_flags VARCHAR(255),   
    notes JSON,       
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TYPE vaccination_status_enum AS ENUM ('pending', 'completed', 'overdue');

ALTER TABLE children
    ADD COLUMN IF NOT EXISTS gs_division VARCHAR(50) NOT NULL DEFAULT '',
    ADD COLUMN IF NOT EXISTS notes JSON,
    ADD COLUMN IF NOT EXISTS vaccination_status vaccination_status_enum DEFAULT 'pending';


