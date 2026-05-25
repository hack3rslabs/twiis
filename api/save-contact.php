<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['status' => 'error', 'message' => 'No data provided']);
    exit;
}

$file = '../data/contacts.json';
$current_data = [];

if (file_exists($file)) {
    $current_data = json_decode(file_get_contents($file), true);
}

if (isset($data['id'])) {
    foreach ($current_data as $i => $c) {
        if ($c['id'] == $data['id']) {
            $current_data[$i] = $data;
            break;
        }
    }
} else {
    $data['id'] = (string)time();
    $current_data[] = $data;
}

if (file_put_contents($file, json_encode($current_data, JSON_PRETTY_PRINT))) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to save contact']);
}
?>
