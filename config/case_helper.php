<?php
/**
 * CASE MANAGEMENT HELPER FUNCTIONS
 * ApplyBoard Africa Ltd - Case Tracking System
 */

// ============================================================================
// CASE STAGES CONFIGURATION
// ============================================================================

/**
 * Get valid case stages by case type
 */
function getCaseStages($caseType = null) {
    $stages = [
        'study_abroad' => [
            'assessment' => 'Assessment',
            'options' => 'Options Provided',
            'application' => 'Application Submitted',
            'submission' => 'Submission Complete',
            'offer' => 'Offer Received',
            'visa' => 'Visa Processing',
            'travel' => 'Travel Arrangements',
            'completed' => 'Completed',
            'closed' => 'Closed'
        ],
        'visa_student' => [
            'assessment' => 'Assessment',
            'documents' => 'Document Collection',
            'submission' => 'Visa Submitted',
            'decision' => 'Decision Pending',
            'completed' => 'Visa Granted',
            'closed' => 'Closed'
        ],
        'visa_tourist' => [
            'assessment' => 'Assessment',
            'documents' => 'Document Collection',
            'submission' => 'Visa Submitted',
            'decision' => 'Decision Pending',
            'completed' => 'Visa Granted',
            'closed' => 'Closed'
        ],
        'visa_family' => [
            'assessment' => 'Assessment',
            'documents' => 'Document Collection',
            'submission' => 'Visa Submitted',
            'decision' => 'Decision Pending',
            'completed' => 'Visa Granted',
            'closed' => 'Closed'
        ],
        'travel_booking' => [
            'requirements' => 'Requirements',
            'booking' => 'Booking',
            'completed' => 'Completed',
            'closed' => 'Closed'
        ],
        'pilgrimage' => [
            'requirements' => 'Requirements',
            'booking' => 'Booking',
            'completed' => 'Completed',
            'closed' => 'Closed'
        ],
        'other' => [
            'assessment' => 'Assessment',
            'processing' => 'Processing',
            'completed' => 'Completed',
            'closed' => 'Closed'
        ]
    ];

    if ($caseType && isset($stages[$caseType])) {
        return $stages[$caseType];
    }
    return $stages;
}

/**
 * Get next stage for a case type
 */
function getNextStage($caseType, $currentStage) {
    $stages = getCaseStages($caseType);
    $stageKeys = array_keys($stages);
    $currentIndex = array_search($currentStage, $stageKeys);

    if ($currentIndex !== false && $currentIndex < count($stageKeys) - 1) {
        return $stageKeys[$currentIndex + 1];
    }
    return null;
}

/**
 * Get previous stage for a case type
 */
function getPreviousStage($caseType, $currentStage) {
    $stages = getCaseStages($caseType);
    $stageKeys = array_keys($stages);
    $currentIndex = array_search($currentStage, $stageKeys);

    if ($currentIndex !== false && $currentIndex > 0) {
        return $stageKeys[$currentIndex - 1];
    }
    return null;
}

/**
 * Get stage label
 */
function getStageLabel($caseType, $stage) {
    $stages = getCaseStages($caseType);
    return isset($stages[$stage]) ? $stages[$stage] : $stage;
}

// ============================================================================
// CASE CRUD OPERATIONS
// ============================================================================

/**
 * Generate unique case number
 */
function generateCaseNumber() {
    return 'CS-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6));
}

/**
 * Create a new case
 */
