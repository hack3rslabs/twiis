<?php
session_start();
if (!isset($_SESSION['twiis_admin']) || $_SESSION['twiis_admin'] !== true) { http_response_code(401); echo '[]'; exit; }
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
$file = '../data/blogs.json';
echo file_exists($file) ? file_get_contents($file) : '[]';
?>
