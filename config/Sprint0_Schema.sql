-- Add Agents Table
CREATE TABLE IF NOT EXISTS `agents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_code` varchar(50) NOT NULL UNIQUE,
  `fullname` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `status` enum('pending', 'verified', 'rejected') NOT NULL DEFAULT 'pending',
  `commission_rate` decimal(5,2) DEFAULT 0.00,
  `documents` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add Inquiries Table
CREATE TABLE IF NOT EXISTS `inquiries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `status` enum('new', 'contacted', 'resolved') NOT NULL DEFAULT 'new',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add agent_id to users table (if not exists)
-- Using a stored procedure style block or just simple ALTER IGNORE if possible, 
-- but MySQL doesn't support IF EXISTS in ALTER easily. 
-- We'll try to add it; if it fails, it might already exist.
-- To be safe, we can run this line alone or ignore error.
ALTER TABLE `users` ADD COLUMN `agent_id` int(11) DEFAULT NULL;
