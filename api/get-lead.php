<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$leadId = $_GET['id'] ?? null;
if (!$leadId) {
    echo json_encode(['status' => 'error', 'message' => 'Lead ID required']);
    exit;
}

try {
    $pdo = new PDO('sqlite:../data/twiis.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare('SELECT * FROM leads WHERE id = ?');
    $stmt->execute([$leadId]);
    $lead = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($lead) {
        $rStmt = $pdo->prepare("SELECT * FROM responses WHERE entity_type='lead' AND entity_id=? ORDER BY created_at DESC");
        $rStmt->execute([$leadId]);
        $lead['responses'] = $rStmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'lead' => $lead]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Lead not found']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
