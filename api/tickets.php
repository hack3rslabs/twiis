<?php
session_start();
if (!isset($_SESSION['twiis_admin']) || $_SESSION['twiis_admin'] !== true) {
    http_response_code(401);
    echo json_encode([]);
    exit;
}

header('Content-Type: application/json');

try {
    $pdo = new PDO('sqlite:../data/twiis.sqlite');
    $stmt = $pdo->query("SELECT * FROM tickets ORDER BY id DESC");
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($tickets);
} catch (Exception $e) {
    echo json_encode([]);
}
?>
