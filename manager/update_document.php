<?php
include "../config/config.php";
include "../config/case_helper.php";

$manager = auth('admin');

if (isset($_GET['id']) && isset($_GET['status'])) {
    $docId = intval($_GET['id']);
    $status = $_GET['status'];

    if (updateDocumentStatus($docId, $status)) {
        logActivity($manager['id'], 'admin', 'document_verified', 'document', $docId, "Document marked as $status");
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
