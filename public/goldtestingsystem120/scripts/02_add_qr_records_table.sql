-- Adding QR records table for better QR code tracking
USE gold_testing_system;

-- QR records table to track QR code generation and usage
CREATE TABLE IF NOT EXISTS qr_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    serial VARCHAR(20) NOT NULL,
    domain VARCHAR(255) NOT NULL,
    qr_path VARCHAR(255) NOT NULL,
    qr_data TEXT NOT NULL,
    scan_count INT DEFAULT 0,
    last_scanned TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY unique_serial_domain (serial, domain),
    FOREIGN KEY (serial) REFERENCES reports(serial_number) ON DELETE CASCADE
);

-- Create indexes for better performance
CREATE INDEX idx_qr_serial ON qr_records(serial);
CREATE INDEX idx_qr_domain ON qr_records(domain);
CREATE INDEX idx_qr_created ON qr_records(created_at);
