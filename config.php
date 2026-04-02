<?php
// config.php
date_default_timezone_set('Asia/Manila');

// ==========================================
// DATABASE CONFIGURATION (aaPanel MySQL)
// ==========================================
$db_host = '127.0.0.1'; // Localhost since the DB is on the same aaPanel server
$db_name = 'sql_ticket_okuramnl_local';
$db_user = 'sql_ticket_okuramnl_local';
$db_pass = '36032bb11e9e4';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Auto-create the tickets table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS tickets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ticket_number VARCHAR(50),
        log_type VARCHAR(50),
        status VARCHAR(50),
        concern_category VARCHAR(100),
        sub_category VARCHAR(100),
        requestor_type VARCHAR(50),
        department VARCHAR(100),
        requestor_specific VARCHAR(100),
        title VARCHAR(255),
        logged_by VARCHAR(100),
        message TEXT,
        date VARCHAR(50),
        time VARCHAR(50),
        timestamp_val BIGINT
    )");

} catch(PDOException $e) {
    die("Database Connection Failed: Please check your aaPanel database credentials. Error: " . $e->getMessage());
}
?>