function createCase($data) {
    global $conn;

    $caseNumber = isset($data['case_number']) ? $data['case_number'] : generateCaseNumber();
    $clientId = mysqli_real_escape_string($conn, $data['client_id']);
    $agentId = mysqli_real_escape_string($conn, $data['agent_id']);
    $caseType = mysqli_real_escape_string($conn, $data['case_type']);
    $title = mysqli_real_escape_string($conn, $data['title']);
    $description = isset($data['description']) ? mysqli_real_escape_string($conn, $data['description']) : '';
    $destinationCountry = isset($data['destination_country']) ? mysqli_real_escape_string($conn, $data['destination_country']) : '';
    $institution = isset($data['institution']) ? mysqli_real_escape_string($conn, $data['institution']) : '';
    $program = isset($data['program']) ? mysqli_real_escape_string($conn, $data['program']) : '';
    $intake = isset($data['intake']) ? mysqli_real_escape_string($conn, $data['intake']) : '';
    $amount = isset($data['amount']) ? floatval($data['amount']) : 0;
    $assignedTo = isset($data['assigned_to']) ? intval($data['assigned_to']) : 'NULL';
    $notes = isset($data['notes']) ? mysqli_real_escape_string($conn, $data['notes']) : '';
    $createdBy = isset($data['created_by']) ? intval($data['created_by']) : 0;
    $createdByType = isset($data['created_by_type']) ? mysqli_real_escape_string($conn, $data['created_by_type']) : 'admin';

    $sql = "INSERT INTO `cases` (
        `case_number`, `client_id`, `agent_id`, `case_type`, `title`, `description`,
        `destination_country`, `institution`, `program`, `intake`, `amount`, `assigned_to`, `notes`
    ) VALUES (
        '$caseNumber', '$clientId', '$agentId', '$caseType', '$title', '$description',
        '$destinationCountry', '$institution', '$program', '$intake', '$amount', $assignedTo, '$notes'
    )";

    if (mysqli_query($conn, $sql)) {
        $caseId = mysqli_insert_id($conn);

        // Log activity
        logActivity($createdBy, $createdByType, 'case_created', 'case', $caseId, "Case $caseNumber created");

        // Create initial stage history
        logCaseStageChange($caseId, null, 'assessment', $createdBy, $createdByType, 'Initial stage');

        return $caseId;
    }
    return false;
}

/**
 * Update case stage
 */
function updateCaseStage($caseId, $newStage, $changedBy, $changedByType, $notes = '') {
    global $conn;

    $caseId = intval($caseId);
    $newStage = mysqli_real_escape_string($conn, $newStage);

    // Get current stage
    $result = mysqli_query($conn, "SELECT `stage`, `case_number`, `agent_id`, `client_id` FROM `cases` WHERE `id` = '$caseId'");
    if (!$result || mysqli_num_rows($result) == 0) {
        return false;
    }

    $case = mysqli_fetch_assoc($result);
    $currentStage = $case['stage'];

    if ($currentStage === $newStage) {
        return true; // Already at this stage
    }

    // Update stage
    $sql = "UPDATE `cases` SET `stage` = '$newStage', `updated_at` = NOW() WHERE `id` = '$caseId'";
    if (mysqli_query($conn, $sql)) {
        // Log stage change
        logCaseStageChange($caseId, $currentStage, $newStage, $changedBy, $changedByType, $notes);

        // Log activity
        logActivity($changedBy, $changedByType, 'case_stage_updated', 'case', $caseId,
            "Case {$case['case_number']} moved from $currentStage to $newStage");

        // Send notification to agent
        $agent = mysqli_fetch_assoc(mysqli_query($conn, "SELECT `id` FROM `agents` WHERE `id` = '{$case['agent_id']}'"));
        if ($agent) {
            createNotification($agent['id'], 'agent',
                'Case Stage Updated',
                "Case {$case['case_number']} has been moved to " . getStageLabelFromStage($newStage),
                'success',
                "/agent/cases.php?view=$caseId"
            );
        }

        // Send notification to client
        createNotification($case['client_id'], 'client',
            'Your Case Status Updated',
            "Your case {$case['case_number']} has been updated to: " . getStageLabelFromStage($newStage),
            'success',
            "/user/cases.php?view=$caseId"
        );

        // Check if case is completed and calculate commission
        if ($newStage === 'completed' || $newStage === 'closed') {
            calculateCommission($caseId);
        }

        return true;
    }
    return false;
}

/**
 * Get stage label directly (without case type)
 */
function getStageLabelFromStage($stage) {
    $labels = [
        'assessment' => 'Assessment',
        'options' => 'Options Provided',
        'application' => 'Application Submitted',
        'submission' => 'Submission Complete',
        'offer' => 'Offer Received',
        'visa' => 'Visa Processing',
        'travel' => 'Travel Arrangements',
        'requirements' => 'Requirements',
        'booking' => 'Booking',
        'processing' => 'Processing',
        'decision' => 'Decision Pending',
        'completed' => 'Completed',
        'closed' => 'Closed'
    ];
    return isset($labels[$stage]) ? $labels[$stage] : $stage;
}

