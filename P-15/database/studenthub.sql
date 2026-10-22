-- ============================================================================
-- StudentHub Portal Database Schema (ITUE203 Practical 08)
-- Charotar University of Science & Technology (CHARUSAT)
-- 3NF Normalized Architecture with Foreign Keys, Triggers & Stored Procedures
-- ============================================================================

CREATE DATABASE IF NOT EXISTS `studenthub_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `studenthub_db`;

-- Drop existing tables in reverse dependency order
DROP TABLE IF EXISTS `audit_logs`;
DROP TABLE IF EXISTS `registrations`;
DROP TABLE IF EXISTS `events`;
DROP TABLE IF EXISTS `students`;
DROP TABLE IF EXISTS `users`;

-- 1. Users Table (Authentication & RBAC)
CREATE TABLE `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'student', 'faculty') NOT NULL DEFAULT 'student',
    `status` ENUM('active', 'inactive', 'suspended') NOT NULL DEFAULT 'active',
    `remember_token` VARCHAR(64) DEFAULT NULL,
    `last_login` DATETIME DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Students Table (Academic Profile)
CREATE TABLE `students` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED DEFAULT NULL,
    `roll_no` VARCHAR(20) NOT NULL UNIQUE,
    `full_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `mobile` VARCHAR(15) NOT NULL,
    `department` VARCHAR(50) NOT NULL,
    `semester` TINYINT UNSIGNED NOT NULL DEFAULT 1,
    `cgpa` DECIMAL(3, 2) NOT NULL DEFAULT 0.00,
    `status` ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_student_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 3. Events Table (Campus Events & Workshops)
CREATE TABLE `events` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(150) NOT NULL,
    `category` VARCHAR(50) NOT NULL,
    `event_date` DATE NOT NULL,
    `venue` VARCHAR(100) NOT NULL,
    `max_seats` INT UNSIGNED NOT NULL DEFAULT 100,
    `registered_seats` INT UNSIGNED NOT NULL DEFAULT 0,
    `poster_image` VARCHAR(255) DEFAULT 'default_event.jpg',
    `status` ENUM('Upcoming', 'Ongoing', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Upcoming',
    `created_by` INT UNSIGNED DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_event_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 4. Registrations Table (Event Attendance - Junction Table)
CREATE TABLE `registrations` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `student_id` INT UNSIGNED NOT NULL,
    `event_id` INT UNSIGNED NOT NULL,
    `registration_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `status` ENUM('Confirmed', 'Waitlisted', 'Cancelled') NOT NULL DEFAULT 'Confirmed',
    `remarks` VARCHAR(255) DEFAULT NULL,
    UNIQUE KEY `unique_student_event` (`student_id`, `event_id`),
    CONSTRAINT `fk_reg_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_reg_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 5. Audit Logs Table (Activity & Security Tracking)
CREATE TABLE `audit_logs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED DEFAULT NULL,
    `action` VARCHAR(50) NOT NULL,
    `entity` VARCHAR(50) NOT NULL,
    `details` TEXT DEFAULT NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_audit_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Stored Procedure: Register Student for Event (Advanced Extension)
DELIMITER //
CREATE PROCEDURE `RegisterStudentForEvent`(
    IN in_student_id INT,
    IN in_event_id INT,
    OUT out_status VARCHAR(50)
)
BEGIN
    DECLARE v_seats_left INT;
    
    SELECT (`max_seats` - `registered_seats`) INTO v_seats_left 
    FROM `events` WHERE `id` = in_event_id FOR UPDATE;
    
    IF v_seats_left > 0 THEN
        INSERT INTO `registrations` (`student_id`, `event_id`, `status`) 
        VALUES (in_student_id, in_event_id, 'Confirmed');
        
        UPDATE `events` SET `registered_seats` = `registered_seats` + 1 
        WHERE `id` = in_event_id;
        
        SET out_status = 'SUCCESS: Registration Confirmed';
    ELSE
        SET out_status = 'FAILED: Event is fully booked';
    END IF;
END //
DELIMITER ;

-- Seed Initial Realistic Data
INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `status`) VALUES
(1, 'admin', 'admin@charusat.edu.in', '$2y$12$K1b.g907dGvVqB6qW7u84.Yp2v1X1gq2eK9.1b.g907dGvVqB6qW.', 'admin', 'active'),
(2, '24it001', 'aarav.s@charusat.edu.in', '$2y$12$K1b.g907dGvVqB6qW7u84.Yp2v1X1gq2eK9.1b.g907dGvVqB6qW.', 'student', 'active'),
(3, '24cse015', 'diya.j@charusat.edu.in', '$2y$12$K1b.g907dGvVqB6qW7u84.Yp2v1X1gq2eK9.1b.g907dGvVqB6qW.', 'student', 'active');

INSERT INTO `students` (`id`, `user_id`, `roll_no`, `full_name`, `email`, `mobile`, `department`, `semester`, `cgpa`, `status`) VALUES
(1, 2, '24IT001', 'Aarav Sharma', 'aarav.s@charusat.edu.in', '9876543210', 'Information Technology', 3, 9.12, 'Active'),
(2, 3, '24CSE015', 'Diya Joshi', 'diya.j@charusat.edu.in', '9825012345', 'Computer Science', 3, 8.94, 'Active'),
(3, NULL, '24CE089', 'Karan Verma', 'karan.v@charusat.edu.in', '9898098980', 'Computer Engineering', 3, 7.85, 'Active');

INSERT INTO `events` (`id`, `title`, `category`, `event_date`, `venue`, `max_seats`, `registered_seats`, `status`) VALUES
(1, 'National Cloud Summit 2026', 'Technical', '2026-11-05', 'FTE Auditorium B', 150, 45, 'Upcoming'),
(2, 'CHARUSAT Hack-a-Fest 4.0', 'Hackathon', '2026-11-18', 'Central Computing Lab', 80, 78, 'Upcoming'),
(3, 'Spoural 2027 Sports Meet', 'Sports', '2026-12-02', 'University Sports Ground', 400, 110, 'Upcoming');

INSERT INTO `registrations` (`student_id`, `event_id`, `status`) VALUES
(1, 1, 'Confirmed'),
(1, 2, 'Confirmed'),
(2, 2, 'Confirmed');
