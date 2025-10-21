CREATE TABLE IF NOT EXISTS child_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    child_id INT REFERENCES children (id) ON DELETE CASCADE,
    visit_date DATE NOT NULL,
    age_recorded_at VARCHAR(20),        
    height DECIMAL(5,2),                
    weight DECIMAL(5,2),               
    head_circum DECIMAL(5,2),           
    risk_flags VARCHAR(255),            
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