/**
 * Log case stage change
 */
function logCaseStageChange($caseId, $fromStage, $toStage, $changedBy, $changedByType, $notes = '') {
    global $conn;

    $caseId = intval($caseId);
    $fromStage = $fromStage ? "'" . mysqli_real_escape_string($conn, $fromStage) . "'" : 'NULL';
    $toStage = mysqli_real_escape_string($conn, $toStage);
    $changedBy = intval($changedBy);
    $changedByType = mysqli_real_escape_string($conn, $changedByType);
    $notesEscaped = mysqli_real_escape_string($conn, $notes);

    $sql = "INSERT INTO `case_stages_history` (`case_id`, `from_stage`, `to_stage`, `changed_by`, `changed_by_type`, `notes`)
            VALUES ('$caseId', $fromStage, '$toStage', '$changedBy', '$changedByType', '$notesEscaped')";

    return mysqli_query($conn, $sql);
}

/**
 * Get case by ID
 */
function getCase($caseId) {
    global $conn;

    $caseId = intval($caseId);
    $result = mysqli_query($conn, "
        SELECT c.*,
               u.fullname as client_name, u.email as client_email, u.phone as client_phone,
               a.fullname as agent_name, a.agent_code, a.email as agent_email,
               assigned.email as assigned_to_email
        FROM `cases` c
        LEFT JOIN `users` u ON c.client_id = u.id
        LEFT JOIN `agents` a ON c.agent_id = a.id
        LEFT JOIN `admin` assigned ON c.assigned_to = assigned.id
        WHERE c.id = '$caseId'
    ");

    return $result && mysqli_num_rows($result) > 0 ? mysqli_fetch_assoc($result) : null;
}

/**
 * Get cases by filter
 */
function getCases($filters = []) {
    global $conn;

    $where = ["1=1"];
    $params = [];

    if (isset($filters['agent_id'])) {
        $where[] = "c.agent_id = '" . intval($filters['agent_id']) . "'";
    }
    if (isset($filters['client_id'])) {
        $where[] = "c.client_id = '" . intval($filters['client_id']) . "'";
    }
    if (isset($filters['case_type'])) {
        $where[] = "c.case_type = '" . mysqli_real_escape_string($conn, $filters['case_type']) . "'";
    }
    if (isset($filters['stage'])) {
        $where[] = "c.stage = '" . mysqli_real_escape_string($conn, $filters['stage']) . "'";
    }
    if (isset($filters['status'])) {
        $where[] = "c.status = '" . mysqli_real_escape_string($conn, $filters['status']) . "'";
    }
    if (isset($filters['assigned_to'])) {
        $where[] = "c.assigned_to = '" . intval($filters['assigned_to']) . "'";
    }
    if (isset($filters['search'])) {
        $search = mysqli_real_escape_string($conn, $filters['search']);
        $where[] = "(c.case_number LIKE '%$search%' OR c.title LIKE '%$search%')";
    }

    $orderBy = isset($filters['order_by']) ? mysqli_real_escape_string($conn, $filters['order_by']) : 'c.created_at';
    $orderDir = isset($filters['order_dir']) && strtoupper($filters['order_dir']) == 'ASC' ? 'ASC' : 'DESC';
    $limit = isset($filters['limit']) ? intval($filters['limit']) : 50;
    $offset = isset($filters['offset']) ? intval($filters['offset']) : 0;

    $sql = "SELECT c.*,
                   u.fullname as client_name, u.email as client_email,
                   a.fullname as agent_name, a.agent_code,
                   assigned.email as assigned_to_email
            FROM `cases` c
            LEFT JOIN `users` u ON c.client_id = u.id
            LEFT JOIN `agents` a ON c.agent_id = a.id
            LEFT JOIN `admin` assigned ON c.assigned_to = assigned.id
            WHERE " . implode(' AND ', $where) . "
            ORDER BY $orderBy $orderDir
            LIMIT $limit OFFSET $offset";

    $result = mysqli_query($conn, $sql);
    $cases = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $cases[] = $row;
        }
    }
    return $cases;
}

