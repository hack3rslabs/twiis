<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1', 'root', '');
    echo 'MySQL connected!';
} catch (PDOException $e) {
    echo 'MySQL error: ' . $e->getMessage();
}
