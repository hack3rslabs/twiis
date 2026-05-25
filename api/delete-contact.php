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

if (!$data || !isset($data['id'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing ID']);
    exit;
}

$file = '../data/contacts.json';

if (!file_exists($file)) {
    echo json_encode(['status' => 'success', 'message' => 'No contacts to delete from']);
    exit;
}

$contacts = json_decode(file_get_contents($file), true);
$new_contacts = [];
$deleted = false;

foreach ($contacts as $c) {
    if ($c['id'] == $data['id']) {
        $deleted = true;
        continue;
    }
    $new_contacts[] = $c;
}

if ($deleted) {
    if (file_put_contents($file, json_encode($new_contacts, JSON_PRETTY_PRINT))) {
        echo json_encode(['status' => 'success', 'message' => 'Contact deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save contacts file']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Contact not found']);
}
?>
