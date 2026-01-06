-- Creating database and tables for gold testing system
CREATE DATABASE IF NOT EXISTS gold_testing_system;
USE gold_testing_system;

-- Reports table to store all test reports
CREATE TABLE IF NOT EXISTS reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    serial_number VARCHAR(20) UNIQUE NOT NULL,
    date_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    customer_name VARCHAR(255) NOT NULL,
    sample_description TEXT,
    weight DECIMAL(10,3),
    carat DECIMAL(5,2),
    qr_code_path VARCHAR(255),
    image_path VARCHAR(255),
    
    -- Element concentrations
    gold_au DECIMAL(5,2),
    copper_cu DECIMAL(5,2),
    silver_ag DECIMAL(5,2),
    zinc_zn DECIMAL(5,2),
    cadmium_cd DECIMAL(5,2),
    iridium_ir DECIMAL(5,2),
    tin_sn DECIMAL(5,2),
    nickel_ni DECIMAL(5,2),
    lead_pb DECIMAL(5,2),
    ruthenium_ru DECIMAL(5,2),
    platinum_pt DECIMAL(5,2),
    cobalt_co DECIMAL(5,2),
    palladium_pd DECIMAL(5,2),
    osmium_os DECIMAL(5,2),
    ferrum_fe DECIMAL(5,2),
    others TEXT,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create index for faster serial number lookups
CREATE INDEX idx_serial_number ON reports(serial_number);
CREATE INDEX idx_date_time ON reports(date_time);
