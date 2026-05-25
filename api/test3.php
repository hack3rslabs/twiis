<?php
try {
    $db = new SQLite3('../data/twiis.db');
    $db->exec('CREATE TABLE test (id INT)');
    echo "SQLite3 works!";
} catch (Exception $e) {
    echo "SQLite3 error: " . $e->getMessage();
}
