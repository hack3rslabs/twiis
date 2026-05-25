<?php
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['action']) && $data['action'] === 'logout') {
    session_destroy();
    echo json_encode(['status' => 'success']);
    exit;
}

if (!isset($data['username']) || !isset($data['password'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing credentials']);
    exit;
}

$username = $data['username'];
$password = $data['password'];

try {
    $pdo = new PDO('sqlite:../data/twiis.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare("SELECT password_hash FROM admin_users WHERE username = :u");
    $stmt->execute([':u' => $username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($password, $row['password_hash'])) {
        $_SESSION['twiis_admin'] = true;
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
}
?>
