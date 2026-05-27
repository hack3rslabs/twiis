<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$ticketId = $_GET['id'] ?? null;
if (!$ticketId) {
    echo json_encode(['status' => 'error', 'message' => 'Ticket ID required']);
    exit;
}

try {
    $pdo = new PDO('sqlite:../data/twiis.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare('SELECT * FROM tickets WHERE id = ?');
    $stmt->execute([$ticketId]);
    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($ticket) {
        $rStmt = $pdo->prepare("SELECT * FROM responses WHERE entity_type='ticket' AND entity_id=? ORDER BY created_at DESC");
        $rStmt->execute([$ticketId]);
        $ticket['responses'] = $rStmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'ticket' => $ticket]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Ticket not found']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
