-- database.sql
CREATE DATABASE payroll_system;
USE payroll_system;

CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    position VARCHAR(50) NOT NULL,
    rate_per_day DECIMAL(10,2) NOT NULL,
    days_absent INT DEFAULT 0,
    total_work_days INT DEFAULT 0,
    gross_pay DECIMAL(10,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample data
INSERT INTO employees (name, position, rate_per_day, days_absent, total_work_days, gross_pay) VALUES
('John Doe', 'Manager', 1500.00, 2, 22, 30000.00),
('Jane Smith', 'Supervisor', 1200.00, 1, 22, 25200.00),
('Mike Johnson', 'Staff', 900.00, 3, 22, 17100.00);