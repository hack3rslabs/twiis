<?php
session_start();
if (!isset($_SESSION['twiis_admin']) || $_SESSION['twiis_admin'] !== true) { http_response_code(401); echo '[]'; exit; }
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$file = '../data/contacts.json';
$current_data = [];

if (file_exists($file)) {
    $current_data = json_decode(file_get_contents($file), true);
}

echo json_encode($current_data);
?>
