<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Only allow authenticated admins
if (!isset($_SESSION['twiis_admin']) || $_SESSION['twiis_admin'] !== true) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$entityId = $input['entity_id'] ?? null;
$entityType = $input['entity_type'] ?? null; // 'lead' or 'ticket'
$message = $input['message'] ?? null;

if (!$entityId || !$entityType || !$message) {
    echo json_encode(['status' => 'error', 'message' => 'entity_id, entity_type, and message are required']);
    exit;
}

try {
    $pdo = new PDO('sqlite:../data/twiis.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // admin_id is hardcoded to 1 for now since we only have one default admin
    $stmt = $pdo->prepare('INSERT INTO responses (entity_id, entity_type, admin_id, message, created_at) VALUES (?, ?, 1, ?, datetime("now"))');
    $stmt->execute([$entityId, $entityType, $message]);
    
    echo json_encode(['status' => 'success', 'message' => 'Response saved successfully']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to save response']);
}
?>
