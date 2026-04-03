<?php
session_start();
date_default_timezone_set('Asia/Manila');
require_once 'config.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    http_response_code(403);
    die(json_encode(['error' => 'Unauthorized']));
}

$active_file = 'active_users.json';
$settings_file = 'log_settings.json';
$profiles_file = 'profiles.json';

function read_json_safe($file, $default = []) {
    if (!file_exists($file)) {
        file_put_contents($file, json_encode($default, JSON_PRETTY_PRINT));
        return $default;
    }
    $raw = file_get_contents($file);
    if ($raw === false || trim($raw) === '') {
        return $default;
    }
    $decoded = json_decode($raw, true);
    return is_array($decoded) ? $decoded : $default;
}

function write_json_safe($file, $data) {
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

function normalize_ticket_status($status) {
    $status = trim((string)$status);
    $closedStatuses = ['Resolved', 'Closed'];
    if (in_array($status, $closedStatuses, true)) {
        return 'Closed';
    }
    return 'Open';
}

// ========================================================================
// GET REQUESTS
// ========================================================================
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {

    if ($_GET['action'] === 'ping') {
        die(json_encode(['status' => 'ok']));
    }

    if ($_GET['action'] === 'get_logs') {
        $sql = "SELECT
                    id, ticket_number as ticketNumber, log_type as type,
                    CASE WHEN status IN ('Resolved', 'Closed') THEN 'Closed' ELSE 'Open' END as status,
                    concern_category as concern, sub_category as subCategory, requestor_type as requestorType,
                    department, job_title as jobTitle, requestor_specific as requestorSpecific,
                    title, logged_by as user, assigned_to as assignedTo, message,
                    client_attachment as clientAttachment, admin_response as adminResponse,
                    admin_response_by as adminResponseBy, admin_response_at as adminResponseAt,
                    admin_attachment as adminAttachment, replies, date, time, timestamp_val as timestamp
                FROM tickets";

        if ($_SESSION['role'] === 'End-User') {
            $stmt = $pdo->prepare($sql . " WHERE logged_by = :user ORDER BY timestamp_val DESC");
            $stmt->execute([':user' => $_SESSION['username']]);
        } else {
            $stmt = $pdo->query($sql . " ORDER BY timestamp_val DESC");
        }
        die(json_encode($stmt->fetchAll(PDO::FETCH_ASSOC)));
    }

    if ($_GET['action'] === 'get_online_users') {
        if ($_SESSION['role'] !== 'IT') {
            http_response_code(403);
            die(json_encode(['error' => 'Unauthorized']));
        }
        $active = read_json_safe($active_file, []);
        $online = [];
        $cutoff = time() - 300;

        foreach ($active as $username => $data) {
            if (isset($data['time'], $data['role']) && $data['time'] > $cutoff && $data['role'] === 'End-User') {
                $online[] = $data;
            }
        }
        die(json_encode(array_values($online)));
    }

    if ($_GET['action'] === 'get_auth_logs') {
        if (!isset($_SESSION['is_superadmin']) || $_SESSION['is_superadmin'] !== true) {
            http_response_code(403);
            die(json_encode(['error' => 'Unauthorized']));
        }
        if (file_exists('auth.log')) {
            $lines = file('auth.log', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            die(json_encode(['logs' => array_reverse($lines)]));
        }
        die(json_encode(['logs' => ["No activity recorded yet."]]));
    }
}

// ========================================================================
// POST REQUESTS
// ========================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // --- 1. HANDLE MULTIPART/FORM-DATA (PHYSICAL FILE UPLOADS) ---
    if (isset($_POST['action']) && $_POST['action'] === 'upload_file') {
        
        if (!isset($_SESSION['is_superadmin']) || $_SESSION['is_superadmin'] !== true) {
            http_response_code(403);
            die(json_encode(['status' => 'error', 'error' => 'Forbidden']));
        }

        // Catch if the file is too large for the server's post_max_size
        if (!isset($_FILES['file'])) {
            die(json_encode(['status' => 'error', 'error' => 'Server received nothing. The file likely exceeds the server post_max_size in php.ini.']));
        }

        // Catch specific PHP upload errors
        if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            die(json_encode(['status' => 'error', 'error' => 'PHP Upload Error Code: ' . $_FILES['file']['error']]));
        }

        $uploadDir = __DIR__ . '/uploads/'; 

        // Check if we can create the directory
        if (!is_dir($uploadDir)) {
            if (!@mkdir($uploadDir, 0775, true)) {
                die(json_encode(['status' => 'error', 'error' => 'Server lacks permission to create the /uploads/ folder. Please run the Ubuntu chown commands.']));
            }
        }

        // Check if the directory is writable
        if (!is_writable($uploadDir)) {
            die(json_encode(['status' => 'error', 'error' => 'The /uploads/ folder exists but is NOT writable by the web server. Please run the Ubuntu chmod/chown commands.']));
        }

        $fileTmpPath = $_FILES['file']['tmp_name'];
        $originalFileName = basename($_FILES['file']['name']);
        
        $cleanFileName = preg_replace("/[^a-zA-Z0-9.-]/", "_", $originalFileName);
        $newFileName = time() . '_' . $cleanFileName;
        $destinationPath = $uploadDir . $newFileName;
        
        $relativeUrl = 'uploads/' . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destinationPath)) {
            die(json_encode(['status' => 'success', 'url' => $relativeUrl]));
        } else {
            die(json_encode(['status' => 'error', 'error' => 'File received, but move_uploaded_file() failed to write it. Check permissions.']));
        }
    }

    // --- 2. HANDLE APPLICATION/JSON PAYLOADS ---
    $raw_data = file_get_contents('php://input');
    $json_data = json_decode($raw_data, true);

    if (isset($json_data['action'])) {

        if ($json_data['action'] === 'assign_ticket') {
            if ($_SESSION['role'] !== 'IT') {
                http_response_code(403);
                die(json_encode(['error' => 'Forbidden']));
            }
            $stmt = $pdo->prepare("UPDATE tickets SET assigned_to = :assignedTo WHERE id = :id");
            $stmt->execute([':assignedTo' => $_SESSION['displayname'], ':id' => $json_data['id']]);
            die(json_encode(['status' => 'success']));
        }

        if ($json_data['action'] === 'save_reply') {
            $ticketId = $json_data['ticketId'] ?? null;
            $replyMsg = $json_data['message'] ?? '';
            $status = isset($json_data['status']) ? normalize_ticket_status($json_data['status']) : null;

            $stmt = $pdo->prepare("SELECT replies FROM tickets WHERE id = :id");
            $stmt->execute([':id' => $ticketId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                http_response_code(404);
                die(json_encode(['error' => 'Ticket not found']));
            }

            $replies = $row['replies'] ? json_decode($row['replies'], true) : [];
            if (!is_array($replies)) $replies = [];

            $attachPath = null;
            if (!empty($json_data['attachmentBase64'])) {
                $attachPath = saveBase64File($json_data['attachmentBase64']);
            }

            $newReply = [
                'username' => $_SESSION['username'],
                'sender' => $_SESSION['displayname'],
                'role' => $_SESSION['role'],
                'message' => $replyMsg,
                'attachment' => $attachPath,
                'timestamp' => date('M j, Y, g:i A')
            ];

            $replies[] = $newReply;

            if ($_SESSION['role'] === 'IT') {
                $finalStatus = $status ? $status : 'Open';
                $upd = $pdo->prepare("UPDATE tickets SET replies = :replies, status = :status WHERE id = :id");
                $upd->execute([':replies' => json_encode($replies), ':status' => $finalStatus, ':id' => $ticketId]);
            } else {
                $upd = $pdo->prepare("UPDATE tickets SET replies = :replies, status = 'Open' WHERE id = :id");
                $upd->execute([':replies' => json_encode($replies), ':id' => $ticketId]);
            }
            die(json_encode(['status' => 'success']));
        }

        if ($json_data['action'] === 'save_log') {
            $log = $json_data['log'] ?? [];

            if ($_SESSION['role'] === 'End-User') {
                $log['type'] = 'Support';
                $log['status'] = 'Open';
                $log['user'] = $_SESSION['username'];
                if (!empty($log['editId'])) {
                    http_response_code(403);
                    die(json_encode(['error' => 'End-Users cannot edit tickets.']));
                }
            } else {
                $log['user'] = $_SESSION['username'];
                $log['status'] = normalize_ticket_status($log['status'] ?? 'Open');
            }

            if (!empty($log['editId']) && $_SESSION['role'] === 'IT') {
                $stmt = $pdo->prepare("UPDATE tickets SET log_type = :type, status = :status, concern_category = :concern, sub_category = :subCategory, requestor_type = :requestorType, department = :department, job_title = :jobTitle, requestor_specific = :requestorSpecific, title = :title, message = :message WHERE id = :id");
                $stmt->execute([
                    ':type' => $log['type'], ':status' => normalize_ticket_status($log['status']),
                    ':concern' => $log['concern'], ':subCategory' => $log['subCategory'],
                    ':requestorType' => $log['requestorType'], ':department' => $log['department'],
                    ':jobTitle' => $log['jobTitle'], ':requestorSpecific' => $log['requestorSpecific'],
                    ':title' => $log['title'], ':message' => $log['message'], ':id' => $log['editId']
                ]);
            } else {
                $clientAttachmentPath = null;
                if (!empty($log['clientAttachmentBase64'])) {
                    $clientAttachmentPath = saveBase64File($log['clientAttachmentBase64']);
                }
                $stmt = $pdo->prepare("INSERT INTO tickets (ticket_number, log_type, status, concern_category, sub_category, requestor_type, department, job_title, requestor_specific, title, logged_by, assigned_to, message, client_attachment, date, time, timestamp_val, replies) VALUES (:ticketNumber, :type, :status, :concern, :subCategory, :requestorType, :department, :jobTitle, :requestorSpecific, :title, :user, :assignedTo, :message, :clientAttachment, :date, :time, :timestamp, '[]')");
                $stmt->execute([
                    ':ticketNumber' => $log['ticketNumber'], ':type' => $log['type'],
                    ':status' => normalize_ticket_status($log['status'] ?? 'Open'), ':concern' => $log['concern'],
                    ':subCategory' => $log['subCategory'], ':requestorType' => $log['requestorType'],
                    ':department' => $log['department'], ':jobTitle' => $log['jobTitle'],
                    ':requestorSpecific' => $log['requestorSpecific'], ':title' => $log['title'],
                    ':user' => $log['user'], ':assignedTo' => null, ':message' => $log['message'],
                    ':clientAttachment' => $clientAttachmentPath, ':date' => $log['date'],
                    ':time' => $log['time'], ':timestamp' => $log['timestamp']
                ]);
            }
            die(json_encode(['status' => 'success']));
        }

        if ($json_data['action'] === 'delete_log') {
            if (!isset($_SESSION['is_superadmin']) || $_SESSION['is_superadmin'] !== true) {
                http_response_code(403);
                die(json_encode(['error' => 'Forbidden']));
            }
            $stmt = $pdo->prepare("DELETE FROM tickets WHERE id = :id");
            $stmt->execute([':id' => $json_data['id']]);
            die(json_encode(['status' => 'success']));
        }

        if ($json_data['action'] === 'clear_logs') {
            if (!isset($_SESSION['is_superadmin']) || $_SESSION['is_superadmin'] !== true) {
                http_response_code(403);
                die(json_encode(['error' => 'Forbidden']));
            }
            $pdo->query("TRUNCATE TABLE tickets");
            die(json_encode(['status' => 'success']));
        }

        if ($json_data['action'] === 'save_settings') {
            if (!isset($_SESSION['can_manage']) || $_SESSION['can_manage'] !== true) {
                http_response_code(403);
                die(json_encode(['error' => 'Forbidden']));
            }
            $existingSettings = read_json_safe($settings_file, ['employeeDepartments' => []]);
            $incomingSettings = isset($json_data['settings']) && is_array($json_data['settings']) ? $json_data['settings'] : [];
            $mergedSettings = array_merge($existingSettings, $incomingSettings);
            write_json_safe($settings_file, $mergedSettings);
            die(json_encode(['status' => 'success']));
        }

        if ($json_data['action'] === 'save_header_content') {
            if (!isset($_SESSION['is_superadmin']) || $_SESSION['is_superadmin'] !== true) {
                http_response_code(403);
                die(json_encode(['error' => 'Forbidden']));
            }
            $target = trim((string)($json_data['target'] ?? ''));
            $title = trim((string)($json_data['title'] ?? ''));
            $banner = trim((string)($json_data['banner'] ?? ''));

            if (!in_array($target, ['it', 'client'], true)) {
                http_response_code(400);
                die(json_encode(['error' => 'Invalid target']));
            }

            $settings = read_json_safe($settings_file, ['employeeDepartments' => []]);
            if ($target === 'it') {
                $settings['itHeaderTitle'] = $title;
                $settings['itHeaderBanner'] = $banner;
            } else {
                $settings['clientHeaderTitle'] = $title;
                $settings['clientHeaderBanner'] = $banner;
            }
            write_json_safe($settings_file, $settings);
            die(json_encode(['status' => 'success', 'target' => $target]));
        }

        if ($json_data['action'] === 'save_theme_banner') {
            if (!isset($_SESSION['is_superadmin']) || $_SESSION['is_superadmin'] !== true) {
                http_response_code(403);
                die(json_encode(['error' => 'Forbidden']));
            }
            $settings = read_json_safe($settings_file, ['employeeDepartments' => []]);
            $settings['loginBanner'] = trim((string)($json_data['banner'] ?? ''));
            write_json_safe($settings_file, $settings);
            die(json_encode(['status' => 'success', 'loginBanner' => $settings['loginBanner']]));
        }

        if ($json_data['action'] === 'save_theme_image') {
            if (!isset($_SESSION['is_superadmin']) || $_SESSION['is_superadmin'] !== true) {
                http_response_code(403);
                die(json_encode(['error' => 'Forbidden']));
            }
            $key = (string)($json_data['key'] ?? '');
            $allowedKeys = ['logoImage', 'bgImage'];

            if (!in_array($key, $allowedKeys, true)) {
                http_response_code(400);
                die(json_encode(['error' => 'Invalid image key']));
            }
            $settings = read_json_safe($settings_file, ['employeeDepartments' => []]);
            $settings[$key] = (string)($json_data['image'] ?? '');
            write_json_safe($settings_file, $settings);
            die(json_encode(['status' => 'success', 'key' => $key]));
        }

        if ($json_data['action'] === 'reset_theme_only') {
            if (!isset($_SESSION['is_superadmin']) || $_SESSION['is_superadmin'] !== true) {
                http_response_code(403);
                die(json_encode(['error' => 'Forbidden']));
            }
            $settings = read_json_safe($settings_file, ['employeeDepartments' => []]);
            $settings['logoImage'] = '';
            $settings['bgImage'] = '';
            $settings['loginBanner'] = '';
            write_json_safe($settings_file, $settings);
            die(json_encode(['status' => 'success']));
        }

        if ($json_data['action'] === 'save_profile') {
            $profiles = read_json_safe($profiles_file, []);
            $profiles[$_SESSION['username']] = $json_data['image'] ?? '';
            write_json_safe($profiles_file, $profiles);
            die(json_encode(['status' => 'success']));
        }
    }
}

http_response_code(400);
echo json_encode(['error' => 'Invalid request']);
?>