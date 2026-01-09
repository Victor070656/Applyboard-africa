<?php
/**
 * STUDENT LOAN APPLICATION HELPER FUNCTIONS
 * ApplyBoard Africa Ltd - Student Loan Management System
 */

// ============================================================================
// LOAN CONFIGURATION
// ============================================================================

/**
 * Get loan configuration settings
 */
function getLoanSettings()
{
    global $conn;

    $settings = [
        'min_amount' => 100000,
        'max_amount' => 5000000,
        'default_interest_rate' => 15,
        'max_duration' => 36,
        'processing_fee' => 5000,
        'grace_period' => 6
    ];

    $result = mysqli_query($conn, "SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'loan_%'");
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $key = str_replace('loan_', '', $row['setting_key']);
            $settings[$key] = is_numeric($row['setting_value']) ? floatval($row['setting_value']) : $row['setting_value'];
        }
    }

    return $settings;
}

/**
 * Calculate monthly repayment amount
 */
function calculateMonthlyRepayment($principal, $annualRate, $months)
{
    if ($principal <= 0 || $months <= 0) {
        return 0;
    }

    $monthlyRate = $annualRate / 100 / 12;

    if ($monthlyRate == 0) {
        return $principal / $months;
    }

    $monthlyPayment = $principal * ($monthlyRate * pow(1 + $monthlyRate, $months)) / (pow(1 + $monthlyRate, $months) - 1);

    return round($monthlyPayment, 2);
}

/**
 * Generate unique loan number
 */
function generateLoanNumber()
{
    return 'SLN-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6));
}

// ============================================================================
// LOAN CRUD OPERATIONS
// ============================================================================

/**
 * Create a new loan application
 */
