-- Database setup for ecommerce site
-- Run this in phpMyAdmin or MySQL command line

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS ecommerce_db;
USE ecommerce_db;

-- Drop existing tables if they exist (in correct order to handle foreign keys)
DROP TABLE IF EXISTS `cart`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `users`;

-- Create users table
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `first_name` VARCHAR(100) NULL,
    `last_name` VARCHAR(100) NULL,
    `phone` VARCHAR(20) NULL,
    `address` TEXT NULL,
    `is_active` BOOLEAN DEFAULT TRUE,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_username` (`username`),
    UNIQUE KEY `unique_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create products table
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `image` VARCHAR(255) NULL,
    `category` VARCHAR(100) NOT NULL,
    `stock_quantity` INT(11) DEFAULT 0,
    `is_active` BOOLEAN DEFAULT TRUE,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create cart table
CREATE TABLE IF NOT EXISTS `cart` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) UNSIGNED NULL,
    `session_id` VARCHAR(128) NULL,
    `product_id` INT(11) UNSIGNED NOT NULL,
    `quantity` INT(11) DEFAULT 1,
    `price` DECIMAL(10,2) NOT NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `session_id` (`session_id`),
    KEY `product_id` (`product_id`),
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample products from JSON data
INSERT INTO `products` (`name`, `description`, `price`, `image`, `category`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES
-- Electronics category
('Headphones', 'High-quality headphones for music lovers', 799.00, 'headphones.jpg', 'electronics', 50, TRUE, NOW(), NOW()),
('Smartwatch', 'Advanced smartwatch with health tracking', 1999.00, 'smartwatch.jpg', 'electronics', 30, TRUE, NOW(), NOW()),
('Camera', 'Professional digital camera', 25999.00, 'camera.jpg', 'electronics', 15, TRUE, NOW(), NOW()),
('Monitor', 'High-resolution computer monitor', 15999.00, 'monitor.jpg', 'electronics', 25, TRUE, NOW(), NOW()),

-- Headphones category
('Wireless Headphones', 'Premium wireless headphones with noise cancellation', 1499.99, 'headphones/headphone1.jpg', 'headphones', 20, TRUE, NOW(), NOW()),
('Noise-Canceling Headphones', 'Professional noise-canceling headphones', 2499.99, 'headphones/headphone2.jpg', 'headphones', 15, TRUE, NOW(), NOW()),
('Bluetooth Over-Ear Headphones', 'Comfortable over-ear Bluetooth headphones', 1799.00, 'headphones/headphone3.jpg', 'headphones', 25, TRUE, NOW(), NOW()),
('Bass Boost Wired Headphones', 'High-bass wired headphones for music enthusiasts', 799.00, 'headphones/headphone4.jpg', 'headphones', 30, TRUE, NOW(), NOW()),
('Gaming Headset with Mic', 'Gaming headset with built-in microphone', 2199.99, 'headphones/headphone5.jpg', 'headphones', 18, TRUE, NOW(), NOW()),
('Foldable Travel Headphones', 'Compact foldable headphones for travel', 1299.00, 'headphones/headphone6.jpg', 'headphones', 22, TRUE, NOW(), NOW()),
('Studio Monitor Headphones', 'Professional studio monitoring headphones', 2999.00, 'headphones/headphone7.jpg', 'headphones', 12, TRUE, NOW(), NOW()),
('Sports Neckband Headphones', 'Wireless neckband headphones for sports', 1099.00, 'headphones/headphone8.jpg', 'headphones', 28, TRUE, NOW(), NOW()),
('Noise-Isolating On-Ear Headphones', 'On-ear headphones with noise isolation', 899.00, 'headphones/headphone9.jpg', 'headphones', 35, TRUE, NOW(), NOW()),
('Wireless Earbuds Pro', 'Premium wireless earbuds with charging case', 3499.00, 'headphones/headphone10.jpg', 'headphones', 40, TRUE, NOW(), NOW()),
('Kids Safe Volume Headphones', 'Volume-limited headphones safe for children', 699.00, 'headphones/headphone11.jpg', 'headphones', 50, TRUE, NOW(), NOW()),
('Hi-Fi Over-Ear Headphones', 'High-fidelity over-ear headphones', 2699.00, 'headphones/headphone12.jpg', 'headphones', 16, TRUE, NOW(), NOW()),
('Wireless Headphones with ANC', 'Wireless headphones with active noise cancellation', 3999.00, 'headphones/headphone13.jpg', 'headphones', 14, TRUE, NOW(), NOW()),
('Lightweight Travel Headphones', 'Ultra-lightweight headphones for travel', 999.00, 'headphones/headphone14.jpg', 'headphones', 32, TRUE, NOW(), NOW()),
('Surround Sound Gaming Headphones', 'Gaming headphones with surround sound', 2899.00, 'headphones/headphone15.jpg', 'headphones', 20, TRUE, NOW(), NOW()),

-- Smartwatches category
('Smartwatch Pro', 'Advanced smartwatch with GPS and health monitoring', 3999.00, 'smartwatches/pro1.jpg', 'smartwatches', 25, TRUE, NOW(), NOW()),
('Fitness Tracker Watch', 'Fitness-focused smartwatch with heart rate monitor', 1999.00, 'smartwatches/fitness1.jpg', 'smartwatches', 40, TRUE, NOW(), NOW());
