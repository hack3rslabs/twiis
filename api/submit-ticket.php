<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'security.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    echo json_encode(['status' => 'error', 'message' => 'No data provided']);
    exit;
}

// 1. Rate Limiting Check
$client_ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
if (!SecurityHelper::checkRateLimit($client_ip, 5, 60)) {
    http_response_code(429);
    echo json_encode(['status' => 'error', 'message' => 'Too many requests. Please try again later.']);
    exit;
}

// 2. CSRF Validation
$csrf_token = $data['csrf_token'] ?? '';
if (!SecurityHelper::validateCSRFToken($csrf_token)) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Invalid or missing CSRF token']);
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

// Generate secure tracking ID
$tracking_id = 'TKT-' . strtoupper(substr(hash('sha256', uniqid(rand(), true)), 0, 8));

try {
    $pdo = new PDO('sqlite:../data/twiis.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Auto-migrate schema to include tracking_id if it doesn't exist
    try {
        $pdo->exec("ALTER TABLE tickets ADD COLUMN tracking_id TEXT");
    } catch (Exception $e) { /* Column likely exists already */ }

    // 3. Encrypt Sensitive Fields
    $email_encrypted = SecurityHelper::encrypt(filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL));
    $description_encrypted = SecurityHelper::encrypt(htmlspecialchars(strip_tags($data['description'] ?? '')));
    $subject_safe = htmlspecialchars(strip_tags($data['subject'] ?? ''));

    $stmt = $pdo->prepare("INSERT INTO tickets (email, subject, description, status, source, timestamp, tracking_id) VALUES (:email, :subject, :description, :status, :source, :timestamp, :tracking_id)");
    $stmt->execute([
        ':email' => $email_encrypted,
        ':subject' => $subject_safe,
        ':description' => $description_encrypted,
        ':status' => $status,
        ':source' => $source,
        ':timestamp' => date('c'),
        ':tracking_id' => $tracking_id
    ]);

    // Send Email Notification
    $to = "help@twiis.in";
    $subject = "New Support Ticket [$tracking_id]: " . $subject_safe;
    $message = "New ticket received:\n\nTracking ID: $tracking_id\nSubject: $subject_safe\n\n(Details encrypted in database)";
    $headers = "From: help@twiis.in";
    @mail($to, $subject, $message, $headers);

    echo json_encode(['status' => 'success', 'tracking_id' => $tracking_id]);
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Database error occurred']);
}
?>
