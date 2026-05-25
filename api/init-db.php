<?php
$db_file = '../data/twiis.sqlite';

try {
    $pdo = new PDO('sqlite:' . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create Admin table
    $pdo->exec("CREATE TABLE IF NOT EXISTS admin_users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        password_hash TEXT NOT NULL
    )");

    // Insert Default Admin (admin / twiis@2026)
    $hash = password_hash('twiis@2026', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT OR IGNORE INTO admin_users (username, password_hash) VALUES (:u, :p)");
    $stmt->execute([':u' => 'admin', ':p' => $hash]);

    // Create Leads table
    $pdo->exec("CREATE TABLE IF NOT EXISTS leads (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT,
        company TEXT,
        email TEXT,
        phone TEXT,
        service TEXT,
        message TEXT,
        status TEXT,
        source TEXT,
        timestamp TEXT
    )");

    // Create Tickets table
    $pdo->exec("CREATE TABLE IF NOT EXISTS tickets (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT,
        subject TEXT,
        description TEXT,
        status TEXT,
        source TEXT,
        timestamp TEXT
    )");

    // Create Clients table
    $pdo->exec("CREATE TABLE IF NOT EXISTS clients (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        url TEXT,
        description TEXT,
        category TEXT NOT NULL, -- 'venture' or 'client'
        client_type TEXT -- e.g., 'EdTech', 'E-Commerce'
    )");

    // Seed default clients/ventures if table is empty
    $count = $pdo->query("SELECT COUNT(*) FROM clients")->fetchColumn();
    if ($count == 0) {
        $seeds = [
            ['Techwell LMS', 'https://techwell.co.in', 'Advanced career training and placement platform.', 'venture', 'EdTech'],
            ['Elearnstack', 'https://elearnstack.com', 'Advanced corporate assessment and CBT engine.', 'venture', 'Assessment'],
            ['Kalavihaara', 'https://kalavihaara.com', 'Handicrafts, arts, and 3D designs online platform.', 'client', 'Creative Studio'],
            ['Elements', 'https://hindustanelements.com', 'Leader in floor guard and wall tile elevations.', 'client', 'E-Commerce'],
            ['Hibeam Infotech', 'https://hibeaminfotech.com', 'Enterprise IT and business ERP development partner.', 'client', 'IT Solutions'],
            ['Albright', 'https://albright.in', 'Overseas education and job consultancy portal.', 'client', 'Consultancy'],
            ['Samrudhi', '', 'Organic agriculture direct-to-city network.', 'client', 'Organic Store']
        ];
        $stmt = $pdo->prepare("INSERT INTO clients (name, url, description, category, client_type) VALUES (?, ?, ?, ?, ?)");
        foreach ($seeds as $row) {
            $stmt->execute($row);
        }
    }

    echo "Database initialized successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
