<?php
include "../config/config.php";
include "../config/case_helper.php";

$manager = auth('admin');

if (isset($_FILES['document']) && isset($_POST['case_id']) && isset($_POST['document_type'])) {
    $caseId = intval($_POST['case_id']);
    $case = getCase($caseId);

    if (!$case) {
        die("Case not found");
    }

    $result = uploadDocument(
        $_FILES['document'],
        $case['client_id'],
        $_POST['document_type'],
        $caseId,
        'admin',
        $manager['id']
    );

    if ($result['success']) {
        logActivity($manager['id'], 'admin', 'document_uploaded', 'document', $result['document_id'],
            "Document uploaded for case {$case['case_number']}");
        echo "<script>alert('Document uploaded successfully'); location.href = 'cases.php?view=$caseId';</script>";
    } else {
        echo "<script>alert('{$result['message']}'); location.href = 'cases.php?view=$caseId';</script>";
    }
} else {
    echo "<script>alert('Invalid request'); location.href = 'cases.php';</script>";
}