function createLoanApplication($data)
{
    global $conn;

    $loanNumber = isset($data['loan_number']) ? $data['loan_number'] : generateLoanNumber();
    $userId = intval($data['user_id']);
    $caseId = !empty($data['case_id']) ? intval($data['case_id']) : 'NULL';

    $loanType = mysqli_real_escape_string($conn, $data['loan_type']);
    $loanAmount = floatval($data['loan_amount_requested']);
    $currency = mysqli_real_escape_string($conn, $data['currency'] ?? 'NGN');
    $purpose = isset($data['purpose']) ? mysqli_real_escape_string($conn, $data['purpose']) : '';
    $programName = isset($data['program_name']) ? mysqli_real_escape_string($conn, $data['program_name']) : '';
    $institutionName = isset($data['institution_name']) ? mysqli_real_escape_string($conn, $data['institution_name']) : '';
    $courseDuration = !empty($data['course_duration']) ? intval($data['course_duration']) : 'NULL';
    $programStartDate = !empty($data['program_start_date']) ? "'" . mysqli_real_escape_string($conn, $data['program_start_date']) . "'" : 'NULL';
    $programEndDate = !empty($data['program_end_date']) ? "'" . mysqli_real_escape_string($conn, $data['program_end_date']) . "'" : 'NULL';

    // Personal Information
    $fullName = mysqli_real_escape_string($conn, $data['full_name']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    $phone = mysqli_real_escape_string($conn, $data['phone']);
    $dateOfBirth = !empty($data['date_of_birth']) ? "'" . mysqli_real_escape_string($conn, $data['date_of_birth']) . "'" : 'NULL';
    $nationality = isset($data['nationality']) ? mysqli_real_escape_string($conn, $data['nationality']) : 'NULL';
    $address = isset($data['address']) ? mysqli_real_escape_string($conn, $data['address']) : '';
    $city = isset($data['city']) ? mysqli_real_escape_string($conn, $data['city']) : '';
    $state = isset($data['state']) ? mysqli_real_escape_string($conn, $data['state']) : '';
    $country = mysqli_real_escape_string($conn, $data['country'] ?? 'Nigeria');

    // Employment/Financial Information
    $employmentStatus = mysqli_real_escape_string($conn, $data['employment_status'] ?? 'student');
    $employerName = isset($data['employer_name']) ? mysqli_real_escape_string($conn, $data['employer_name']) : 'NULL';
    $monthlyIncome = !empty($data['monthly_income']) ? floatval($data['monthly_income']) : 'NULL';
    $incomeSource = isset($data['income_source']) ? mysqli_real_escape_string($conn, $data['income_source']) : 'NULL';
    $hasCollateral = !empty($data['has_collateral']) ? 1 : 0;
    $collateralType = isset($data['collateral_type']) ? mysqli_real_escape_string($conn, $data['collateral_type']) : 'NULL';
    $collateralValue = !empty($data['collateral_value']) ? floatval($data['collateral_value']) : 'NULL';
    $hasGuarantor = !empty($data['has_guarantor']) ? 1 : 0;
    $guarantorName = isset($data['guarantor_name']) ? mysqli_real_escape_string($conn, $data['guarantor_name']) : 'NULL';
    $guarantorEmail = isset($data['guarantor_email']) ? mysqli_real_escape_string($conn, $data['guarantor_email']) : 'NULL';
    $guarantorPhone = isset($data['guarantor_phone']) ? mysqli_real_escape_string($conn, $data['guarantor_phone']) : 'NULL';
    $guarantorRelationship = isset($data['guarantor_relationship']) ? mysqli_real_escape_string($conn, $data['guarantor_relationship']) : 'NULL';
    $guarantorAddress = isset($data['guarantor_address']) ? mysqli_real_escape_string($conn, $data['guarantor_address']) : 'NULL';

    // Bank Account Details for Disbursement
    $bankName = isset($data['bank_name']) ? mysqli_real_escape_string($conn, $data['bank_name']) : 'NULL';
    $accountNumber = isset($data['account_number']) ? mysqli_real_escape_string($conn, $data['account_number']) : 'NULL';
    $accountName = isset($data['account_name']) ? mysqli_real_escape_string($conn, $data['account_name']) : 'NULL';
    $accountType = isset($data['account_type']) ? mysqli_real_escape_string($conn, $data['account_type']) : 'savings';

    // Loan Terms
    $settings = getLoanSettings();
    $repaymentPeriod = !empty($data['repayment_period']) ? intval($data['repayment_period']) : $settings['max_duration'];
    $interestRate = !empty($data['interest_rate']) ? floatval($data['interest_rate']) : $settings['default_interest_rate'];
    $monthlyRepayment = calculateMonthlyRepayment($loanAmount, $interestRate, $repaymentPeriod);
    $gracePeriod = !empty($data['grace_period']) ? intval($data['grace_period']) : $settings['grace_period'];

    $sql = "INSERT INTO `student_loans` (
        `loan_number`, `user_id`, `case_id`, `loan_type`, `loan_amount_requested`, `currency`, `purpose`,
        `program_name`, `institution_name`, `course_duration`, `program_start_date`, `program_end_date`,
        `full_name`, `email`, `phone`, `date_of_birth`, `nationality`, `address`, `city`, `state`, `country`,
        `employment_status`, `employer_name`, `monthly_income`, `income_source`,
        `has_collateral`, `collateral_type`, `collateral_value`,
        `has_guarantor`, `guarantor_name`, `guarantor_email`, `guarantor_phone`, `guarantor_relationship`, `guarantor_address`,
        `bank_name`, `account_number`, `account_name`, `account_type`,
        `repayment_period`, `interest_rate`, `monthly_repayment`, `grace_period`,
        `status`, `submission_date`
    ) VALUES (
        '$loanNumber', '$userId', $caseId, '$loanType', '$loanAmount', '$currency', '$purpose',
        '$programName', '$institutionName', $courseDuration, $programStartDate, $programEndDate,
        '$fullName', '$email', '$phone', $dateOfBirth, '$nationality', '$address', '$city', '$state', '$country',
        '$employmentStatus', $employerName, $monthlyIncome, '$incomeSource',
        '$hasCollateral', $collateralType, $collateralValue,
        '$hasGuarantor', $guarantorName, $guarantorEmail, $guarantorPhone, $guarantorRelationship, $guarantorAddress,
        $bankName, $accountNumber, $accountName, '$accountType',
        '$repaymentPeriod', '$interestRate', '$monthlyRepayment', '$gracePeriod',
        'pending', NOW()
    )";

    if (mysqli_query($conn, $sql)) {
        $loanId = mysqli_insert_id($conn);

        // Log activity
        if (function_exists('logActivity')) {
            logActivity($userId, 'client', 'loan_application_created', 'loan', $loanId, "Loan application $loanNumber submitted");
        }

        return $loanId;
    }

    return false;
}

/**
 * Get loan by ID
 */
