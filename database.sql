-- ----------------------------------------------------------
-- Phase 2: Complete Resort Database Architecture
-- ----------------------------------------------------------

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- ----------------------------------------------------------
-- 1. admins: admin users who can log into the back-office
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admins` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `name` VARCHAR(100) NULL,
    `role` ENUM('super','staff') DEFAULT 'super',
    `status` ENUM('active','inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 2. room_categories: defines each type of room
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `room_categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `total_rooms` INT UNSIGNED NOT NULL DEFAULT 1,
    `ac` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1 = AC, 0 = Non-AC',
    `max_occupancy` INT UNSIGNED NOT NULL DEFAULT 2,
    `adult_capacity` INT UNSIGNED NOT NULL DEFAULT 2,
    `kids_capacity` INT UNSIGNED NOT NULL DEFAULT 0,
    `extra_person_charge` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `status` ENUM('active','inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 3. room_rates: base rate + optional seasonal rates
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `room_rates` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT NOT NULL,
    `rate` DECIMAL(10,2) NOT NULL,
    `season_start` DATE NULL COMMENT 'NULL means base rate all year',
    `season_end` DATE NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_room_rates_category` FOREIGN KEY (`category_id`) REFERENCES `room_categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 4. room_images: multiple images per room category
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `room_images` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT NOT NULL,
    `image_path` VARCHAR(255) NOT NULL,
    `alt_text` VARCHAR(150) NULL,
    `sort_order` INT UNSIGNED DEFAULT 0,
    `status` ENUM('active','inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_room_images_category` FOREIGN KEY (`category_id`) REFERENCES `room_categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 5. amenities: list of possible amenities (e.g., Wifi, Pool)
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `amenities` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    `icon_class` VARCHAR(100) NULL,
    `status` ENUM('active','inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 6. room_category_amenities: many-to-many linking table
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `room_category_amenities` (
    `category_id` INT NOT NULL,
    `amenity_id` INT NOT NULL,
    PRIMARY KEY (`category_id`, `amenity_id`),
    CONSTRAINT `fk_rca_category` FOREIGN KEY (`category_id`) REFERENCES `room_categories`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_rca_amenity` FOREIGN KEY (`amenity_id`) REFERENCES `amenities`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 7. restaurant_items: menu items
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `restaurant_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `description` TEXT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `category` VARCHAR(100) NULL,
    `status` ENUM('active','inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 8. restaurant_timings: opening hours per day
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `restaurant_timings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `day_of_week` ENUM('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
    `open_time` TIME NOT NULL,
    `close_time` TIME NOT NULL,
    `status` ENUM('active','inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_day` (`day_of_week`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 9. facilities: general resort facilities (e.g., Spa, Gym)
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `facilities` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    `icon_class` VARCHAR(100) NULL,
    `status` ENUM('active','inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 10. gallery: images shown on the public gallery page
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `gallery` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `image_path` VARCHAR(255) NOT NULL,
    `caption` VARCHAR(200) NULL,
    `category` VARCHAR(100) NULL,
    `sort_order` INT UNSIGNED DEFAULT 0,
    `status` ENUM('active','inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 11. banquet_details: banquet halls / event spaces
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `banquet_details` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `capacity` INT UNSIGNED NOT NULL,
    `description` TEXT NULL,
    `price` DECIMAL(12,2) NOT NULL,
    `status` ENUM('active','inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 12. booking_inquiries: reservations made by guests
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `booking_inquiries` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `guest_name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(30) NULL,
    `check_in` DATE NOT NULL,
    `check_out` DATE NOT NULL,
    `category_id` INT NOT NULL,
    `adults` INT UNSIGNED NOT NULL,
    `kids` INT UNSIGNED NOT NULL,
    `extra_persons` INT UNSIGNED DEFAULT 0,
    `message` TEXT NULL,
    `status` ENUM('new','confirmed','cancelled','completed') DEFAULT 'new',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_booking_category` FOREIGN KEY (`category_id`) REFERENCES `room_categories`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 13. contact_inquiries: generic contact-form submissions
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `contact_inquiries` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `subject` VARCHAR(200) NOT NULL,
    `message` TEXT NOT NULL,
    `status` ENUM('new','read','closed') DEFAULT 'new',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 14. site_settings: key-value store for generic site config
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `site_settings` (
    `setting_key` VARCHAR(100) PRIMARY KEY,
    `setting_value` TEXT NOT NULL,
    `description` VARCHAR(255) NULL,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 15. seo_settings: SEO related key-value pairs
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `seo_settings` (
    `page_url` VARCHAR(100) PRIMARY KEY,
    `title` VARCHAR(200) NOT NULL,
    `description` TEXT NULL,
    `keywords` TEXT NULL,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 16. policies: various policy pages (privacy, terms, etc.)
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `policies` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(150) NOT NULL,
    `type` ENUM('privacy','terms','cancellation','other') NOT NULL,
    `content` TEXT NOT NULL,
    `status` ENUM('active','inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 17. testimonials: guest reviews
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `testimonials` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `guest_name` VARCHAR(150) NOT NULL,
    `rating` TINYINT UNSIGNED NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
    `comment` TEXT NULL,
    `photo_path` VARCHAR(255) NULL,
    `status` ENUM('active','inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 18. social_links: links to social media profiles
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `social_links` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `platform` VARCHAR(50) NOT NULL,
    `url` VARCHAR(255) NOT NULL,
    `icon_class` VARCHAR(100) NULL,
    `status` ENUM('active','inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- Indexes
-- ----------------------------------------------------------
CREATE INDEX `idx_booking_category` ON `booking_inquiries`(`category_id`);
CREATE INDEX `idx_room_rate_category` ON `room_rates`(`category_id`);
CREATE INDEX `idx_room_image_category` ON `room_images`(`category_id`);

COMMIT;
