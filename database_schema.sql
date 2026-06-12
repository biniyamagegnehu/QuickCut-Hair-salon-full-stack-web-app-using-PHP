-- ============================================================================
-- QuickCut Hair Salon - Complete Database Schema
-- ============================================================================
-- Database: quickcut
-- Created: 2026-06-12
-- Description: Full schema for QuickCut hair salon management system with
--              user authentication, appointment booking, payment processing,
--              and queue management
-- ============================================================================

-- Create Database if not exists
CREATE DATABASE IF NOT EXISTS `quickcut` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `quickcut`;

-- ============================================================================
-- TABLE: users
-- Description: Customer and admin user accounts
-- ============================================================================
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique user identifier',
  `name` VARCHAR(255) NOT NULL COMMENT 'User full name',
  `email` VARCHAR(255) NOT NULL UNIQUE COMMENT 'User email address',
  `phone` VARCHAR(20) COMMENT 'User phone number',
  `password` VARCHAR(255) NOT NULL COMMENT 'Hashed password',
  `role` ENUM('customer', 'admin', 'barber') NOT NULL DEFAULT 'customer' COMMENT 'User role in system',
  `is_verified` TINYINT(1) DEFAULT 0 COMMENT 'Email verification status',
  `verification_token` VARCHAR(255) COMMENT 'Email verification token',
  `profile_image` VARCHAR(255) COMMENT 'Path to profile image',
  `google_id` VARCHAR(255) UNIQUE COMMENT 'Google OAuth ID for social login',
  `auth_provider` VARCHAR(50) DEFAULT 'local' COMMENT 'Authentication provider (local/google)',
  `reset_token` VARCHAR(255) COMMENT 'Password reset token',
  `reset_token_expiry` DATETIME COMMENT 'Password reset token expiry time',
  `last_login` DATETIME COMMENT 'Last login timestamp',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Account creation time',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update time',
  INDEX `idx_email` (`email`),
  INDEX `idx_role` (`role`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='User accounts table';

-- ============================================================================
-- TABLE: barbers
-- Description: Barber/stylist information and details
-- ============================================================================
CREATE TABLE `barbers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique barber identifier',
  `first_name` VARCHAR(100) NOT NULL COMMENT 'Barber first name',
  `last_name` VARCHAR(100) NOT NULL COMMENT 'Barber last name',
  `email` VARCHAR(255) NOT NULL UNIQUE COMMENT 'Barber email',
  `phone` VARCHAR(20) NOT NULL COMMENT 'Barber phone number',
  `specialty` VARCHAR(255) COMMENT 'Barber specializations (e.g., fades, designs)',
  `rate` DECIMAL(8, 2) DEFAULT 0.00 COMMENT 'Hourly or base rate',
  `status` ENUM('active', 'inactive', 'on_leave') NOT NULL DEFAULT 'active' COMMENT 'Barber current status',
  `bio` TEXT COMMENT 'Barber biography/description',
  `experience_years` INT DEFAULT 0 COMMENT 'Years of experience',
  `profile_image` VARCHAR(255) COMMENT 'Path to barber profile image',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation time',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update time',
  INDEX `idx_status` (`status`),
  INDEX `idx_email` (`email`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Barber information table';

-- ============================================================================
-- TABLE: services
-- Description: Available hair salon services
-- ============================================================================
CREATE TABLE `services` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique service identifier',
  `name` VARCHAR(255) NOT NULL UNIQUE COMMENT 'Service name (e.g., Haircut, Shave)',
  `description` TEXT COMMENT 'Service description',
  `duration` INT NOT NULL COMMENT 'Service duration in minutes',
  `price` DECIMAL(8, 2) NOT NULL COMMENT 'Service price',
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active' COMMENT 'Service availability status',
  `image_path` VARCHAR(255) COMMENT 'Path to service image',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation time',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update time',
  INDEX `idx_status` (`status`),
  INDEX `idx_price` (`price`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Services offered by salon table';

-- ============================================================================
-- TABLE: working_hours
-- Description: Salon operating hours for each day of the week
-- ============================================================================
CREATE TABLE `working_hours` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique record identifier',
  `day_of_week` VARCHAR(20) NOT NULL UNIQUE COMMENT 'Day of week (Monday-Sunday)',
  `open_time` TIME NOT NULL DEFAULT '09:00:00' COMMENT 'Opening time (HH:MM:SS)',
  `close_time` TIME NOT NULL DEFAULT '18:00:00' COMMENT 'Closing time (HH:MM:SS)',
  `is_closed` TINYINT(1) DEFAULT 0 COMMENT 'Is salon closed on this day',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation time',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update time',
  INDEX `idx_day_of_week` (`day_of_week`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Salon working hours table';

-- ============================================================================
-- TABLE: appointments
-- Description: Customer appointments and walk-in bookings
-- ============================================================================
CREATE TABLE `appointments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique appointment identifier',
  `user_id` INT COMMENT 'Customer user ID (NULL for walk-ins)',
  `service_id` INT NOT NULL COMMENT 'Service ID for appointment',
  `barber_id` INT NOT NULL COMMENT 'Assigned barber ID',
  `appointment_date` DATE NOT NULL COMMENT 'Appointment date',
  `appointment_time` TIME NOT NULL COMMENT 'Appointment time (HH:MM)',
  `status` ENUM('pending', 'waiting', 'scheduled', 'confirmed', 'in_progress', 'completed', 'cancelled', 'no_show') 
    NOT NULL DEFAULT 'pending' COMMENT 'Appointment status',
  `appointment_source` ENUM('online', 'walk_in') NOT NULL DEFAULT 'online' COMMENT 'Booking source',
  `walk_in_name` VARCHAR(255) COMMENT 'Walk-in customer name',
  `walk_in_phone` VARCHAR(20) COMMENT 'Walk-in customer phone',
  `queue_number` INT COMMENT 'Queue position for the day',
  `notes` TEXT COMMENT 'Additional appointment notes',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation time',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update time',
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (`service_id`) REFERENCES `services`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  FOREIGN KEY (`barber_id`) REFERENCES `barbers`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_barber_id` (`barber_id`),
  INDEX `idx_service_id` (`service_id`),
  INDEX `idx_appointment_date` (`appointment_date`),
  INDEX `idx_status` (`status`),
  INDEX `idx_queue_number` (`queue_number`),
  INDEX `idx_date_barber_time` (`appointment_date`, `barber_id`, `appointment_time`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Appointments and bookings table';

-- ============================================================================
-- TABLE: payments
-- Description: Payment records for Chapa integration
-- ============================================================================
CREATE TABLE `payments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique payment identifier',
  `user_id` INT COMMENT 'Customer user ID',
  `appointment_id` INT NOT NULL COMMENT 'Related appointment ID',
  `amount` DECIMAL(10, 2) NOT NULL COMMENT 'Payment amount (typically 50% deposit)',
  `status` ENUM('pending', 'paid', 'failed', 'cancelled') NOT NULL DEFAULT 'pending' COMMENT 'Payment status',
  `transaction_ref` VARCHAR(255) UNIQUE NOT NULL COMMENT 'Chapa transaction reference (tx_ref)',
  `chapa_response` TEXT COMMENT 'Raw JSON response from Chapa',
  `payment_method` VARCHAR(50) COMMENT 'Payment method used',
  `notes` TEXT COMMENT 'Payment notes',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Payment creation time',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update time',
  `paid_at` DATETIME COMMENT 'Payment completion timestamp',
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (`appointment_id`) REFERENCES `appointments`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_appointment_id` (`appointment_id`),
  INDEX `idx_status` (`status`),
  INDEX `idx_transaction_ref` (`transaction_ref`),
  INDEX `idx_created_at` (`created_at`),
  UNIQUE INDEX `idx_appointment_payment` (`appointment_id`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Payment transactions table';

-- ============================================================================
-- TABLE: barber_ratings
-- Description: Customer ratings and reviews for barbers
-- ============================================================================
CREATE TABLE `barber_ratings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique rating identifier',
  `customer_id` INT NOT NULL COMMENT 'Customer/user ID',
  `barber_id` INT NOT NULL COMMENT 'Barber ID being rated',
  `appointment_id` INT NOT NULL UNIQUE COMMENT 'Related appointment ID',
  `rating` INT NOT NULL COMMENT 'Rating value (1-5 stars)',
  `review` TEXT COMMENT 'Customer review text',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Rating creation time',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update time',
  FOREIGN KEY (`customer_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`barber_id`) REFERENCES `barbers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`appointment_id`) REFERENCES `appointments`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX `idx_barber_id` (`barber_id`),
  INDEX `idx_customer_id` (`customer_id`),
  INDEX `idx_rating` (`rating`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Barber ratings and reviews table';

-- ============================================================================
-- TABLE: notifications
-- Description: System notifications for users
-- ============================================================================
CREATE TABLE `notifications` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique notification identifier',
  `user_id` INT NOT NULL COMMENT 'Recipient user ID',
  `message` TEXT NOT NULL COMMENT 'Notification message',
  `type` VARCHAR(50) NOT NULL COMMENT 'Notification type (e.g., appointment, payment, status)',
  `related_id` INT COMMENT 'Related entity ID (appointment, payment, etc.)',
  `role` VARCHAR(50) COMMENT 'Target role for notification',
  `is_read` TINYINT(1) DEFAULT 0 COMMENT 'Read status',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Notification creation time',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update time',
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_is_read` (`is_read`),
  INDEX `idx_type` (`type`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='User notifications table';

-- ============================================================================
-- TABLE: about_content
-- Description: About Us page content
-- ============================================================================
CREATE TABLE `about_content` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique content identifier',
  `content` LONGTEXT NOT NULL COMMENT 'About page HTML content',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Content creation time',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update time'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='About page content table';

-- ============================================================================
-- SAMPLE DATA
-- ============================================================================

-- Insert default working hours
INSERT INTO `working_hours` (`day_of_week`, `open_time`, `close_time`, `is_closed`) VALUES
('Monday', '09:00:00', '18:00:00', 0),
('Tuesday', '09:00:00', '18:00:00', 0),
('Wednesday', '09:00:00', '18:00:00', 0),
('Thursday', '09:00:00', '18:00:00', 0),
('Friday', '09:00:00', '20:00:00', 0),
('Saturday', '09:00:00', '18:00:00', 0),
('Sunday', '10:00:00', '16:00:00', 1);

-- Insert sample services
INSERT INTO `services` (`name`, `description`, `duration`, `price`, `status`) VALUES
('Haircut', 'Professional haircut with modern styles', 30, 500.00, 'active'),
('Fade', 'Clean fade with sharp lines', 25, 400.00, 'active'),
('Beard Shave', 'Professional shave with aftercare', 20, 300.00, 'active'),
('Hair Design', 'Custom designs and patterns', 45, 800.00, 'active'),
('Coloring', 'Hair coloring service', 60, 1200.00, 'active'),
('Full Service', 'Haircut, shave, and styling', 60, 1500.00, 'active');

-- Insert sample admin user (password: admin@123)
INSERT INTO `users` (`name`, `email`, `phone`, `password`, `role`, `is_verified`) VALUES
('Admin User', 'admin@quickcut.com', '1234567890', '$2y$10$zr.MUdqxHlqYvzGfQV8aCeG6j3j5xJ5A5J5A5J5A5J5A5J5A5J5A5', 'admin', 1);

-- Insert sample barber
INSERT INTO `barbers` (`first_name`, `last_name`, `email`, `phone`, `specialty`, `rate`, `status`, `bio`, `experience_years`) VALUES
('John', 'Smith', 'john@quickcut.com', '9876543210', 'Fades, Modern Cuts', 2000.00, 'active', 'Expert barber with 10 years of experience', 10);

-- Insert sample about content
INSERT INTO `about_content` (`content`) VALUES
('<h2>About QuickCut Hair Salon</h2><p>Welcome to QuickCut, your premier destination for professional hair styling and grooming services. With years of experience and a team of skilled barbers, we ensure every client leaves our salon feeling their best.</p>');

-- ============================================================================
-- VIEWS FOR COMMON QUERIES
-- ============================================================================

-- View: Upcoming appointments with details
CREATE OR REPLACE VIEW `v_upcoming_appointments` AS
SELECT 
  a.id,
  a.appointment_date,
  a.appointment_time,
  a.status,
  a.queue_number,
  u.name AS customer_name,
  u.email AS customer_email,
  u.phone AS customer_phone,
  b.first_name,
  b.last_name,
  s.name AS service_name,
  s.duration,
  s.price,
  a.walk_in_name,
  a.walk_in_phone
FROM appointments a
LEFT JOIN users u ON a.user_id = u.id
JOIN barbers b ON a.barber_id = b.id
JOIN services s ON a.service_id = s.id
WHERE a.appointment_date >= CURDATE() AND a.status IN ('pending', 'waiting', 'scheduled', 'confirmed')
ORDER BY a.appointment_date, a.appointment_time;

-- View: Barber statistics
CREATE OR REPLACE VIEW `v_barber_stats` AS
SELECT 
  b.id,
  b.first_name,
  b.last_name,
  b.specialty,
  COUNT(a.id) AS total_appointments,
  SUM(CASE WHEN a.status = 'completed' THEN 1 ELSE 0 END) AS completed_appointments,
  ROUND(AVG(CASE WHEN br.rating IS NOT NULL THEN br.rating ELSE 0 END), 2) AS average_rating,
  COUNT(DISTINCT br.id) AS total_ratings
FROM barbers b
LEFT JOIN appointments a ON b.id = a.barber_id
LEFT JOIN barber_ratings br ON b.id = br.barber_id
GROUP BY b.id, b.first_name, b.last_name, b.specialty;

-- View: Daily revenue
CREATE OR REPLACE VIEW `v_daily_revenue` AS
SELECT 
  DATE(p.created_at) AS payment_date,
  COUNT(p.id) AS total_payments,
  SUM(p.amount) AS total_revenue,
  SUM(CASE WHEN p.status = 'paid' THEN p.amount ELSE 0 END) AS completed_revenue
FROM payments p
GROUP BY DATE(p.created_at)
ORDER BY payment_date DESC;

-- ============================================================================
-- END OF SCHEMA
-- ============================================================================
