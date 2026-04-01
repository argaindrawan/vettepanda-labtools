CREATE DATABASE IF NOT EXISTS `research_inventory` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `research_inventory`;

CREATE TABLE IF NOT EXISTS `inventory` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `item_name` VARCHAR(255) NOT NULL,
  `category` ENUM('equipment','material') NOT NULL,
  `quantity` INT NOT NULL DEFAULT 0,
  `unit` VARCHAR(64) NOT NULL,
  `location` VARCHAR(255) DEFAULT '',
  `last_updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `notes` TEXT DEFAULT NULL
);

INSERT INTO `inventory` (`item_name`, `category`, `quantity`, `unit`, `location`, `notes`) VALUES
('Microscope', 'equipment', 3, 'unit', 'Lab A', 'Optical microscope with camera'),
('Ethanol', 'material', 15, 'L', 'Chemical storage', '95% reagent grade');