function getLoan($loanId)
{
    global $conn;

    $loanId = intval($loanId);
    $result = mysqli_query($conn, "
        SELECT sl.*,
               u.fullname as client_name, u.email as client_email, u.phone as client_phone,
               a.fullname as agent_name
        FROM `student_loans` sl
        LEFT JOIN `users` u ON sl.user_id = u.id
        LEFT JOIN `agents` a ON u.agent_id = a.id
        WHERE sl.id = '$loanId'
    ");

    return $result && mysqli_num_rows($result) > 0 ? mysqli_fetch_assoc($result) : null;
}

/**
 * Get loans by filter
 */
function getLoans($filters = [])
{
    global $conn;

    $where = ["1=1"];

    if (isset($filters['user_id'])) {
        $where[] = "sl.user_id = '" . intval($filters['user_id']) . "'";
    }
    if (isset($filters['status'])) {
        $where[] = "sl.status = '" . mysqli_real_escape_string($conn, $filters['status']) . "'";
    }
    if (isset($filters['loan_type'])) {
        $where[] = "sl.loan_type = '" . mysqli_real_escape_string($conn, $filters['loan_type']) . "'";
    }
    if (isset($filters['search'])) {
        $search = mysqli_real_escape_string($conn, $filters['search']);
        $where[] = "(sl.loan_number LIKE '%$search%' OR sl.full_name LIKE '%$search%' OR sl.program_name LIKE '%$search%')";
    }

    $orderBy = isset($filters['order_by']) ? mysqli_real_escape_string($conn, $filters['order_by']) : 'sl.created_at';
    $orderDir = isset($filters['order_dir']) && strtoupper($filters['order_dir']) == 'ASC' ? 'ASC' : 'DESC';
    $limit = isset($filters['limit']) ? intval($filters['limit']) : 50;
    $offset = isset($filters['offset']) ? intval($filters['offset']) : 0;

    $sql = "SELECT sl.*,
                   u.fullname as client_name, u.email as client_email,
                   a.fullname as agent_name
            FROM `student_loans` sl
            LEFT JOIN `users` u ON sl.user_id = u.id
            LEFT JOIN `agents` a ON u.agent_id = a.id
            WHERE " . implode(' AND ', $where) . "
            ORDER BY $orderBy $orderDir
            LIMIT $limit OFFSET $offset";

    $result = mysqli_query($conn, $sql);
    $loans = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $loans[] = $row;
        }
    }
    return $loans;
}

/**
 * Count loans by filter
 */
function countLoans($filters = [])
{
    global $conn;

    $where = ["1=1"];

    if (isset($filters['user_id'])) {
        $where[] = "user_id = '" . intval($filters['user_id']) . "'";
    }
    if (isset($filters['status'])) {
        $where[] = "status = '" . mysqli_real_escape_string($conn, $filters['status']) . "'";
    }

    $sql = "SELECT COUNT(*) as total FROM `student_loans` WHERE " . implode(' AND ', $where);
    $result = mysqli_query($conn, $sql);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        return intval($row['total']);
    }
    return 0;
}

/**
 * Update loan status
 */
function updateLoanStatus($loanId, $newStatus, $notes = '', $changedBy = null)
{
    global $conn;

    $loanId = intval($loanId);
    $newStatus = mysqli_real_escape_string($conn, $newStatus);
    $notesEscaped = mysqli_real_escape_string($conn, $notes);

    $setFields = ["`status` = '$newStatus'"];

    // Set timestamps based on status
    switch ($newStatus) {
        case 'under_review':
            $setFields[] = "`review_date` = NOW()";
            break;
        case 'approved':
            $setFields[] = "`approval_date` = NOW()";
            $setFields[] = "`approved_by` = " . ($changedBy ? intval($changedBy) : 'NULL');
            break;
        case 'disbursed':
            $setFields[] = "`disbursement_date` = NOW()";
            break;
    }

    if ($notes) {
        if ($newStatus == 'rejected') {
            $setFields[] = "`rejection_reason` = '$notesEscaped'";
        } else {
            $setFields[] = "`review_notes` = '$notesEscaped'";
        }
    }

    $sql = "UPDATE `student_loans` SET " . implode(', ', $setFields) . " WHERE `id` = '$loanId'";

    if (mysqli_query($conn, $sql)) {
        // Log activity
        $loan = getLoan($loanId);
        if ($loan && function_exists('logActivity')) {
            logActivity(
                $changedBy ?: $loan['user_id'],
                $changedBy ? 'admin' : 'client',
                'loan_status_updated',
                'loan',
                $loanId,
                "Loan {$loan['loan_number']} status changed to $newStatus"
            );
        }
        return true;
    }
    return false;
}

/**
 * Approve loan application
 */
