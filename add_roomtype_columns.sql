-- Add missing columns to room_types table
-- This SQL script will add the same columns as the Laravel migration

ALTER TABLE `room_types` 
ADD COLUMN `description` TEXT NULL AFTER `room_name`,
ADD COLUMN `base_price` DECIMAL(10,2) DEFAULT 0 AFTER `description`,
ADD COLUMN `max_occupancy` INT DEFAULT 2 AFTER `base_price`,
ADD COLUMN `amenities` JSON NULL AFTER `max_occupancy`,
ADD COLUMN `is_active` TINYINT(1) DEFAULT 1 AFTER `amenities`;

-- Update existing records to have default values
UPDATE `room_types` 
SET `is_active` = 1 
WHERE `is_active` IS NULL;