/**
 * Count cases by filter
 */
function countCases($filters = []) {
    global $conn;

    $where = ["1=1"];

    if (isset($filters['agent_id'])) {
        $where[] = "agent_id = '" . intval($filters['agent_id']) . "'";
    }
    if (isset($filters['client_id'])) {
        $where[] = "client_id = '" . intval($filters['client_id']) . "'";
    }
    if (isset($filters['case_type'])) {
        $where[] = "case_type = '" . mysqli_real_escape_string($conn, $filters['case_type']) . "'";
    }
    if (isset($filters['stage'])) {
        $where[] = "stage = '" . mysqli_real_escape_string($conn, $filters['stage']) . "'";
    }
    if (isset($filters['status'])) {
        $where[] = "status = '" . mysqli_real_escape_string($conn, $filters['status']) . "'";
    }

    $sql = "SELECT COUNT(*) as total FROM `cases` WHERE " . implode(' AND ', $where);
    $result = mysqli_query($conn, $sql);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return intval($row['total']);
    }
    return 0;
}

/**
 * Update case details
 */
function updateCase($caseId, $data) {
    global $conn;

    $caseId = intval($caseId);
    $set = [];

    $allowedFields = [
        'title', 'description', 'destination_country', 'institution', 'program',
        'intake', 'amount', 'commission_amount', 'commission_paid', 'status',
        'assigned_to', 'notes'
    ];

    foreach ($allowedFields as $field) {
        if (isset($data[$field])) {
            $value = mysqli_real_escape_string($conn, $data[$field]);
            $set[] = "`$field` = '$value'";
        }
    }

    if (empty($set)) {
        return false;
    }

    $sql = "UPDATE `cases` SET " . implode(', ', $set) . ", `updated_at` = NOW() WHERE `id` = '$caseId'";

    if (mysqli_query($conn, $sql)) {
        $case = getCase($caseId);
        logActivity(
            isset($data['updated_by']) ? $data['updated_by'] : 0,
            isset($data['updated_by_type']) ? $data['updated_by_type'] : 'admin',
            'case_updated',
            'case',
            $caseId,
            "Case {$case['case_number']} updated"
        );
        return true;
    }
    return false;
}

// ============================================================================
// DOCUMENT MANAGEMENT
// ============================================================================

/**
 * Upload document for a case
 */
function uploadDocument($file, $clientId, $documentType, $caseId = null, $uploadedBy = 'client', $uploadedById = 0) {
    global $conn;

    $uploadDir = '../uploads/documents/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileName = time() . '_' . basename($file['name']);
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
        $caseIdValue = $caseId ? "'" . intval($caseId) . "'" : 'NULL';
        $clientId = intval($clientId);
        $documentType = mysqli_real_escape_string($conn, $documentType);
        $uploadedBy = mysqli_real_escape_string($conn, $uploadedBy);
        $fileSize = intval($file['size']);

        $sql = "INSERT INTO `documents` (`case_id`, `client_id`, `document_type`, `file_path`, `file_name`, `file_size`, `uploaded_by`)
                VALUES ($caseIdValue, '$clientId', '$documentType', '$filePath', '$fileName', '$fileSize', '$uploadedBy')";

        if (mysqli_query($conn, $sql)) {
            $docId = mysqli_insert_id($conn);
            logActivity($uploadedById, $uploadedBy, 'document_uploaded', 'document', $docId,
                "Document $fileName uploaded");

            return ['success' => true, 'document_id' => $docId, 'message' => 'Document uploaded successfully'];
        }
    }

    return ['success' => false, 'message' => 'Failed to upload document'];
}

/**
 * Get documents for a case
 */
function getCaseDocuments($caseId) {
    global $conn;

    $caseId = intval($caseId);
    $result = mysqli_query($conn, "SELECT * FROM `documents` WHERE `case_id` = '$caseId' ORDER BY `created_at` DESC");

    $docs = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $docs[] = $row;
        }
    }
    return $docs;
}

/**
 * Update document status
 */
