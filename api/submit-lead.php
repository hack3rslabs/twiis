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

// 1. Rate Limiting Check (OWASP A04)
$client_ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
if (!SecurityHelper::checkRateLimit($client_ip, 5, 60)) {
    http_response_code(429);
    echo json_encode(['status' => 'error', 'message' => 'Too many requests. Please try again later.']);
    exit;
}

// 2. CSRF Validation (OWASP A08)
$csrf_token = $data['csrf_token'] ?? '';
if (!SecurityHelper::validateCSRFToken($csrf_token)) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Invalid or missing security token. Please refresh and try again.']);
    exit;
}

$status = 'New';
$source = 'Main Website';

// Check API Key for external integrations
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

    // 3. Encrypt PII fields (OWASP A02)
    $name_safe = htmlspecialchars(strip_tags($data['name'] ?? ''));
    $company_safe = htmlspecialchars(strip_tags($data['company'] ?? ''));
    $email_encrypted = SecurityHelper::encrypt(filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL));
    $phone_encrypted = SecurityHelper::encrypt(htmlspecialchars(strip_tags($data['phone'] ?? '')));
    $service_safe = htmlspecialchars(strip_tags($data['service'] ?? ''));
    $message_encrypted = SecurityHelper::encrypt(htmlspecialchars(strip_tags($data['message'] ?? '')));

    $stmt = $pdo->prepare("INSERT INTO leads (name, company, email, phone, service, message, status, source, timestamp) VALUES (:name, :company, :email, :phone, :service, :message, :status, :source, :timestamp)");
    $stmt->execute([
        ':name' => $name_safe,
        ':company' => $company_safe,
        ':email' => $email_encrypted,
        ':phone' => $phone_encrypted,
        ':service' => $service_safe,
        ':message' => $message_encrypted,
        ':status' => $status,
        ':source' => $source,
        ':timestamp' => date('c')
    ]);

    // Send Email Notification (using safe values, not encrypted)
    $to = "help@twiis.in";
    $subject = "New Lead Enquiry: " . $name_safe . " - " . $company_safe;
    $message = "New enquiry received:\n\nName: " . $name_safe . "\nCompany: " . $company_safe . "\nService: " . $service_safe . "\n\n(Contact details encrypted in database)";
    $mail_headers = "From: help@twiis.in";
    @mail($to, $subject, $message, $mail_headers);

    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Database error occurred']);
}
?>
