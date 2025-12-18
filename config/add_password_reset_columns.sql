-- Add password reset columns to users table
-- Run this SQL in phpMyAdmin or your database management tool

ALTER TABLE `users`
ADD COLUMN `reset_token` VARCHAR(255) NULL AFTER `profile_picture`,
ADD COLUMN `reset_token_expiry` DATETIME NULL AFTER `reset_token`;

-- Optional: Add index for better performance on token lookups
ALTER TABLE `users` ADD INDEX `idx_reset_token` (`reset_token`);