<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'ID required']);
    exit;
}

$file = '../data/links.json';
if (file_exists($file)) {
    $current_data = json_decode(file_get_contents($file), true);
    $new_data = array_values(array_filter($current_data, function($l) use ($data) {
        return $l['id'] != $data['id'];
    }));
    file_put_contents($file, json_encode($new_data, JSON_PRETTY_PRINT));
    echo json_encode(['status' => 'success', 'message' => 'Link deleted']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'File not found']);
}
?>