function approveLoan($loanId, $approvedAmount, $approvedBy, $notes = '')
{
    global $conn;

    $loanId = intval($loanId);
    $approvedAmount = floatval($approvedAmount);
    $approvedBy = intval($approvedBy);
    $notesEscaped = mysqli_real_escape_string($conn, $notes);

    $loan = getLoan($loanId);
    if (!$loan) {
        return false;
    }

    // Calculate monthly repayment for approved amount
    $monthlyRepayment = calculateMonthlyRepayment(
        $approvedAmount,
        $loan['interest_rate'],
        $loan['repayment_period']
    );

    $sql = "UPDATE `student_loans` SET
        `status` = 'approved',
        `loan_amount_approved` = '$approvedAmount',
        `monthly_repayment` = '$monthlyRepayment',
        `remaining_balance` = '$approvedAmount',
        `approval_date` = NOW(),
        `approved_by` = '$approvedBy',
        `review_notes` = '$notesEscaped'
        WHERE `id` = '$loanId'";

    if (mysqli_query($conn, $sql)) {
        // Create notification for user
        if (function_exists('createNotification')) {
            createNotification(
                $loan['user_id'],
                'client',
                'Loan Application Approved',
                "Your loan application {$loan['loan_number']} has been approved for ₦" . number_format($approvedAmount),
                'success',
                '/user/my_loans.php?view=' . $loanId
            );
        }

        // Log activity
        if (function_exists('logActivity')) {
            logActivity($approvedBy, 'admin', 'loan_approved', 'loan', $loanId, "Loan {$loan['loan_number']} approved");
        }

        return true;
    }
    return false;
}

/**
 * Get loan status badge class
 */
function getLoanStatusBadge($status)
{
    $badges = [
        'draft' => 'bg-secondary',
        'pending' => 'bg-warning',
        'under_review' => 'bg-info',
        'approved' => 'bg-primary',
        'rejected' => 'bg-danger',
        'disbursed' => 'bg-purple',
        'repaying' => 'bg-success',
        'completed' => 'bg-green',
        'defaulted' => 'bg-dark'
    ];
    return isset($badges[$status]) ? $badges[$status] : 'bg-secondary';
}

/**
 * Get loan status label
 */
function getLoanStatusLabel($status)
{
    $labels = [
        'draft' => 'Draft',
        'pending' => 'Pending',
        'under_review' => 'Under Review',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'disbursed' => 'Disbursed',
        'repaying' => 'Repaying',
        'completed' => 'Completed',
        'defaulted' => 'Defaulted'
    ];
    return isset($labels[$status]) ? $labels[$status] : ucfirst(str_replace('_', ' ', $status));
}

/**
 * Get loan type label
 */
function getLoanTypeLabel($type)
{
    $labels = [
        'tuition' => 'Tuition Fee',
        'living_expenses' => 'Living Expenses',
        'full_program' => 'Full Program',
        'travel' => 'Travel Expenses',
        'other' => 'Other'
    ];
    return isset($labels[$type]) ? $labels[$type] : ucfirst(str_replace('_', ' ', $type));
}

// ============================================================================
// LOAN DOCUMENT MANAGEMENT
// ============================================================================

/**
 * Upload loan document
 */
function uploadLoanDocument($file, $loanId, $documentType)
{
    global $conn;

    $uploadDir = '../uploads/loans/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileName = 'loan_' . $loanId . '_' . time() . '_' . basename($file['name']);
    $filePath = $uploadDir . $fileName;

    // Check file type
    $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];
    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'message' => 'Invalid file type. Only PDF and images allowed.'];
    }

    // Check file size (5MB max)
    if ($file['size'] > 5 * 1024 * 1024) {
        return ['success' => false, 'message' => 'File too large. Maximum 5MB allowed.'];
    }

    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        $loanId = intval($loanId);
        $documentType = mysqli_real_escape_string($conn, $documentType);
        $fileSize = intval($file['size']);

        $sql = "INSERT INTO `loan_documents` (`loan_id`, `document_type`, `file_path`, `file_name`, `file_size`)
                VALUES ('$loanId', '$documentType', '$filePath', '$fileName', '$fileSize')";

        if (mysqli_query($conn, $sql)) {
            return ['success' => true, 'message' => 'Document uploaded successfully'];
        }
    }

    return ['success' => false, 'message' => 'Failed to upload document'];
}

/**
 * Get loan documents
 */
function getLoanDocuments($loanId)
{
    global $conn;

    $loanId = intval($loanId);
    $result = mysqli_query($conn, "SELECT * FROM `loan_documents` WHERE `loan_id` = '$loanId' ORDER BY `uploaded_at` DESC");

    $docs = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $docs[] = $row;
        }
    }
    return $docs;
}

/**
 * Get loan document type label
 */
function getLoanDocumentTypeLabel($type)
{
    $labels = [
        'identity_proof' => 'Identity Proof',
        'income_proof' => 'Proof of Income',
        'admission_letter' => 'Admission Letter',
        'fee_schedule' => 'Fee Schedule',
        'bank_statement' => 'Bank Statement',
        'guarantor_form' => 'Guarantor Form',
        'collateral_document' => 'Collateral Document',
        'other' => 'Other'
    ];
    return isset($labels[$type]) ? $labels[$type] : ucfirst(str_replace('_', ' ', $type));
}
