-- ============================================================================
-- STUDENT LOAN APPLICATION FEATURE - DATABASE MIGRATION
-- ApplyBoard Africa Ltd
-- ============================================================================

-- ----------------------------------------------------------------------------
-- 1. Create student_loans table
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `student_loans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_number` varchar(50) NOT NULL UNIQUE,
  `user_id` int(11) NOT NULL,
  `case_id` int(11) DEFAULT NULL COMMENT 'Linked case if applicable',
  `loan_type` enum('tuition','living_expenses','full_program','travel','other') NOT NULL DEFAULT 'tuition',
  `loan_amount_requested` decimal(12,2) NOT NULL,
  `loan_amount_approved` decimal(12,2) DEFAULT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'NGN',
  `purpose` text DEFAULT NULL COMMENT 'Purpose of the loan',
  `program_name` varchar(255) DEFAULT NULL COMMENT 'Program being funded',
  `institution_name` varchar(255) DEFAULT NULL COMMENT 'Institution/University',
  `course_duration` int(11) DEFAULT NULL COMMENT 'Duration in months',
  `program_start_date` date DEFAULT NULL,
  `program_end_date` date DEFAULT NULL,

  -- Applicant Information
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,

  -- Employment/Financial Information
  `employment_status` enum('employed','self_employed','unemployed','student','other') NOT NULL DEFAULT 'student',
  `employer_name` varchar(255) DEFAULT NULL,
  `monthly_income` decimal(12,2) DEFAULT NULL,
  `income_source` varchar(255) DEFAULT NULL,
  `has_collateral` tinyint(1) DEFAULT 0,
  `collateral_type` varchar(255) DEFAULT NULL,
  `collateral_value` decimal(12,2) DEFAULT NULL,
  `has_guarantor` tinyint(1) DEFAULT 0,
  `guarantor_name` varchar(255) DEFAULT NULL,
  `guarantor_email` varchar(255) DEFAULT NULL,
  `guarantor_phone` varchar(50) DEFAULT NULL,
  `guarantor_relationship` varchar(100) DEFAULT NULL,
  `guarantor_address` text DEFAULT NULL,

  -- Loan Terms
  `repayment_period` int(11) DEFAULT NULL COMMENT 'Repayment period in months',
  `interest_rate` decimal(5,2) DEFAULT NULL COMMENT 'Annual interest rate',
  `monthly_repayment` decimal(12,2) DEFAULT NULL,
  `grace_period` int(11) DEFAULT 6 COMMENT 'Grace period in months before repayment starts',

  -- Application Status
  `status` enum('draft','pending','under_review','approved','rejected','disbursed','repaying','completed','defaulted') NOT NULL DEFAULT 'pending',
  `submission_date` datetime DEFAULT NULL,
  `review_date` datetime DEFAULT NULL,
  `approval_date` datetime DEFAULT NULL,
  `disbursement_date` datetime DEFAULT NULL,
  `disbursement_method` varchar(100) DEFAULT NULL,
  `disbursement_reference` varchar(255) DEFAULT NULL,

  -- Repayment Tracking
  `total_repaid` decimal(12,2) DEFAULT 0.00,
  `remaining_balance` decimal(12,2) DEFAULT NULL,
  `next_payment_due` date DEFAULT NULL,
  `last_payment_date` date DEFAULT NULL,

  -- Admin/Review Notes
  `review_notes` text DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `reviewed_by` int(11) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,

  -- Documents
  `documents_submitted` text DEFAULT NULL COMMENT 'JSON array of submitted document types',
  `documents_verified` tinyint(1) DEFAULT 0,

  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),

  PRIMARY KEY (`id`),
  UNIQUE KEY (`loan_number`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_case_id` (`case_id`),
  KEY `idx_status` (`status`),
  KEY `idx_submission_date` (`submission_date`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------------------------------------------------------
-- 2. Create loan_repayments table for tracking loan repayments
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `loan_repayments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_id` int(11) NOT NULL,
  `payment_reference` varchar(100) NOT NULL UNIQUE,
  `amount` decimal(12,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT 'paystack',
  `payment_date` datetime NOT NULL,
  `status` enum('pending','success','failed','reversed') NOT NULL DEFAULT 'success',
  `transaction_id` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),

  PRIMARY KEY (`id`),
  KEY `idx_loan_id` (`loan_id`),
  KEY `idx_payment_reference` (`payment_reference`),
  KEY `idx_status` (`status`),
  KEY `idx_payment_date` (`payment_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------------------------------------------------------
-- 3. Create loan_documents table for loan-specific documents
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `loan_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_id` int(11) NOT NULL,
  `document_type` enum('identity_proof','income_proof','admission_letter','fee_schedule','bank_statement','guarantor_form','collateral_document','other') NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_size` bigint DEFAULT NULL,
  `status` enum('pending','verified','rejected') NOT NULL DEFAULT 'pending',
  `verification_notes` text DEFAULT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT current_timestamp(),

  PRIMARY KEY (`id`),
  KEY `idx_loan_id` (`loan_id`),
  KEY `idx_document_type` (`document_type`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------------------------------------------------------
-- 4. Update cases table to include student_loan case type
-- ----------------------------------------------------------------------------
-- Note: This modifies the ENUM to include 'student_loan'
ALTER TABLE `cases`
MODIFY COLUMN `case_type` enum('study_abroad','visa_student','visa_tourist','visa_family','travel_booking','pilgrimage','student_loan','other') NOT NULL;

-- ----------------------------------------------------------------------------
-- 5. Update documents table to include loan-related document types
-- ----------------------------------------------------------------------------
ALTER TABLE `documents`
MODIFY COLUMN `document_type` enum('passport','transcript','certificate','statement_of_purpose','cv','recommendation','financial_proof','visa','offer_letter','identity_proof','income_proof','admission_letter','fee_schedule','bank_statement','guarantor_form','collateral_document','other') NOT NULL;

-- ----------------------------------------------------------------------------
-- 5a. Add bank account columns to student_loans table for disbursement
-- ----------------------------------------------------------------------------

-- Only add columns if they don't exist
ALTER TABLE `student_loans`
ADD COLUMN IF NOT EXISTS `bank_name` varchar(255) DEFAULT NULL COMMENT 'Bank name for disbursement',
ADD COLUMN IF NOT EXISTS `account_number` varchar(50) DEFAULT NULL COMMENT 'Bank account number',
ADD COLUMN IF NOT EXISTS `account_name` varchar(255) DEFAULT NULL COMMENT 'Account holder name',
ADD COLUMN IF NOT EXISTS `account_type` enum('savings','current') DEFAULT 'savings' COMMENT 'Account type';

-- ----------------------------------------------------------------------------
-- 5b. Update settings table to include description column
-- ----------------------------------------------------------------------------
ALTER TABLE `settings`
ADD COLUMN IF NOT EXISTS `description` text DEFAULT NULL;

-- ----------------------------------------------------------------------------
-- 6. Insert default settings for loan pricing
-- ----------------------------------------------------------------------------
INSERT IGNORE INTO `settings` (`setting_key`, `setting_value`, `description`) VALUES
('loan_min_amount', '100000', 'Minimum loan amount in NGN'),
('loan_max_amount', '5000000', 'Maximum loan amount in NGN'),
('loan_default_interest_rate', '15', 'Default annual interest rate for loans (%)'),
('loan_max_duration', '36', 'Maximum loan duration in months'),
('loan_processing_fee', '5000', 'Loan application processing fee in NGN'),
('loan_case_amount_student_loan', '2500', 'Case amount for student loan applications'),
('loan_case_commission_student_loan', '500', 'Fixed commission for student loan applications'),
('loan_case_commission_percent_student_loan', '10', 'Commission percentage for student loans');

-- ----------------------------------------------------------------------------
-- Migration Complete
-- ----------------------------------------------------------------------------
