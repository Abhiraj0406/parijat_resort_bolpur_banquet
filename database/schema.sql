-- ==============================================================================
-- PARIJAT RESORT BOLPUR & BANQUET - MASTER ENTERPRISE DATABASE SCHEMA
-- Version: 2.0 (Production)
-- Database Engine: InnoDB | Character Set: utf8mb4 | Collation: utf8mb4_unicode_ci
-- Target Systems: MySQL 5.7+, MySQL 8.0+, MariaDB 10.3+
-- ==============================================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+05:30";

-- ------------------------------------------------------------------------------
-- 1. TABLE: admins
-- Purpose: Administrative back-office users with bcrypt authentication
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admins` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `name` VARCHAR(100) NULL,
    `role` ENUM('super', 'staff') DEFAULT 'super',
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `last_login` TIMESTAMP NULL DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_admin_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 2. TABLE: room_categories
-- Purpose: 19 Total Rooms mapped across 4 resort categories
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `room_categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    `facilities` TEXT NULL,
    `total_rooms` INT UNSIGNED NOT NULL DEFAULT 1,
    `ac` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1 = AC Enabled, 0 = Non-AC',
    `has_balcony` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1 = Has Balcony, 0 = No Balcony',
    `is_convertible_ac` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1 = Convertible AC/Non-AC',
    `max_occupancy` INT UNSIGNED NOT NULL DEFAULT 2,
    `adult_capacity` INT UNSIGNED NOT NULL DEFAULT 2,
    `kids_capacity` INT UNSIGNED NOT NULL DEFAULT 0,
    `extra_person_charge` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_cat_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 3. TABLE: room_rates
-- Purpose: Multi-tier tariffs supporting Base, Weekend, and Seasonal Rates
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `room_rates` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT NOT NULL,
    `rate_title` VARCHAR(150) NOT NULL DEFAULT 'Standard Tariff',
    `rate_type` ENUM('base', 'weekend', 'seasonal') DEFAULT 'base',
    `rate_ac` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `rate_non_ac` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `rate` DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Legacy Fallback Rate',
    `season_start` DATE NULL,
    `season_end` DATE NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_rates_category` (`category_id`, `rate_type`),
    CONSTRAINT `fk_room_rates_category` FOREIGN KEY (`category_id`) REFERENCES `room_categories`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 4. TABLE: room_images
-- Purpose: High-definition room category photo galleries
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `room_images` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT NOT NULL,
    `image_path` VARCHAR(255) NOT NULL,
    `alt_text` VARCHAR(150) NULL,
    `sort_order` INT UNSIGNED DEFAULT 0,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_images_cat_order` (`category_id`, `sort_order`),
    CONSTRAINT `fk_room_images_category` FOREIGN KEY (`category_id`) REFERENCES `room_categories`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 5. TABLE: amenities
