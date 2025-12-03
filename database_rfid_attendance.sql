-- ============================================
-- RFID Attendance Table - Manual SQL Creation
-- ============================================
-- 
-- If you prefer to create the table manually instead of using migrations,
-- run this SQL script in your MySQL database (phpMyAdmin, MySQL Workbench, etc.)
--
-- Database: gym (or your database name)
-- ============================================

CREATE TABLE IF NOT EXISTS `rfid_attendance` (
  `attendance_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id` VARCHAR(50) NOT NULL COMMENT 'Foreign key to gym_members.id',
  `scan_time` DATETIME NOT NULL COMMENT 'Timestamp when the RFID card was scanned',
  PRIMARY KEY (`attendance_id`),
  KEY `idx_member_id` (`member_id`),
  KEY `idx_scan_time` (`scan_time`),
  KEY `idx_member_scan` (`member_id`, `scan_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- OPTIONAL: Add Foreign Key Constraint
-- ============================================
-- Uncomment the following line if you want database-level referential integrity
-- This ensures that only valid member IDs can be inserted
-- ============================================
-- ALTER TABLE `rfid_attendance` 
--   ADD CONSTRAINT `fk_rfid_attendance_member` 
--   FOREIGN KEY (`member_id`) 
--   REFERENCES `gym_members` (`id`) 
--   ON DELETE CASCADE 
--   ON UPDATE CASCADE;

-- ============================================
-- OPTIONAL: Add Additional Columns
-- ============================================
-- If you want to track additional information, you can add columns like:
--
-- ALTER TABLE `rfid_attendance` 
--   ADD COLUMN `location` VARCHAR(100) NULL COMMENT 'Location where scan occurred' AFTER `scan_time`,
--   ADD COLUMN `device_id` VARCHAR(50) NULL COMMENT 'RFID scanner device ID' AFTER `location`;
--
-- ============================================

