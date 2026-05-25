<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['path']) || !isset($data['content'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    exit;
}

$file = '..' . $data['path'];

// Sanitize path to prevent directory traversal
$real_base = realpath('..');
$real_file = realpath($file);

if ($real_file && strpos($real_file, $real_base) === 0) {
    if (file_put_contents($real_file, $data['content'])) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save file']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Access denied']);
}
?>
