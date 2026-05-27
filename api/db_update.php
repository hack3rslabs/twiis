<?php
try {
    $pdo = new PDO('sqlite:../data/twiis.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("ALTER TABLE tickets ADD COLUMN tracking_id TEXT");
    echo "Success";
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'duplicate column name') !== false) {
        echo "Already exists";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
?>
