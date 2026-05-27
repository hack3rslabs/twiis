<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'security.php';

echo json_encode([
    'status' => 'success',
    'csrf_token' => SecurityHelper::generateCSRFToken()
]);
?>
