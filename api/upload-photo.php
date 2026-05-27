<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Directory where photos will be stored
$target_dir = __DIR__ . '/../images/team/';
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true);
}

if (!isset($_FILES['photo'])) {
    echo json_encode(['status' => 'error', 'message' => 'No photo provided']);
    exit;
}

$file = $_FILES['photo'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

// Simple validation
$allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
if (!in_array($ext, $allowed)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid file type']);
    exit;
}

$new_filename = uniqid('team_') . '.' . $ext;
$target_file = $target_dir . $new_filename;

if (move_uploaded_file($file['tmp_name'], $target_file)) {
    echo json_encode(['status' => 'success', 'path' => '/images/team/' . $new_filename]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to upload photo']);
}
?>
