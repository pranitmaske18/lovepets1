CREATE DATABASE IF NOT EXISTS `lovepets_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `lovepets_db`;

CREATE TABLE `pets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pet_name` varchar(255) NOT NULL,
  `description` text,
  `photos` json,
  `seller_name` varchar(255) NOT NULL,
  `phone_number` varchar(20),
  `address` text,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `is_adopted` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `adopted_pets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pet_id` int(11),
  `pet_name` varchar(255) NOT NULL,
  `description` text,
  `photos` json,
  `seller_name` varchar(255),
  `adopted_by` varchar(255) NOT NULL,
  `adopter_email` varchar(255),
  `adoption_message` text,
  `adopted_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pet_id` (`pet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data
INSERT INTO `pets` (`pet_name`, `description`, `photos`, `seller_name`, `phone_number`, `address`) VALUES
('Sample Dog', 'Playful pup', '["uploads/sample-dog.jpg"]', 'John Doe', '1234567890', 'Sample Address');
