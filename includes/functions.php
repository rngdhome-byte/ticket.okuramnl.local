<?php
// Upgrade DB Schema Safely
try {
    global $pdo;
    if($pdo) {
        $stmt = $pdo->prepare("SHOW COLUMNS FROM tickets LIKE 'assigned_to'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $pdo->exec("ALTER TABLE tickets ADD COLUMN assigned_to VARCHAR(100) AFTER logged_by");
            $pdo->exec("ALTER TABLE tickets ADD COLUMN client_attachment VARCHAR(255) AFTER message");
            $pdo->exec("ALTER TABLE tickets ADD COLUMN admin_response TEXT AFTER client_attachment");
            $pdo->exec("ALTER TABLE tickets ADD COLUMN admin_response_by VARCHAR(100) AFTER admin_response");
            $pdo->exec("ALTER TABLE tickets ADD COLUMN admin_response_at DATETIME AFTER admin_response_by");
            $pdo->exec("ALTER TABLE tickets ADD COLUMN admin_attachment VARCHAR(255) AFTER admin_response_at");
            $pdo->exec("ALTER TABLE tickets ADD COLUMN replies LONGTEXT AFTER admin_attachment");
        }
    }
} catch (PDOException $e) { error_log("Database patcher failed: " . $e->getMessage()); }

function saveBase64File($base64_string) {
    if (empty($base64_string)) return null;
    
    // Use absolute path for server writing
    $upload_dir_absolute = __DIR__ . '/../uploads';
    if (!file_exists($upload_dir_absolute)) {
        @mkdir($upload_dir_absolute, 0755, true);
    }
    
    $parts = explode(';', $base64_string);
    $mime_part = $parts[0];
    
    if (!isset($parts[1])) return null;
    $data_part = explode(',', $parts[1])[1] ?? '';
    $file_data = base64_decode($data_part);
    
    $ext = 'bin';
    if (strpos($mime_part, 'image/png') !== false) $ext = 'png';
    elseif (strpos($mime_part, 'image/jpeg') !== false) $ext = 'jpg';
    elseif (strpos($mime_part, 'application/pdf') !== false) $ext = 'pdf';
    elseif (strpos($mime_part, 'text/plain') !== false) $ext = 'txt';
    elseif (strpos($mime_part, 'wordprocessing') !== false) $ext = 'docx';
    elseif (strpos($mime_part, 'spreadsheet') !== false) $ext = 'xlsx';
    
    $filename = 'attach_' . time() . '_' . substr(uniqid(), -4) . '.' . $ext;
    
    // Save file using absolute path
    @file_put_contents($upload_dir_absolute . '/' . $filename, $file_data);
    
    // Return relative path to save in the database for web URLs
    return 'uploads/' . $filename;
}

function log_auth_attempt($username, $status) {
    $log_file = __DIR__ . '/../auth.log';
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown IP';
    $timestamp = date("Y-m-d H:i:s");
    
    // Cast username to string to prevent null deprecation errors
    $safe_username = (string)($username ?? 'Unknown');
    
    $entry = "[$timestamp] IP: " . str_pad($ip, 15) . " | User: " . str_pad($safe_username, 15) . " | Status: $status" . PHP_EOL;
    @file_put_contents($log_file, $entry, FILE_APPEND);
}

function track_active_user() {
    // Safely cast to string to guarantee we don't pass null as an array key
    $username = isset($_SESSION['username']) ? (string)$_SESSION['username'] : '';
    if ($username === '') return; 

    // Use absolute path from the includes directory to the root
    $active_file = __DIR__ . '/../active_users.json';
    
    if (!file_exists($active_file)) {
        @file_put_contents($active_file, json_encode([]));
    }
    
    $file_content = @file_get_contents($active_file);
    $active = $file_content ? json_decode($file_content, true) : [];
    
    // Ensure it's a valid array before assigning keys
    if (!is_array($active)) $active = [];

    $active[$username] = [
        'time' => time(),
        'displayname' => (string)($_SESSION['displayname'] ?? ''),
        'role' => (string)($_SESSION['role'] ?? ''),
        'department' => (string)($_SESSION['department'] ?? ''),
        'job_title' => (string)($_SESSION['job_title'] ?? '')
    ];
    
    @file_put_contents($active_file, json_encode($active));
}

// Global initialization check with absolute paths
$profiles_file = __DIR__ . '/../profiles.json';
if (!file_exists($profiles_file)) {
    @file_put_contents($profiles_file, json_encode([], JSON_PRETTY_PRINT));
}
?>