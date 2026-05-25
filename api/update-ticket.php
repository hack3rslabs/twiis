<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
$data = json_decode(file_get_contents('php://input'), true);
$file = '../data/tickets.json';
if ($data && file_exists($file)) {
    $tickets = json_decode(file_get_contents($file), true);
    foreach ($tickets as &$t) {
        if ($t['id'] == $data['id']) {
            $t['status'] = $data['status'];
            break;
        }
    }
    file_put_contents($file, json_encode($tickets, JSON_PRETTY_PRINT));
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
?>
