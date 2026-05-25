<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    echo json_encode(['status' => 'error', 'message' => 'No data provided']);
    exit;
}

$status = 'Open';
$source = 'Main Website';

// Check API Key
$headers = getallheaders();
$api_key = $headers['X-API-Key'] ?? null;
if ($api_key) {
    $keys_file = '../data/api_keys.json';
    $valid_keys = file_exists($keys_file) ? json_decode(file_get_contents($keys_file), true) : [];
    if (!isset($valid_keys[$api_key])) {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Invalid API Key']);
        exit;
    }
    $source = 'External API: ' . $valid_keys[$api_key];
}

try {
    $pdo = new PDO('sqlite:../data/twiis.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare("INSERT INTO tickets (email, subject, description, status, source, timestamp) VALUES (:email, :subject, :description, :status, :source, :timestamp)");
    $stmt->execute([
        ':email' => $data['email'] ?? '',
        ':subject' => $data['subject'] ?? '',
        ':description' => $data['description'] ?? '',
        ':status' => $status,
        ':source' => $source,
        ':timestamp' => date('c')
    ]);

    // Send Email Notification
    $to = "help@twiis.in";
    $subject = "New Support Ticket: " . ($data['subject'] ?? '');
    $message = "New ticket received:\n\nSubject: " . ($data['subject'] ?? '') . "\nDescription: " . ($data['description'] ?? '') . "\nEmail: " . ($data['email'] ?? '');
    $headers = "From: help@twiis.in";
    @mail($to, $subject, $message, $headers);

    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
}
?>
