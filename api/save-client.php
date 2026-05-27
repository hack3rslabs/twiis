<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

session_start();
if (!isset($_SESSION['twiis_admin']) || $_SESSION['twiis_admin'] !== true) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['name']) || !isset($data['category'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    exit;
}

$db_file = '../data/twiis.sqlite';

try {
    $pdo = new PDO('sqlite:' . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($data['id']) && !empty($data['id'])) {
        // Update
        $stmt = $pdo->prepare("UPDATE clients SET name = :name, url = :url, description = :description, category = :category, client_type = :client_type WHERE id = :id");
        $stmt->execute([
            ':name' => htmlspecialchars(strip_tags($data['name'])),
            ':url' => filter_var($data['url'] ?? '', FILTER_SANITIZE_URL),
            ':description' => htmlspecialchars(strip_tags($data['description'] ?? '')),
            ':category' => htmlspecialchars(strip_tags($data['category'])),
            ':client_type' => htmlspecialchars(strip_tags($data['client_type'] ?? '')),
            ':id' => $data['id']
        ]);
        echo json_encode(['status' => 'success', 'message' => 'Client updated successfully']);
    } else {
        // Insert
        $stmt = $pdo->prepare("INSERT INTO clients (name, url, description, category, client_type) VALUES (:name, :url, :description, :category, :client_type)");
        $stmt->execute([
            ':name' => htmlspecialchars(strip_tags($data['name'])),
            ':url' => filter_var($data['url'] ?? '', FILTER_SANITIZE_URL),
            ':description' => htmlspecialchars(strip_tags($data['description'] ?? '')),
            ':category' => htmlspecialchars(strip_tags($data['category'])),
            ':client_type' => htmlspecialchars(strip_tags($data['client_type'] ?? ''))
        ]);
        echo json_encode(['status' => 'success', 'message' => 'Client added successfully']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    error_log($e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Database error occurred']);
}
?>
