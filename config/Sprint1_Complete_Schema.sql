-- ============================================================================
-- APPLYBOARD AFRICA LTD - COMPLETE DATABASE SCHEMA
-- Per plan.txt requirements
-- ============================================================================

-- ----------------------------------------------------------------------------
-- 1. CASES TABLE - Core application/case tracking
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `cases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `case_number` varchar(50) NOT NULL,
  `client_id` int(11) NOT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `case_type` enum('study_abroad','visa_student','visa_tourist','visa_family','travel_booking','pilgrimage','other') NOT NULL,
  `stage` enum('assessment','options','application','submission','offer','visa','travel','booking','completed','closed') NOT NULL DEFAULT 'assessment',
  `status` enum('active','on_hold','cancelled','completed') NOT NULL DEFAULT 'active',
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `destination_country` varchar(100) DEFAULT NULL,
  `institution` varchar(255) DEFAULT NULL,
  `program` varchar(255) DEFAULT NULL,
  `intake` varchar(50) DEFAULT NULL,
  `amount` decimal(12,2) DEFAULT 0.00,
  `commission_amount` decimal(12,2) DEFAULT 0.00,
  `commission_paid` enum('pending','partial','paid') NOT NULL DEFAULT 'pending',
  `assigned_to` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `case_number` (`case_number`),
  KEY `idx_client_id` (`client_id`),
  KEY `idx_agent_id` (`agent_id`),
  KEY `idx_case_type` (`case_type`),
  KEY `idx_stage` (`stage`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------------------------------------------------------
-- 2. DOCUMENTS TABLE - Document management for cases
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `case_id` int(11) DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `document_type` enum('passport','transcript','certificate','statement_of_purpose','cv','recommendation','financial_proof','visa','offer_letter','other') NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_size` bigint DEFAULT NULL,
  `uploaded_by` enum('client','agent','admin','system') NOT NULL DEFAULT 'client',
  `status` enum('pending','verified','rejected','expired') NOT NULL DEFAULT 'pending',
  `expiry_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_case_id` (`case_id`),
  KEY `idx_client_id` (`client_id`),
  KEY `idx_document_type` (`document_type`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------------------------------------------------------
-- 3. COMMISSIONS TABLE - Commission tracking and payments
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `commissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_id` int(11) NOT NULL,
  `case_id` int(11) DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `commission_type` enum('referral','case_completion','service','bonus') NOT NULL DEFAULT 'case_completion',
  `amount` decimal(12,2) NOT NULL,
  `rate_percentage` decimal(5,2) DEFAULT NULL,
  `case_amount` decimal(12,2) DEFAULT 0.00,
  `status` enum('pending','approved','paid','rejected','cancelled') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(100) DEFAULT NULL,
  `payment_reference` varchar(255) DEFAULT NULL,
  `paid_date` datetime DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_agent_id` (`agent_id`),
  KEY `idx_case_id` (`case_id`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------------------------------------------------------
-- 4. ACTIVITY_LOGS TABLE - Audit trail for all actions
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `user_type` enum('admin','agent','client','system') NOT NULL,
  `action` varchar(100) NOT NULL,
  `entity_type` varchar(50) DEFAULT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_user_type` (`user_type`),
  KEY `idx_action` (`action`),
  KEY `idx_entity_type` (`entity_type`),
  KEY `idx_entity_id` (`entity_id`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------------------------------------------------------
-- 5. CASE_STAGES_HISTORY TABLE - Track case stage transitions
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `case_stages_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `case_id` int(11) NOT NULL,
  `from_stage` varchar(50) DEFAULT NULL,
  `to_stage` varchar(50) NOT NULL,
  `changed_by` int(11) NOT NULL,
  `changed_by_type` enum('admin','agent','client','system') NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_case_id` (`case_id`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------------------------------------------------------
-- 6. AGENT_PERFORMANCE TABLE - Track agent ratings and performance
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `agent_performance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_id` int(11) NOT NULL UNIQUE,
  `total_referrals` int(11) NOT NULL DEFAULT 0,
  `active_cases` int(11) NOT NULL DEFAULT 0,
  `completed_cases` int(11) NOT NULL DEFAULT 0,
  `total_earnings` decimal(12,2) NOT NULL DEFAULT 0.00,
  `rating_activity` decimal(3,2) DEFAULT 0.00 COMMENT 'Activity score 0-5',
  `rating_quality` decimal(3,2) DEFAULT 0.00 COMMENT 'Quality score 0-5',
  `rating_outcomes` decimal(3,2) DEFAULT 0.00 COMMENT 'Outcomes score 0-5',
  `rating_overall` decimal(3,2) DEFAULT 0.00 COMMENT 'Overall score 0-5',
  `tier` enum('bronze','silver','gold','platinum') NOT NULL DEFAULT 'bronze',
  `last_calculated_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_agent_id` (`agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------------------------------------------------------
-- 7. NOTIFICATIONS TABLE - Store notifications for users
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_type` enum('admin','agent','client') NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','success','warning','error') NOT NULL DEFAULT 'info',
  `link` varchar(500) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_user_type` (`user_type`),
  KEY `idx_is_read` (`is_read`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------------------------------------------------------
-- 8. Update agents table with performance fields
-- ----------------------------------------------------------------------------
ALTER TABLE `agents`
ADD COLUMN IF NOT EXISTS `slug` varchar(100) DEFAULT NULL COMMENT 'SEO-friendly agent identifier',
ADD COLUMN IF NOT EXISTS `wallet_balance` decimal(12,2) NOT NULL DEFAULT 0.00,
ADD COLUMN IF NOT EXISTS `total_earned` decimal(12,2) NOT NULL DEFAULT 0.00,
ADD COLUMN IF NOT EXISTS `referral_count` int(11) NOT NULL DEFAULT 0,
ADD COLUMN IF NOT EXISTS `performance_id` int(11) DEFAULT NULL;

-- ----------------------------------------------------------------------------
-- 9. Update inquiries table for better tracking
-- ----------------------------------------------------------------------------
ALTER TABLE `inquiries`
ADD COLUMN IF NOT EXISTS `service_type` enum('study_abroad','visa_student','visa_tourist','visa_family','travel_booking','pilgrimage','other') DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `source` enum('website','whatsapp','referral','social_media','other') NOT NULL DEFAULT 'website',
ADD COLUMN IF NOT EXISTS `converted_to_case` tinyint(1) NOT NULL DEFAULT 0,
ADD COLUMN IF NOT EXISTS `case_id` int(11) DEFAULT NULL;

-- ----------------------------------------------------------------------------
-- 10. Update users table for client role
-- ----------------------------------------------------------------------------
ALTER TABLE `users`
ADD COLUMN IF NOT EXISTS `phone` varchar(30) DEFAULT NULL AFTER `email`,
ADD COLUMN IF NOT EXISTS `country` varchar(100) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `city` varchar(100) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `address` text DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `date_of_birth` date DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `passport_number` varchar(100) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `profile_complete` tinyint(1) NOT NULL DEFAULT 0;
