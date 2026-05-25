<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
$data = json_decode(file_get_contents('php://input'), true);
$file = '../data/leads.json';
if ($data && file_exists($file)) {
    $leads = json_decode(file_get_contents($file), true);
    foreach ($leads as &$l) {
        if ($l['id'] == $data['id']) {
            $l['status'] = $data['status'];
            break;
        }
    }
    file_put_contents($file, json_encode($leads, JSON_PRETTY_PRINT));
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
?>
