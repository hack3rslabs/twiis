<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if (!isset($_SESSION['twiis_admin']) || $_SESSION['twiis_admin'] !== true) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$entityId = $input['entity_id'] ?? null;
$entityType = $input['entity_type'] ?? null; // 'lead' or 'ticket'
$status = $input['status'] ?? null;

if (!$entityId || !$entityType || !$status) {
    echo json_encode(['status' => 'error', 'message' => 'entity_id, entity_type, and status are required']);
    exit;
}

$allowedStatuses = ['New', 'Open', 'In Progress', 'Contacted', 'Resolved', 'Closed'];
if (!in_array($status, $allowedStatuses)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid status']);
    exit;
}

try {
    $pdo = new PDO('sqlite:../data/twiis.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $table = $entityType === 'lead' ? 'leads' : 'tickets';
    $stmt = $pdo->prepare("UPDATE $table SET status = ? WHERE id = ?");
    $stmt->execute([$status, $entityId]);
    
    echo json_encode(['status' => 'success', 'message' => ucfirst($entityType) . ' status updated to ' . $status]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update status']);
}
?>