function updateDocumentStatus($docId, $status, $notes = '') {
    global $conn;

    $docId = intval($docId);
    $status = mysqli_real_escape_string($conn, $status);
    $notesEscaped = mysqli_real_escape_string($conn, $notes);

    $sql = "UPDATE `documents` SET `status` = '$status', `notes` = '$notesEscaped' WHERE `id` = '$docId'";
    return mysqli_query($conn, $sql);
}

// ============================================================================
// COMMISSION FUNCTIONS
// ============================================================================

/**
 * Calculate commission for a case
 */
function calculateCommission($caseId) {
    global $conn;

    $caseId = intval($caseId);
    $case = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `cases` WHERE `id` = '$caseId'"));

    if (!$case || $case['commission_amount'] > 0) {
        return false; // Already calculated or case not found
    }

    $agent = mysqli_fetch_assoc(mysqli_query($conn, "SELECT `commission_rate` FROM `agents` WHERE `id` = '{$case['agent_id']}'"));
    if (!$agent) {
        return false;
    }

    $rate = floatval($agent['commission_rate']);
    $caseAmount = floatval($case['amount']);

    if ($caseAmount > 0 && $rate > 0) {
        $commissionAmount = ($caseAmount * $rate) / 100;

        mysqli_query($conn, "UPDATE `cases` SET `commission_amount` = '$commissionAmount' WHERE `id` = '$caseId'");

        // Create commission record
        createCommissionRecord($case['agent_id'], $caseId, $case['client_id'], $commissionAmount, $rate, $caseAmount);

        return $commissionAmount;
    }

    return 0;
}

/**
 * Create commission record
 */
function createCommissionRecord($agentId, $caseId, $clientId, $amount, $rate, $caseAmount) {
    global $conn;

    $agentId = intval($agentId);
    $caseId = intval($caseId);
    $clientId = intval($clientId);

    $sql = "INSERT INTO `commissions` (`agent_id`, `case_id`, `client_id`, `commission_type`, `amount`, `rate_percentage`, `case_amount`, `status`)
            VALUES ('$agentId', '$caseId', '$clientId', 'case_completion', '$amount', '$rate', '$caseAmount', 'pending')";

    return mysqli_query($conn, $sql);
}

/**
 * Get pending commissions for an agent
 */
function getAgentCommissions($agentId, $status = null) {
    global $conn;

    $agentId = intval($agentId);
    $where = ["c.agent_id = '$agentId'"];

    if ($status) {
        $where[] = "c.status = '" . mysqli_real_escape_string($conn, $status) . "'";
    }

    $sql = "SELECT c.*, ca.case_number, ca.title as case_title
            FROM `commissions` c
            LEFT JOIN `cases` ca ON c.case_id = ca.id
            WHERE " . implode(' AND ', $where) . "
            ORDER BY c.created_at DESC";

    $result = mysqli_query($conn, $sql);
    $commissions = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $commissions[] = $row;
        }
    }
    return $commissions;
}

/**
 * Update commission status
 */
function updateCommissionStatus($commissionId, $status, $approvedBy = null, $paymentMethod = null, $paymentRef = null) {
    global $conn;

    $commissionId = intval($commissionId);
    $status = mysqli_real_escape_string($conn, $status);

    $set = ["`status` = '$status'"];

    if ($status === 'paid') {
        $set[] = "`paid_date` = NOW()";
    }
    if ($approvedBy) {
        $set[] = "`approved_by` = '" . intval($approvedBy) . "'";
        $set[] = "`approved_at` = NOW()";
    }
    if ($paymentMethod) {
        $set[] = "`payment_method` = '" . mysqli_real_escape_string($conn, $paymentMethod) . "'";
    }
    if ($paymentRef) {
        $set[] = "`payment_reference` = '" . mysqli_real_escape_string($conn, $paymentRef) . "'";
    }

    $sql = "UPDATE `commissions` SET " . implode(', ', $set) . " WHERE `id` = '$commissionId'";
    return mysqli_query($conn, $sql);
}

// ============================================================================
// ACTIVITY LOGGING
// ============================================================================

/**
 * Log activity
 */
