<?php
include "../config/config.php";
include "../config/case_helper.php";

$user = auth('user');

if (isset($_FILES['document']) && isset($_POST['case_id']) && isset($_POST['document_type'])) {
    $caseId = intval($_POST['case_id']);
    $case = getCase($caseId);

    if (!$case || $case['client_id'] != $user['id']) {
        echo "<script>alert('Access denied'); location.href = 'documents.php';</script>";
        exit;
    }

    $result = uploadDocument(
        $_FILES['document'],
        $user['id'],
        $_POST['document_type'],
        $caseId,
        'client',
        $user['id']
    );

    if ($result['success']) {
        logActivity($user['id'], 'client', 'document_uploaded', 'document', $result['document_id'],
            "Document uploaded for case {$case['case_number']}");
        echo "<script>alert('Document uploaded successfully'); location.href = 'documents.php';</script>";
    } else {
        echo "<script>alert('{$result['message']}'); location.href = 'documents.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request'); location.href = 'documents.php';</script>";
}
