CREATE TABLE IF NOT EXISTS working_hours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') NOT NULL,
    open_time TIME,
    close_time TIME,
    is_closed BOOLEAN DEFAULT FALSE,
    UNIQUE(day_of_week)
);

INSERT IGNORE INTO working_hours (day_of_week, open_time, close_time, is_closed) VALUES
('Monday', '09:00:00', '18:00:00', FALSE),
('Tuesday', '09:00:00', '18:00:00', FALSE),
('Wednesday', '09:00:00', '18:00:00', FALSE),
('Thursday', '09:00:00', '18:00:00', FALSE),
('Friday', '09:00:00', '18:00:00', FALSE),
('Saturday', '09:00:00', '18:00:00', FALSE),
('Sunday', '09:00:00', '18:00:00', FALSE);