function logActivity($userId, $userType, $action, $entityType = null, $entityId = null, $description = null) {
    global $conn;

    $userId = $userId ? "'" . intval($userId) . "'" : 'NULL';
    $userType = mysqli_real_escape_string($conn, $userType);
    $action = mysqli_real_escape_string($conn, $action);
    $entityType = $entityType ? "'" . mysqli_real_escape_string($conn, $entityType) . "'" : 'NULL';
    $entityId = $entityId ? "'" . intval($entityId) . "'" : 'NULL';
    $description = $description ? "'" . mysqli_real_escape_string($conn, $description) . "'" : 'NULL';
    $ip = isset($_SERVER['REMOTE_ADDR']) ? "'" . $_SERVER['REMOTE_ADDR'] . "'" : 'NULL';
    $ua = isset($_SERVER['HTTP_USER_AGENT']) ? "'" . mysqli_real_escape_string($conn, $_SERVER['HTTP_USER_AGENT']) . "'" : 'NULL';

    $sql = "INSERT INTO `activity_logs` (`user_id`, `user_type`, `action`, `entity_type`, `entity_id`, `description`, `ip_address`, `user_agent`)
            VALUES ($userId, '$userType', '$action', $entityType, $entityId, $description, $ip, $ua)";

    return @mysqli_query($conn, $sql);
}

/**
 * Get activity logs
 */
function getActivityLogs($filters = []) {
    global $conn;

    $where = ["1=1"];
    $limit = isset($filters['limit']) ? intval($filters['limit']) : 100;

    if (isset($filters['user_id'])) {
        $where[] = "user_id = '" . intval($filters['user_id']) . "'";
    }
    if (isset($filters['user_type'])) {
        $where[] = "user_type = '" . mysqli_real_escape_string($conn, $filters['user_type']) . "'";
    }
    if (isset($filters['entity_type'])) {
        $where[] = "entity_type = '" . mysqli_real_escape_string($conn, $filters['entity_type']) . "'";
    }
    if (isset($filters['entity_id'])) {
        $where[] = "entity_id = '" . intval($filters['entity_id']) . "'";
    }

    $sql = "SELECT * FROM `activity_logs` WHERE " . implode(' AND ', $where) . " ORDER BY `created_at` DESC LIMIT $limit";
    $result = mysqli_query($conn, $sql);

    $logs = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $logs[] = $row;
        }
    }
    return $logs;
}

// ============================================================================
// NOTIFICATIONS
// ============================================================================

/**
 * Create notification
 */
function createNotification($userId, $userType, $title, $message, $type = 'info', $link = null) {
    global $conn;

    $userId = intval($userId);
    $userType = mysqli_real_escape_string($conn, $userType);
    $title = mysqli_real_escape_string($conn, $title);
    $message = mysqli_real_escape_string($conn, $message);
    $type = mysqli_real_escape_string($conn, $type);
    $link = $link ? "'" . mysqli_real_escape_string($conn, $link) . "'" : 'NULL';

    $sql = "INSERT INTO `notifications` (`user_id`, `user_type`, `title`, `message`, `type`, `link`)
            VALUES ('$userId', '$userType', '$title', '$message', '$type', $link)";

    return mysqli_query($conn, $sql);
}

/**
 * Get notifications for user
 */
function getUserNotifications($userId, $userType, $unreadOnly = false) {
    global $conn;

    $userId = intval($userId);
    $userType = mysqli_real_escape_string($conn, $userType);
    $where = ["user_id = '$userId'", "user_type = '$userType'"];

    if ($unreadOnly) {
        $where[] = "is_read = 0";
    }

    $sql = "SELECT * FROM `notifications` WHERE " . implode(' AND ', $where) . " ORDER BY `created_at` DESC LIMIT 50";
    $result = mysqli_query($conn, $sql);

    $notifications = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $notifications[] = $row;
        }
    }
    return $notifications;
}

/**
 * Mark notification as read
 */
function markNotificationRead($notificationId) {
    global $conn;

    $notificationId = intval($notificationId);
    $sql = "UPDATE `notifications` SET `is_read` = 1 WHERE `id` = '$notificationId'";
    return mysqli_query($conn, $sql);
}

/**
 * Mark all notifications as read for user
 */
