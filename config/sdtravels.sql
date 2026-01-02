-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 02, 2026 at 10:00 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sdtravels`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_type` enum('admin','agent','client','system') NOT NULL,
  `action` varchar(100) NOT NULL,
  `entity_type` varchar(50) DEFAULT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `user_type`, `action`, `entity_type`, `entity_id`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 3, 'client', 'case_created', 'case', 1, 'Case CS-2025-E0BACE created', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 00:05:02'),
(2, 3, 'client', 'case_created', 'case', 2, 'Case CS-2025-741A0D created', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 00:06:15'),
(3, 3, 'client', 'case_created', 'case', 3, 'Case CS-2025-50A05F created', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 00:07:01'),
(4, 3, 'client', 'case_created', 'case', 4, 'Case CS-2025-C5C623 created', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 00:10:20'),
(5, 3, 'client', 'document_uploaded', 'document', 1, 'Document 1766704220_testFile.png uploaded', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 00:10:20'),
(6, 1, 'admin', 'update', 'settings', NULL, 'Updated case type pricing', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-30 17:39:26'),
(7, 1, 'admin', 'update', 'settings', NULL, 'Updated case type pricing', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-30 18:07:14'),
(8, 4, 'client', 'case_created', 'case', 5, 'Case CS-2025-285CB4 created', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 10:00:34'),
(9, 4, 'client', 'document_uploaded', 'document', 2, 'Document 1767171634_University Assembly Flyer.jpeg uploaded', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 10:00:34'),
(10, 4, 'client', 'case_created', 'case', 6, 'Case CS-2025-A87274 created', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 11:14:34'),
(11, 1, 'admin', 'case_stage_updated', 'case', 6, 'Case CS-2025-A87274 moved from assessment to offer', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 11:35:41'),
(12, 1, 'admin', 'case_stage_updated', 'case', 6, 'Case CS-2025-A87274 moved from offer to completed', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 11:35:49'),
(13, 1, 'admin', 'case_stage_updated', 'case', 6, 'Case CS-2025-A87274 moved from completed to travel', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 11:43:36'),
(14, 1, 'admin', 'case_stage_updated', 'case', 6, 'Case CS-2025-A87274 moved from travel to completed', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 11:43:41'),
(15, 1, 'admin', 'update', 'settings', NULL, 'Updated case type pricing', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 13:45:13'),
(16, 1, 'admin', 'case_created', 'case', 7, 'Case CS-2026-A3D5DB created', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 23:06:50'),
(17, 1, 'admin', 'case_stage_updated', 'case', 5, 'Case CS-2025-285CB4 moved from assessment to documents', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-02 09:36:16'),
(18, 1, 'admin', 'case_stage_updated', 'case', 5, 'Case CS-2025-285CB4 moved from documents to submission', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-02 09:36:41'),
(19, 1, 'admin', 'case_stage_updated', 'case', 5, 'Case CS-2025-285CB4 moved from submission to decision', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-02 09:36:55'),
(20, 1, 'admin', 'case_stage_updated', 'case', 5, 'Case CS-2025-285CB4 moved from decision to completed', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-02 09:37:04'),
(21, 1, 'admin', 'case_stage_updated', 'case', 5, 'Case CS-2025-285CB4 moved from completed to closed', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-02 09:37:28'),
(22, 1, 'admin', 'document_verified', 'document', 2, 'Document marked as verified', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-02 09:37:38');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`) VALUES
(1, 'admin@admin.com', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `agents`
--

CREATE TABLE `agents` (
  `id` int(11) NOT NULL,
  `agent_code` varchar(50) NOT NULL,
  `fullname` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `status` enum('pending','verified','rejected') NOT NULL DEFAULT 'pending',
  `commission_rate` decimal(5,2) DEFAULT 0.00,
  `documents` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `slug` varchar(100) DEFAULT NULL COMMENT 'SEO-friendly agent identifier',
  `wallet_balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_earned` decimal(12,2) NOT NULL DEFAULT 0.00,
  `referral_count` int(11) NOT NULL DEFAULT 0,
  `performance_id` int(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `account_name` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agents`
--

INSERT INTO `agents` (`id`, `agent_code`, `fullname`, `email`, `password`, `phone`, `status`, `commission_rate`, `documents`, `created_at`, `slug`, `wallet_balance`, `total_earned`, `referral_count`, `performance_id`, `address`, `city`, `country`, `bank_name`, `account_number`, `account_name`) VALUES
(1, 'AGT-7F0E1A', 'Dominique Conrad', 'savyjug@example.com', '00000000', '+1 (117) 797-2941', 'verified', 0.00, NULL, '2025-12-20 01:07:28', NULL, 1.70, 4000.00, 1, NULL, '', '', '', 'kuda', '2014205473', 'Vic Ike');

-- --------------------------------------------------------

--
-- Table structure for table `agent_performance`
--

CREATE TABLE `agent_performance` (
  `id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
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
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agent_performance`
--

INSERT INTO `agent_performance` (`id`, `agent_id`, `total_referrals`, `active_cases`, `completed_cases`, `total_earnings`, `rating_activity`, `rating_quality`, `rating_outcomes`, `rating_overall`, `tier`, `last_calculated_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 2, 4000.00, 1.10, 2.00, 5.00, 2.70, 'silver', '2026-01-02 09:42:27', '2025-12-30 13:52:09', '2026-01-02 09:42:27');

-- --------------------------------------------------------

--
-- Table structure for table `cases`
--

CREATE TABLE `cases` (
  `id` int(11) NOT NULL,
  `case_number` varchar(50) NOT NULL,
  `client_id` int(11) NOT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `case_type` enum('study_abroad','visa_student','visa_tourist','visa_family','travel_booking','pilgrimage','other') NOT NULL,
  `stage` enum('assessment','options','application','submission','offer','visa','travel','booking','completed','closed','documents','decision','requirements','processing') NOT NULL DEFAULT 'assessment',
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
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cases`
--

INSERT INTO `cases` (`id`, `case_number`, `client_id`, `agent_id`, `case_type`, `stage`, `status`, `title`, `description`, `destination_country`, `institution`, `program`, `intake`, `amount`, `commission_amount`, `commission_paid`, `assigned_to`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'CS-2025-E0BACE', 3, 0, 'study_abroad', 'assessment', 'active', 'Quibusdam ullam volu', 'Non dolores sit eiu', 'Germany', 'Ea qui molestiae sin', 'Deserunt nobis est d', 'January 2026', 81.00, 0.00, 'pending', NULL, '', '2025-12-26 00:05:02', '2025-12-26 00:05:02'),
(2, 'CS-2025-741A0D', 3, 0, 'study_abroad', 'assessment', 'active', 'Quibusdam ullam volu', 'Non dolores sit eiu', 'Germany', 'Ea qui molestiae sin', 'Deserunt nobis est d', 'January 2026', 81.00, 0.00, 'pending', NULL, '', '2025-12-26 00:06:15', '2025-12-26 00:06:15'),
(3, 'CS-2025-50A05F', 3, 0, 'study_abroad', 'assessment', 'active', 'Quibusdam ullam volu', 'Non dolores sit eiu', 'Germany', 'Ea qui molestiae sin', 'Deserunt nobis est d', 'January 2026', 81.00, 0.00, 'pending', NULL, '', '2025-12-26 00:07:01', '2025-12-26 00:07:01'),
(4, 'CS-2025-C5C623', 3, 0, 'visa_tourist', 'assessment', 'active', 'Deserunt sapiente al', 'Rem qui earum numqua', 'Dubai (UAE)', 'Id optio voluptatum', 'Irure aliquam ipsum ', 'September 2025', 3.00, 0.00, 'pending', NULL, '', '2025-12-26 00:10:20', '2025-12-26 00:10:20'),
(5, 'CS-2025-285CB4', 4, 1, 'visa_student', 'closed', 'active', 'Nesciunt molestiae ', 'Eos at Nam voluptas', 'United Kingdom', 'Magni duis commodi a', 'Consequatur ut vel ', 'September 2026', 17.00, 1.70, 'pending', NULL, '', '2025-12-31 10:00:34', '2026-01-02 09:37:28'),
(6, 'CS-2025-A87274', 4, 1, 'study_abroad', 'completed', 'active', 'At laborum in vel ut', 'Dolorem dolore nesci', 'Germany', 'Ut soluta aut ad eos', 'Similique aut simili', 'September 2025', 40000.00, 4000.00, 'pending', NULL, '', '2025-12-31 11:14:34', '2025-12-31 11:43:41'),
(7, 'CS-2026-A3D5DB', 5, NULL, 'travel_booking', 'assessment', 'active', 'Case from Inquiry - Charles Benjamin', 'Quis et rem et ipsam', '', '', '', '', 0.00, 0.00, 'pending', NULL, '', '2026-01-01 23:06:50', '2026-01-01 23:06:50');

-- --------------------------------------------------------

--
-- Table structure for table `case_stages_history`
--

CREATE TABLE `case_stages_history` (
  `id` int(11) NOT NULL,
  `case_id` int(11) NOT NULL,
  `from_stage` varchar(50) DEFAULT NULL,
  `to_stage` varchar(50) NOT NULL,
  `changed_by` int(11) NOT NULL,
  `changed_by_type` enum('admin','agent','client','system') NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `case_stages_history`
--

INSERT INTO `case_stages_history` (`id`, `case_id`, `from_stage`, `to_stage`, `changed_by`, `changed_by_type`, `notes`, `created_at`) VALUES
(1, 1, NULL, 'assessment', 3, 'client', 'Initial stage', '2025-12-26 00:05:02'),
(2, 2, NULL, 'assessment', 3, 'client', 'Initial stage', '2025-12-26 00:06:15'),
(3, 3, NULL, 'assessment', 3, 'client', 'Initial stage', '2025-12-26 00:07:01'),
(4, 4, NULL, 'assessment', 3, 'client', 'Initial stage', '2025-12-26 00:10:20'),
(5, 5, NULL, 'assessment', 4, 'client', 'Initial stage', '2025-12-31 10:00:34'),
(6, 6, NULL, 'assessment', 4, 'client', 'Initial stage', '2025-12-31 11:14:34'),
(7, 6, 'assessment', 'offer', 1, 'admin', '', '2025-12-31 11:35:41'),
(8, 6, 'offer', 'completed', 1, 'admin', '', '2025-12-31 11:35:49'),
(9, 6, 'completed', 'travel', 1, 'admin', '', '2025-12-31 11:43:36'),
(10, 6, 'travel', 'completed', 1, 'admin', '', '2025-12-31 11:43:41'),
(11, 7, NULL, 'assessment', 1, 'admin', 'Initial stage', '2026-01-01 23:06:50'),
(12, 5, 'assessment', 'documents', 1, 'admin', '', '2026-01-02 09:36:16'),
(13, 5, 'documents', 'submission', 1, 'admin', '', '2026-01-02 09:36:41'),
(14, 5, 'submission', 'decision', 1, 'admin', '', '2026-01-02 09:36:55'),
(15, 5, 'decision', 'completed', 1, 'admin', '', '2026-01-02 09:37:04'),
(16, 5, 'completed', 'closed', 1, 'admin', '', '2026-01-02 09:37:28');

-- --------------------------------------------------------

--
-- Table structure for table `commissions`
--

CREATE TABLE `commissions` (
  `id` int(11) NOT NULL,
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
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commissions`
--

INSERT INTO `commissions` (`id`, `agent_id`, `case_id`, `client_id`, `commission_type`, `amount`, `rate_percentage`, `case_amount`, `status`, `payment_method`, `payment_reference`, `paid_date`, `approved_by`, `approved_at`, `notes`, `created_at`) VALUES
(1, 1, 6, 4, 'case_completion', 4000.00, 10.00, 40000.00, 'paid', NULL, NULL, '2025-12-31 11:47:17', 1, '2025-12-31 11:47:17', NULL, '2025-12-31 11:43:41'),
(2, 1, 5, 4, 'case_completion', 1.70, 10.00, 17.00, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-02 09:37:04');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `case_id` int(11) DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `document_type` enum('passport','transcript','certificate','statement_of_purpose','cv','recommendation','financial_proof','visa','offer_letter','other') NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_size` bigint(20) DEFAULT NULL,
  `uploaded_by` enum('client','agent','admin','system') NOT NULL DEFAULT 'client',
  `status` enum('pending','verified','rejected','expired') NOT NULL DEFAULT 'pending',
  `expiry_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `case_id`, `client_id`, `document_type`, `file_path`, `file_name`, `file_size`, `uploaded_by`, `status`, `expiry_date`, `notes`, `created_at`) VALUES
(1, 4, 3, 'recommendation', '../uploads/documents/1766704220_testFile.png', '1766704220_testFile.png', 150, 'client', 'pending', NULL, NULL, '2025-12-26 00:10:20'),
(2, 5, 4, 'certificate', '../uploads/documents/1767171634_University Assembly Flyer.jpeg', '1767171634_University Assembly Flyer.jpeg', 84910, 'client', 'verified', NULL, '', '2025-12-31 10:00:34');

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `status` enum('new','contacted','resolved') NOT NULL DEFAULT 'new',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `service_type` enum('study_abroad','visa_student','visa_tourist','visa_family','travel_booking','pilgrimage','other') DEFAULT NULL,
  `source` enum('website','whatsapp','referral','social_media','other') NOT NULL DEFAULT 'website',
  `converted_to_case` tinyint(1) NOT NULL DEFAULT 0,
  `case_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `message`, `agent_id`, `status`, `created_at`, `service_type`, `source`, `converted_to_case`, `case_id`) VALUES
(1, 'Charles Benjamin', 'bebefuw@example.com', '+1 (775) 793-7684', 'Quis et rem et ipsam', NULL, 'new', '2025-12-31 19:32:34', 'travel_booking', 'website', 1, 7);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` enum('admin','agent','client') NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','success','warning','error') NOT NULL DEFAULT 'info',
  `link` varchar(500) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `user_type`, `title`, `message`, `type`, `link`, `is_read`, `created_at`) VALUES
(1, 1, 'agent', 'Agent Alert', 'Please check your dashboard for important updates.', 'info', '', 1, '2025-12-30 16:16:22'),
(2, 1, 'agent', 'Case Stage Updated', 'Case CS-2025-A87274 has been moved to Offer Received', 'success', '/agent/cases.php?view=6', 1, '2025-12-31 11:35:41'),
(3, 4, 'client', 'Your Case Status Updated', 'Your case CS-2025-A87274 has been updated to: Offer Received', 'success', '/user/cases.php?view=6', 0, '2025-12-31 11:35:41'),
(4, 1, 'agent', 'Case Stage Updated', 'Case CS-2025-A87274 has been moved to Completed', 'success', '/agent/cases.php?view=6', 1, '2025-12-31 11:35:49'),
(5, 4, 'client', 'Your Case Status Updated', 'Your case CS-2025-A87274 has been updated to: Completed', 'success', '/user/cases.php?view=6', 0, '2025-12-31 11:35:49'),
(6, 1, 'agent', 'Case Stage Updated', 'Case CS-2025-A87274 has been moved to Travel Arrangements', 'success', '/agent/cases.php?view=6', 1, '2025-12-31 11:43:36'),
(7, 4, 'client', 'Your Case Status Updated', 'Your case CS-2025-A87274 has been updated to: Travel Arrangements', 'success', '/user/cases.php?view=6', 0, '2025-12-31 11:43:36'),
(8, 1, 'agent', 'Case Stage Updated', 'Case CS-2025-A87274 has been moved to Completed', 'success', '/agent/cases.php?view=6', 1, '2025-12-31 11:43:41'),
(9, 4, 'client', 'Your Case Status Updated', 'Your case CS-2025-A87274 has been updated to: Completed', 'success', '/user/cases.php?view=6', 0, '2025-12-31 11:43:41'),
(10, 1, 'agent', 'Commission Earned', 'You earned a commission of ₦4,000.00 for case #CS-2025-A87274', 'success', 'commissions.php', 1, '2025-12-31 11:43:41'),
(11, 1, 'agent', 'Case Stage Updated', 'Case CS-2025-285CB4 has been moved to documents', 'success', '/agent/cases.php?view=5', 1, '2026-01-02 09:36:16'),
(12, 4, 'client', 'Your Case Status Updated', 'Your case CS-2025-285CB4 has been updated to: documents', 'success', '/user/cases.php?view=5', 0, '2026-01-02 09:36:16'),
(13, 1, 'agent', 'Case Stage Updated', 'Case CS-2025-285CB4 has been moved to Submission Complete', 'success', '/agent/cases.php?view=5', 1, '2026-01-02 09:36:41'),
(14, 4, 'client', 'Your Case Status Updated', 'Your case CS-2025-285CB4 has been updated to: Submission Complete', 'success', '/user/cases.php?view=5', 0, '2026-01-02 09:36:41'),
(15, 1, 'agent', 'Case Stage Updated', 'Case CS-2025-285CB4 has been moved to Decision Pending', 'success', '/agent/cases.php?view=5', 1, '2026-01-02 09:36:55'),
(16, 4, 'client', 'Your Case Status Updated', 'Your case CS-2025-285CB4 has been updated to: Decision Pending', 'success', '/user/cases.php?view=5', 0, '2026-01-02 09:36:55'),
(17, 1, 'agent', 'Case Stage Updated', 'Case CS-2025-285CB4 has been moved to Completed', 'success', '/agent/cases.php?view=5', 1, '2026-01-02 09:37:04'),
(18, 4, 'client', 'Your Case Status Updated', 'Your case CS-2025-285CB4 has been updated to: Completed', 'success', '/user/cases.php?view=5', 0, '2026-01-02 09:37:04'),
(19, 1, 'agent', 'Commission Earned', 'You earned a commission of ₦1.70 for case #CS-2025-285CB4', 'success', 'commissions.php', 1, '2026-01-02 09:37:04'),
(20, 1, 'agent', 'Case Stage Updated', 'Case CS-2025-285CB4 has been moved to Closed', 'success', '/agent/cases.php?view=5', 1, '2026-01-02 09:37:28'),
(21, 4, 'client', 'Your Case Status Updated', 'Your case CS-2025-285CB4 has been updated to: Closed', 'success', '/user/cases.php?view=5', 0, '2026-01-02 09:37:28');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `case_id` int(11) DEFAULT NULL,
  `reference` varchar(100) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `currency` varchar(10) DEFAULT 'NGN',
  `status` enum('pending','success','failed','refunded') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT 'paystack',
  `case_type` varchar(50) DEFAULT NULL,
  `metadata` text DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `case_id`, `reference`, `amount`, `currency`, `status`, `payment_method`, `case_type`, `metadata`, `paid_at`, `created_at`) VALUES
(1, 4, NULL, 'APP_1767174458_4_E4D732', 40000.00, 'NGN', 'pending', 'paystack', 'study_abroad', '{\"case_type\":\"study_abroad\",\"title\":\"In asperiores laboru\",\"description\":\"Accusamus sit magni \",\"destination_country\":\"Canada\",\"institution\":\"Ad quis reprehenderi\",\"program\":\"Enim fugit dolor se\",\"intake\":\"January 2026\",\"amount\":40000,\"commission\":4000}', NULL, '2025-12-31 10:47:38'),
(2, 4, NULL, 'APP_1767175035_4_A21951', 40000.00, 'NGN', 'pending', 'paystack', 'study_abroad', '{\"case_type\":\"study_abroad\",\"title\":\"Voluptatibus error d\",\"description\":\"Et veritatis a dolor\",\"destination_country\":\"Saudi Arabia\",\"institution\":\"Qui ut ipsum doloru\",\"program\":\"Sed consequatur Est\",\"intake\":\"January 2025\",\"amount\":40000,\"commission\":4000}', NULL, '2025-12-31 10:57:15'),
(3, 4, NULL, 'APP_1767175123_4_B2E1E9', 40000.00, 'NGN', 'pending', 'paystack', 'study_abroad', '{\"case_type\":\"study_abroad\",\"title\":\"Voluptatibus error d\",\"description\":\"Et veritatis a dolor\",\"destination_country\":\"Saudi Arabia\",\"institution\":\"Qui ut ipsum doloru\",\"program\":\"Sed consequatur Est\",\"intake\":\"January 2025\",\"amount\":40000,\"commission\":4000}', NULL, '2025-12-31 10:58:43'),
(4, 4, 6, 'APP_1767175845_4_963646', 40000.00, 'NGN', 'success', 'paystack', 'study_abroad', '{\"case_type\":\"study_abroad\",\"title\":\"At laborum in vel ut\",\"description\":\"Dolorem dolore nesci\",\"destination_country\":\"Germany\",\"institution\":\"Ut soluta aut ad eos\",\"program\":\"Similique aut simili\",\"intake\":\"September 2025\",\"amount\":40000,\"commission\":4000}', '2025-12-31 11:14:34', '2025-12-31 11:10:45');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_group` varchar(50) DEFAULT 'general',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `setting_group`, `updated_at`, `updated_by`) VALUES
(1, 'case_amount_study_abroad', '40000', 'case_pricing', '2025-12-31 13:45:13', 1),
(2, 'case_commission_study_abroad', '0', 'case_pricing', '2025-12-31 13:45:13', 1),
(3, 'case_commission_percent_study_abroad', '10', 'case_pricing', '2025-12-31 13:45:13', 1),
(4, 'case_amount_visa_student', '30000', 'case_pricing', '2025-12-31 13:45:13', 1),
(5, 'case_commission_visa_student', '0', 'case_pricing', '2025-12-31 13:45:13', 1),
(6, 'case_commission_percent_visa_student', '10', 'case_pricing', '2025-12-31 13:45:13', 1),
(7, 'case_amount_visa_tourist', '0', 'case_pricing', '2025-12-31 13:45:13', 1),
(8, 'case_commission_visa_tourist', '0', 'case_pricing', '2025-12-31 13:45:13', 1),
(9, 'case_commission_percent_visa_tourist', '10', 'case_pricing', '2025-12-31 13:45:13', 1),
(10, 'case_amount_visa_family', '0', 'case_pricing', '2025-12-31 13:45:13', 1),
(11, 'case_commission_visa_family', '0', 'case_pricing', '2025-12-31 13:45:13', 1),
(12, 'case_commission_percent_visa_family', '10', 'case_pricing', '2025-12-31 13:45:13', 1),
(13, 'case_amount_travel_booking', '0', 'case_pricing', '2025-12-31 13:45:13', 1),
(14, 'case_commission_travel_booking', '0', 'case_pricing', '2025-12-31 13:45:13', 1),
(15, 'case_commission_percent_travel_booking', '10', 'case_pricing', '2025-12-31 13:45:13', 1),
(16, 'case_amount_pilgrimage', '0', 'case_pricing', '2025-12-31 13:45:13', 1),
(17, 'case_commission_pilgrimage', '0', 'case_pricing', '2025-12-31 13:45:13', 1),
(18, 'case_commission_percent_pilgrimage', '10', 'case_pricing', '2025-12-31 13:45:13', 1),
(19, 'case_amount_other', '0', 'case_pricing', '2025-12-31 13:45:13', 1),
(20, 'case_commission_other', '0', 'case_pricing', '2025-12-31 13:45:13', 1),
(21, 'case_commission_percent_other', '10', 'case_pricing', '2025-12-31 13:45:13', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `userid` varchar(30) NOT NULL,
  `fullname` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `agent_id` int(11) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `passport_number` varchar(100) DEFAULT NULL,
  `profile_complete` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `userid`, `fullname`, `email`, `phone`, `password`, `created_at`, `agent_id`, `country`, `city`, `address`, `date_of_birth`, `passport_number`, `profile_complete`) VALUES
(1, '67e3d4d10fa8c', 'Vic Ike', 'ike@gmail.com', NULL, '000000', '2025-03-26 11:20:01', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(2, '67ed81b13502b', 'Rhoda Hardin', 'fobu@example.com', NULL, '000000', '2025-04-02 19:28:01', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(3, '694daa1309691', 'Bernard Kelly', 'abc@gmail.com', NULL, '00000000', '2025-12-25 22:18:11', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(4, 'USR6953d64b7d8d6', 'Zorita Osborn', 'junyhe@example.com', NULL, '00000000', '2025-12-30 14:40:27', 1, NULL, NULL, NULL, NULL, NULL, 0),
(5, '6956ef122aba4', 'Charles Benjamin', 'bebefuw@example.com', NULL, 'Password123', '2026-01-01 23:02:58', NULL, NULL, NULL, NULL, NULL, NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_user_type` (`user_type`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_entity_type` (`entity_type`),
  ADD KEY `idx_entity_id` (`entity_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agents`
--
ALTER TABLE `agents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `agent_code` (`agent_code`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `agent_performance`
--
ALTER TABLE `agent_performance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `agent_id` (`agent_id`),
  ADD KEY `idx_agent_id` (`agent_id`);

--
-- Indexes for table `cases`
--
ALTER TABLE `cases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `case_number` (`case_number`),
  ADD KEY `idx_client_id` (`client_id`),
  ADD KEY `idx_agent_id` (`agent_id`),
  ADD KEY `idx_case_type` (`case_type`),
  ADD KEY `idx_stage` (`stage`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `case_stages_history`
--
ALTER TABLE `case_stages_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_case_id` (`case_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `commissions`
--
ALTER TABLE `commissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_agent_id` (`agent_id`),
  ADD KEY `idx_case_id` (`case_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_case_id` (`case_id`),
  ADD KEY `idx_client_id` (`client_id`),
  ADD KEY `idx_document_type` (`document_type`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_user_type` (`user_type`),
  ADD KEY `idx_is_read` (`is_read`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reference` (`reference`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_case_id` (`case_id`),
  ADD KEY `idx_reference` (`reference`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`),
  ADD KEY `idx_setting_group` (`setting_group`),
  ADD KEY `idx_setting_key` (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `agents`
--
ALTER TABLE `agents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `agent_performance`
--
ALTER TABLE `agent_performance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cases`
--
ALTER TABLE `cases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `case_stages_history`
--
ALTER TABLE `case_stages_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `commissions`
--
ALTER TABLE `commissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
