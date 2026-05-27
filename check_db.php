<?php
$pdo = new PDO('sqlite:data/twiis.sqlite');
$stmt = $pdo->query('SELECT * FROM admin_users');
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($res);
if(count($res) === 0) {
    // Insert a default admin
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $pdo->exec("INSERT INTO admin_users (username, password_hash) VALUES ('admin', '$hash')");
    echo "Inserted default admin: admin / admin123\n";
}
?>