function markAllNotificationsRead($userId, $userType) {
    global $conn;

    $userId = intval($userId);
    $userType = mysqli_real_escape_string($conn, $userType);

    $sql = "UPDATE `notifications` SET `is_read` = 1 WHERE `user_id` = '$userId' AND `user_type` = '$userType'";
    return mysqli_query($conn, $sql);
}

/**
 * Get unread notification count
 */
function getUnreadNotificationCount($userId, $userType) {
    global $conn;

    $userId = intval($userId);
    $userType = mysqli_real_escape_string($conn, $userType);

    $sql = "SELECT COUNT(*) as count FROM `notifications` WHERE `user_id` = '$userId' AND `user_type` = '$userType' AND `is_read` = 0";
    $result = mysqli_query($conn, $sql);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        return intval($row['count']);
    }
    return 0;
}

// ============================================================================
// AGENT PERFORMANCE
// ============================================================================

/**
 * Calculate and update agent performance
 */
function updateAgentPerformance($agentId) {
    global $conn;

    $agentId = intval($agentId);

    // Get counts
    $totalReferrals = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT COUNT(*) as count FROM `users` WHERE `agent_id` = '$agentId'"))['count'];

    $activeCases = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT COUNT(*) as count FROM `cases` WHERE `agent_id` = '$agentId' AND `status` = 'active'"))['count'];

    $completedCases = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT COUNT(*) as count FROM `cases` WHERE `agent_id` = '$agentId' AND (`stage` = 'completed' OR `stage` = 'closed')"))['count'];

    $totalEarnings = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT COALESCE(SUM(amount), 0) as total FROM `commissions` WHERE `agent_id` = '$agentId' AND `status` = 'paid'"))['total'];

    // Calculate ratings (simplified logic)
    $ratingActivity = min(5.0, ($totalReferrals * 0.5) + ($activeCases * 0.3));
    $ratingQuality = min(5.0, ($completedCases * 0.5) + 1);
    $ratingOutcomes = min(5.0, ($completedCases > 0 ? 4 : 0) + ($totalEarnings > 0 ? 1 : 0));
    $ratingOverall = round(($ratingActivity + $ratingQuality + $ratingOutcomes) / 3, 2);

    // Determine tier
    $tier = 'bronze';
    if ($ratingOverall >= 4.5) $tier = 'platinum';
    elseif ($ratingOverall >= 3.5) $tier = 'gold';
    elseif ($ratingOverall >= 2.5) $tier = 'silver';

    // Check if performance record exists
    $existing = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `agent_performance` WHERE `agent_id` = '$agentId'"));

    if ($existing) {
        $sql = "UPDATE `agent_performance` SET
                `total_referrals` = '$totalReferrals',
                `active_cases` = '$activeCases',
                `completed_cases` = '$completedCases',
                `total_earnings` = '$totalEarnings',
                `rating_activity` = '$ratingActivity',
                `rating_quality` = '$ratingQuality',
                `rating_outcomes` = '$ratingOutcomes',
                `rating_overall` = '$ratingOverall',
                `tier` = '$tier',
                `last_calculated_at` = NOW()
                WHERE `agent_id` = '$agentId'";
    } else {
        $sql = "INSERT INTO `agent_performance`
                (`agent_id`, `total_referrals`, `active_cases`, `completed_cases`, `total_earnings`,
                 `rating_activity`, `rating_quality`, `rating_outcomes`, `rating_overall`, `tier`, `last_calculated_at`)
                VALUES
                ('$agentId', '$totalReferrals', '$activeCases', '$completedCases', '$totalEarnings',
                 '$ratingActivity', '$ratingQuality', '$ratingOutcomes', '$ratingOverall', '$tier', NOW())";
    }

    // Also update agents table
    mysqli_query($conn, "UPDATE `agents` SET `total_earned` = '$totalEarnings', `referral_count` = '$totalReferrals' WHERE `id` = '$agentId'");

    return mysqli_query($conn, $sql);
}

/**
 * Get agent performance
 */
function getAgentPerformance($agentId) {
    global $conn;

    $agentId = intval($agentId);
    $result = mysqli_query($conn, "SELECT * FROM `agent_performance` WHERE `agent_id` = '$agentId'");

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}