-- Purpose: Resort features and room amenity master definitions
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `amenities` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    `icon_class` VARCHAR(100) NULL,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 6. TABLE: room_category_amenities
-- Purpose: Category to Amenity Many-to-Many mapping
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `room_category_amenities` (
    `category_id` INT NOT NULL,
    `amenity_id` INT NOT NULL,
    PRIMARY KEY (`category_id`, `amenity_id`),
    CONSTRAINT `fk_rca_category` FOREIGN KEY (`category_id`) REFERENCES `room_categories`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_rca_amenity` FOREIGN KEY (`amenity_id`) REFERENCES `amenities`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 7. TABLE: restaurant_items
-- Purpose: Labanya's Heshel Ghar Multi-Cuisine Menu (Bengali, Chinese, Mughlai)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `restaurant_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `description` TEXT NULL,
    `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `category` VARCHAR(100) NOT NULL DEFAULT 'Bengali' COMMENT 'Bengali, Chinese, Mughlai',
    `is_veg` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 = Vegetarian, 0 = Non-Veg',
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_menu_category` (`category`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 8. TABLE: restaurant_timings
-- Purpose: Operating hours for Breakfast, Thali Lunch, Evening Snacks, and Dinner
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `restaurant_timings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `day_of_week` VARCHAR(50) NOT NULL,
    `open_time` TIME NOT NULL,
    `close_time` TIME NOT NULL,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_timing_day` (`day_of_week`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 9. TABLE: banquet_details
-- Purpose: AC Banquet Hall & Event Lawn specifications and facilities
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `banquet_details` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `capacity` INT UNSIGNED NOT NULL DEFAULT 150,
    `description` TEXT NULL,
    `price` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `facilities` TEXT NULL,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 10. TABLE: banquet_images
-- Purpose: Banquet hall showcase imagery
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `banquet_images` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `banquet_id` INT NOT NULL,
    `image_path` VARCHAR(255) NOT NULL,
    `alt_text` VARCHAR(150) NULL,
    `is_featured` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_banquet_images_hall` FOREIGN KEY (`banquet_id`) REFERENCES `banquet_details`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 11. TABLE: facilities
-- Purpose: General resort facilities (Swimming Pool, Lawn, Free Parking)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `facilities` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    `icon_class` VARCHAR(100) NULL,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 12. TABLE: gallery
-- Purpose: Public photo gallery with category filtering
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `gallery` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `image_path` VARCHAR(255) NOT NULL,
    `caption` VARCHAR(200) NULL,
    `category` VARCHAR(100) DEFAULT 'Resort',
    `sort_order` INT UNSIGNED DEFAULT 0,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_gallery_cat` (`category`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 13. TABLE: booking_inquiries
-- Purpose: Guest room reservation inquiries submitted via website
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `booking_inquiries` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `guest_name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(30) NOT NULL,
    `whatsapp_number` VARCHAR(30) NULL,
    `check_in` DATE NOT NULL,
    `check_out` DATE NOT NULL,
    `category_id` INT NOT NULL,
    `ac_preference` VARCHAR(20) DEFAULT 'AC',
    `number_of_rooms` INT UNSIGNED DEFAULT 1,
    `adults` INT UNSIGNED NOT NULL DEFAULT 2,
    `kids` INT UNSIGNED NOT NULL DEFAULT 0,
    `extra_persons` INT UNSIGNED DEFAULT 0,
    `message` TEXT NULL,
    `status` ENUM('new', 'contacted', 'confirmed', 'cancelled', 'closed') DEFAULT 'new',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_booking_status_date` (`status`, `check_in`),
    INDEX `idx_booking_guest` (`guest_name`, `phone`),
    INDEX `idx_booking_created` (`created_at`),
    CONSTRAINT `fk_booking_category` FOREIGN KEY (`category_id`) REFERENCES `room_categories`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 14. TABLE: reservations
-- Purpose: Synchronized booking confirmation log referenced by receipts
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `reservations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(30) NOT NULL,
    `check_in` DATE NOT NULL,
    `check_out` DATE NOT NULL,
    `room_type` VARCHAR(150) NOT NULL,
    `message` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_res_dates` (`check_in`, `check_out`),
    INDEX `idx_res_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 15. TABLE: contact_inquiries
-- Purpose: Contact form inquiries sent to management
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `contact_inquiries` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(30) NULL,
    `subject` VARCHAR(200) NOT NULL DEFAULT 'Website Contact Form',
    `message` TEXT NOT NULL,
    `status` ENUM('new', 'unread', 'read', 'closed') DEFAULT 'new',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_contact_status` (`status`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 16. TABLE: site_settings
-- Purpose: Key-value store for resort contact details, management, and pool hours
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `site_settings` (
    `setting_key` VARCHAR(100) PRIMARY KEY,
    `setting_value` TEXT NOT NULL,
    `description` VARCHAR(255) NULL,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 17. TABLE: seo_settings
-- Purpose: Dynamic SEO meta titles, descriptions, and OpenGraph tags per route
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `seo_settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `page_name` VARCHAR(100) NOT NULL UNIQUE,
    `meta_title` VARCHAR(255) NOT NULL,
    `meta_description` TEXT NULL,
    `meta_keywords` TEXT NULL,
    `canonical_url` VARCHAR(255) NULL,
    `og_title` VARCHAR(255) NULL,
    `og_description` TEXT NULL,
    `og_image` VARCHAR(255) NULL,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_seo_page` (`page_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 18. TABLE: policies
-- Purpose: 18 Resort rules, dining notice, pool guidelines, and stay terms
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `policies` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(150) NOT NULL,
    `type` ENUM('checkin', 'dining', 'pool', 'cancellation', 'stay', 'other') NOT NULL DEFAULT 'other',
    `content` TEXT NOT NULL,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_policy_type` (`type`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 19. TABLE: social_links
-- Purpose: External social media profile connections
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `social_links` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `platform` VARCHAR(50) NOT NULL UNIQUE,
    `url` VARCHAR(255) NOT NULL,
    `icon_class` VARCHAR(100) NULL,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ==============================================================================
-- OFFICIAL SEED DATA FOR PARIJAT RESORT BOLPUR
-- ==============================================================================

-- 1. Seed: 4 Official Room Categories (Total 19 Rooms)
INSERT INTO `room_categories` (`id`, `name`, `description`, `facilities`, `total_rooms`, `ac`, `has_balcony`, `is_convertible_ac`, `max_occupancy`, `adult_capacity`, `kids_capacity`, `extra_person_charge`, `status`) VALUES
(1, 'Deluxe Cottage Rooms with Balcony', 'Spacious cottage rooms nestled amidst serene garden landscapes, featuring private sit-out balconies, comfortable queen bed, high-speed WiFi, LED television, tea/coffee station, and premium bathroom amenities.', 'Private Balcony, High-Speed WiFi, LED TV, Air Conditioning, Tea/Coffee Maker, Room Service, Luxury Toiletries', 6, 1, 1, 1, 3, 2, 1, 500.00, 'active'),
(2, 'Deluxe Premium Ground Floor with Balcony', 'Convenient ground-floor executive rooms with direct lawn and garden access, private balcony, comfortable queen bed, and modern ensuite bathroom.', 'Ground Floor Access, Private Balcony, High-Speed WiFi, LED TV, Air Conditioning, Intercom, Geyser', 5, 1, 1, 1, 3, 2, 1, 500.00, 'active'),
(3, 'Deluxe Premium First Floor with Balcony', 'Elevated premium rooms offering panoramic views of the landscaped grounds and swimming pool, tranquil private balcony, and peaceful natural ambiance.', 'First Floor Balcony with Pool View, High-Speed WiFi, LED TV, Air Conditioning, Tea Station, Premium Bedding', 6, 1, 1, 1, 3, 2, 1, 500.00, 'active'),
(4, 'Family Premium (Four Sharing) with Balcony', 'Expansive suite designed for family vacations or small groups of up to four guests, featuring dual queen beds, spacious balcony, and modern conveniences.', 'Four Sharing Capacity, Dual Beds, Private Balcony, High-Speed WiFi, 43\" LED TV, Air Conditioning, Refrigerator, Intercom', 2, 1, 1, 1, 5, 4, 2, 500.00, 'active')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `total_rooms` = VALUES(`total_rooms`);

-- 2. Seed: Base Room Tariffs (Placeholders on Enquiry)
INSERT INTO `room_rates` (`category_id`, `rate_title`, `rate_type`, `rate_ac`, `rate_non_ac`, `rate`) VALUES
(1, 'Standard Tariff', 'base', 0.00, 0.00, 0.00),
(2, 'Standard Tariff', 'base', 0.00, 0.00, 0.00),
(3, 'Standard Tariff', 'base', 0.00, 0.00, 0.00),
(4, 'Standard Tariff', 'base', 0.00, 0.00, 0.00)
ON DUPLICATE KEY UPDATE `rate_title` = VALUES(`rate_title`);

-- 3. Seed: Amenities
INSERT INTO `amenities` (`id`, `name`, `description`, `icon_class`, `status`) VALUES
(1, 'Swimming Pool', 'Crystal clear outdoor swimming pool with clean filtration (10:00 AM - 9:30 PM)', 'fa fa-tint', 'active'),
(2, 'Labanya Heshel Ghar', 'Authentic multi-cuisine dining with Bengali, Chinese & Mughlai specialties', 'fa fa-cutlery', 'active'),
(3, 'Banquet & Conference', 'Spacious AC banquet hall with audio-visual equipment and lawn access', 'fa fa-building', 'active'),
(4, 'Free High-Speed WiFi', 'Reliable high-speed wireless internet across cottages and common areas', 'fa fa-wifi', 'active'),
(5, 'Free Secure Parking', 'Ample complimentary on-site parking for cars and tourist buses', 'fa fa-car', 'active'),
(6, '24/7 Front Office Desk', 'Round-the-clock reception and travel assistance for Bolpur-Shantiniketan', 'fa fa-clock-o', 'active')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- 4. Seed: Labanya's Heshel Ghar Restaurant Timings
INSERT INTO `restaurant_timings` (`day_of_week`, `open_time`, `close_time`, `status`) VALUES
('Breakfast (Daily)', '09:00:00', '10:00:00', 'active'),
('Lunch - Authentic Thali (Daily)', '13:00:00', '15:00:00', 'active'),
('Evening Snacks (Daily)', '18:00:00', '20:30:00', 'active'),
('Dinner (Daily)', '21:00:00', '22:30:00', 'active')
ON DUPLICATE KEY UPDATE `open_time` = VALUES(`open_time`), `close_time` = VALUES(`close_time`);

-- 5. Seed: Sample Multi-Cuisine Menu Items
INSERT INTO `restaurant_items` (`name`, `description`, `price`, `category`, `is_veg`, `status`) VALUES
('Special Bengali Fish Thali', 'Steamed Gobindobhog Rice, Ghee, Shobji Dal, Bhaja, Seasonal Vegetable, Rui/Katla Kalia, Chutney & Papad', 280.00, 'Bengali', 0, 'active'),
('Authentic Mutton Kasha', 'Slow-cooked tender Bengali style spicy mutton gravy with baby potatoes', 380.00, 'Bengali', 0, 'active'),
('Chilli Chicken (Dry/Gravy)', 'Crispy wok-tossed chicken chunks with bell peppers, onion, and oriental spices', 260.00, 'Chinese', 0, 'active'),
('Veg Hakka Noodles', 'Wok-tossed noodles with crunchy seasonal vegetables and light soy sauce', 180.00, 'Chinese', 1, 'active'),
('Chicken Dum Biryani', 'Fragrant Basmati rice cooked with succulent chicken pieces, boiled egg and potato in Lucknowi style', 290.00, 'Mughlai', 0, 'active')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- 6. Seed: Banquet Details
INSERT INTO `banquet_details` (`id`, `name`, `capacity`, `description`, `price`, `facilities`, `status`) VALUES
(1, 'Parijat AC Banquet & Conference Hall', 250, 'Ideal for destination weddings, birthday gatherings, anniversary parties, institutional seminars, and corporate offsites in Shantiniketan.', 0.00, 'AC, Sound System, Projector Screen, Stage Lighting, Dedicated Catering by Labanya Heshel Ghar, Sprawling Lawn', 'active')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- 7. Seed: Core Site Settings
INSERT INTO `site_settings` (`setting_key`, `setting_value`, `description`) VALUES
('resort_name', 'Parijat Resort Bolpur & Banquet', 'Official name of the resort'),
('address', 'Near Shantiniketan, Bolpur, Birbhum, West Bengal - 731204, India', 'Full physical address'),
('phone_primary', '+91 98302 76364', 'Primary telephone number'),
('phone_secondary', '+91 98309 09875', 'Secondary telephone number'),
('whatsapp_number', '+91 98309 09875', 'Primary WhatsApp reservation number'),
('email_address', 'info@parijatresortbolpur.com', 'Primary resort email'),
('proprietor_name', 'Mr. Ananda Kumar Saha', 'Proprietor'),
('finance_head_name', 'Mr. Arindam Saha', 'Finance & Administrative Head'),
('manager_name', 'Mr. Tonmay Guha', 'Resort Manager'),
('manager_phone', '+91 87775 13554', 'Direct contact of Resort Manager'),
('check_in_time', '12:30 PM', 'Standard check-in time'),
('check_out_time', '11:00 AM', 'Standard check-out time'),
('swimming_pool_timings', '10:00 AM – 9:30 PM', 'Operating hours for the swimming pool'),
('swimming_pool_timing_note', 'Timings are subject to seasonal schedule and maintenance. Synthetic swimwear mandatory.', 'Pool operational footnote')
ON DUPLICATE KEY UPDATE `setting_value` = VALUES(`setting_value`);

-- 8. Seed: SEO Settings for All 8 Main Pages
INSERT INTO `seo_settings` (`page_name`, `meta_title`, `meta_description`, `meta_keywords`, `canonical_url`, `og_title`, `og_description`, `og_image`) VALUES
('home', 'Parijat Resort Bolpur | Luxury Resort & AC Banquet near Shantiniketan', 'Experience serene luxury at Parijat Resort Bolpur near Shantiniketan. 19 cottages & rooms with balconies, swimming pool, authentic Bengali dining at Labanya Heshel Ghar, and grand AC banquet.', 'resort in bolpur, parijat resort bolpur, resort in shantiniketan, luxury resort bolpur, banquet hall bolpur, swimming pool resort bolpur', 'https://parijatresortbolpur.com/', 'Parijat Resort Bolpur | Luxury Resort near Shantiniketan', 'Serene resort with 19 rooms, cottages with balconies, swimming pool, authentic Bengali dining, and AC banquet hall.', 'image/banner/banner-1.jpg'),
('about', 'About Us & Policies | Parijat Resort Bolpur Shantiniketan', 'Learn about Parijat Resort Bolpur, executive management, dining policies at Labanya Heshel Ghar, and swimming pool regulations.', 'about parijat resort, parijat resort management, bolpur resort policies, swimming pool rules', 'https://parijatresortbolpur.com/about', 'About Parijat Resort Bolpur', 'Resort history, leadership team, and guest guidelines.', 'image/cottage_exterior_lawn_1.jpeg'),
('accomodation', 'Rooms & Cottages with Balcony | Parijat Resort Bolpur', 'Explore 19 convertible AC/Non-AC rooms and cottages across 4 categories with private balconies, deluxe amenities, and family suites in Bolpur Shantiniketan.', 'rooms in bolpur, cottages in bolpur, deluxe rooms shantiniketan, parijat resort accommodation', 'https://parijatresortbolpur.com/accomodation', 'Rooms & Cottages | Parijat Resort Bolpur', 'Explore our 19 convertible rooms and cottages with balconies.', 'image/banner/banner-2.jpg'),
('dining', 'Labanya Heshel Ghar Restaurant | Parijat Resort Bolpur', 'Authentic Bengali thali, Chinese, and Mughlai delicacies freshly cooked to order at Labanya Heshel Ghar, Parijat Resort Bolpur.', 'restaurant in bolpur, bengali food shantiniketan, dining bolpur, labanya heshel ghar', 'https://parijatresortbolpur.com/dining', 'Labanya Heshel Ghar | Parijat Resort Bolpur', 'Authentic Bengali, Chinese & Mughlai dining freshly prepared for guests.', 'image/food/food-1.jpg'),
('banquet', 'AC Banquet & Conference Hall | Parijat Resort Bolpur', 'Book our AC banquet hall for destination weddings, corporate seminars, family events, and conferences in Bolpur with in-house catering.', 'banquet hall bolpur, wedding venue shantiniketan, conference hall bolpur, party lawn bolpur', 'https://parijatresortbolpur.com/banquet', 'AC Banquet & Conference Hall | Parijat Resort Bolpur', 'Spacious AC banquet hall and lawn for weddings and corporate gatherings.', 'image/banner/banner-3.jpg'),
('gallery', 'Photo Gallery | Parijat Resort Bolpur & Banquet', 'Browse real photographs of cottages, room balconies, swimming pool, dining hall, and lush green gardens at Parijat Resort Bolpur.', 'parijat resort photos, bolpur resort gallery, shantiniketan resort pictures', 'https://parijatresortbolpur.com/gallery', 'Photo Gallery | Parijat Resort Bolpur', 'Explore the visual beauty and facilities of Parijat Resort Bolpur.', 'image/gallery/01.jpg'),
('rules', '18 Resort Guidelines & Regulations | Parijat Resort Bolpur', 'Review check-in hours, dining schedules, swimming pool synthetic swimwear rule, advance deposits, and cancellation policies at Parijat Resort Bolpur.', 'resort rules bolpur, check-in policy parijat, pool timings bolpur, cancellation terms', 'https://parijatresortbolpur.com/rules', 'Resort Rules & Regulations | Parijat Resort Bolpur', 'Comprehensive 18 guest guidelines and policies for a safe, restful stay.', 'image/banner/banner-1.jpg'),
('contact', 'Contact & Reservations Desk | Parijat Resort Bolpur', 'Get in touch with Parijat Resort Bolpur. Direct phone numbers, WhatsApp reservations, manager contact, location address, and interactive enquiry form.', 'contact parijat resort, resort booking phone bolpur, bolpur resort whatsapp number', 'https://parijatresortbolpur.com/contact', 'Contact & Reservations | Parijat Resort Bolpur', 'Speak directly with our reservation team or send an instant booking enquiry.', 'image/contact_bg.jpg')
ON DUPLICATE KEY UPDATE `meta_title` = VALUES(`meta_title`), `meta_description` = VALUES(`meta_description`), `canonical_url` = VALUES(`canonical_url`);

-- 9. Seed: Core Policies
INSERT INTO `policies` (`title`, `type`, `content`, `status`) VALUES
('Check-In & Check-Out Hours', 'checkin', 'Standard Check-In time is 12:30 PM and Check-Out time is 11:00 AM. Early check-in or late check-out is subject to prior confirmation and room availability.', 'active'),
('Dining & Meal Ordering Notice', 'dining', 'All food at Labanya Heshel Ghar is cooked 100% fresh upon order; therefore, advance meal orders are required. Room delivery is available for breakfast, evening snacks, and dinner, but NOT during lunch because authentic Thali service is exclusively served in the dining hall. Outside food is strictly prohibited.', 'active'),
('Swimming Pool Hygiene & Dress Code', 'pool', 'Swimming pool operates between 10:00 AM and 9:30 PM. Synthetic swimwear is strictly mandatory for pool hygiene and water filtration. Guests must take a shower before stepping into pool water. During Holi, playing with colors in the pool is strictly forbidden.', 'active'),
('Reservation Advance & Cancellation Terms', 'cancellation', 'A 50% advance deposit is required to confirm room reservations. In regular season, cancellations carry no cash refund but may be carried forward up to 3 months for an alternate date. During peak season (Durga Puja, Poush Mela, Basanta Utsav, Christmas/New Year), cancellations are strictly non-refundable and dates cannot be altered.', 'active')
ON DUPLICATE KEY UPDATE `title` = VALUES(`title`), `content` = VALUES(`content`);

-- 10. Seed: Social Media Profiles
INSERT INTO `social_links` (`platform`, `url`, `icon_class`, `status`) VALUES
('Facebook', 'https://facebook.com/parijatresortbolpur', 'fa fa-facebook', 'active'),
('Instagram', 'https://instagram.com/parijatresortbolpur', 'fa fa-instagram', 'active'),
('WhatsApp', 'https://wa.me/919830909875', 'fa fa-whatsapp', 'active'),
('GoogleMaps', 'https://maps.google.com/?q=Parijat+Resort+Bolpur', 'fa fa-map-marker', 'active')
ON DUPLICATE KEY UPDATE `url` = VALUES(`url`);

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
