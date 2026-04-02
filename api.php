<?php
session_start();
date_default_timezone_set('Asia/Manila');
require_once 'config.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    http_response_code(403); die(json_encode(['error' => 'Unauthorized']));
}

$active_file = 'active_users.json';
$settings_file = 'log_settings.json';
$profiles_file = 'profiles.json';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    
    if ($_GET['action'] === 'get_logs') {
        // Re-added the exact column aliases so JavaScript can read the JSON properly
        $sql = "SELECT id, ticket_number as ticketNumber, log_type as type, status, concern_category as concern, sub_category as subCategory, requestor_type as requestorType, department, job_title as jobTitle, requestor_specific as requestorSpecific, title, logged_by as user, assigned_to as assignedTo, message, client_attachment as clientAttachment, admin_response as adminResponse, admin_response_by as adminResponseBy, admin_response_at as adminResponseAt, admin_attachment as adminAttachment, replies, date, time, timestamp_val as timestamp FROM tickets";
        
        if ($_SESSION['role'] === 'End-User') {
            $stmt = $pdo->prepare($sql . " WHERE logged_by = :user ORDER BY timestamp_val DESC");
            $stmt->execute([':user' => $_SESSION['username']]);
        } else {
            $stmt = $pdo->query($sql . " ORDER BY timestamp_val DESC");
        }
        die(json_encode($stmt->fetchAll(PDO::FETCH_ASSOC)));
    }

    if ($_GET['action'] === 'get_online_users') {
        if ($_SESSION['role'] !== 'IT') die("Unauthorized");
        $active = json_decode(file_get_contents($active_file), true) ?: [];
        $online = [];
        $cutoff = time() - 300; 
        foreach ($active as $username => $data) {
            if ($data['time'] > $cutoff && $data['role'] === 'End-User') {
                $online[] = $data;
            }
        }
        die(json_encode(array_values($online)));
    }

    if ($_GET['action'] === 'get_auth_logs') {
        if (!isset($_SESSION['is_superadmin']) || $_SESSION['is_superadmin'] !== true) {
            http_response_code(403); die("Unauthorized");
        }
        if (file_exists('auth.log')) {
            $lines = file('auth.log', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            die(json_encode(['logs' => array_reverse($lines)]));
        }
        die(json_encode(['logs' => ["No activity recorded yet."]]));
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raw_data = file_get_contents('php://input');
    $json_data = json_decode($raw_data, true);
    
    if (isset($json_data['action'])) {
        if ($json_data['action'] === 'assign_ticket') {
            if ($_SESSION['role'] !== 'IT') { http_response_code(403); die("Forbidden"); }
            $stmt = $pdo->prepare("UPDATE tickets SET assigned_to = :assignedTo WHERE id = :id");
            $stmt->execute([':assignedTo' => $_SESSION['displayname'], ':id' => $json_data['id']]);
            die(json_encode(['status' => 'success']));
        }
        
        if ($json_data['action'] === 'save_reply') {
            $ticketId = $json_data['ticketId'];
            $replyMsg = $json_data['message'];
            $status = isset($json_data['status']) ? $json_data['status'] : null;
            
            $stmt = $pdo->prepare("SELECT replies FROM tickets WHERE id = :id");
            $stmt->execute([':id' => $ticketId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) die(json_encode(['error' => 'Ticket not found']));
            
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
            
            if ($_SESSION['role'] === 'IT' && $status) {
                $upd = $pdo->prepare("UPDATE tickets SET replies = :replies, status = :status WHERE id = :id");
                $upd->execute([':replies' => json_encode($replies), ':status' => $status, ':id' => $ticketId]);
            } else {
                $upd = $pdo->prepare("UPDATE tickets SET replies = :replies, status = 'Pending' WHERE id = :id");
                $upd->execute([':replies' => json_encode($replies), ':id' => $ticketId]);
            }
            die(json_encode(['status' => 'success']));
        }

        if ($json_data['action'] === 'save_log') {
            $log = $json_data['log'];

            if ($_SESSION['role'] === 'End-User') {
                $log['type'] = 'Support'; 
                $log['status'] = 'Pending'; 
                $log['user'] = $_SESSION['username']; 
                if (!empty($log['editId'])) {
                     http_response_code(403); die(json_encode(['error' => 'End-Users cannot edit tickets.']));
                }
            } else {
                $log['user'] = $_SESSION['username'];
            }

            if (!empty($log['editId']) && $_SESSION['role'] === 'IT') {
                $stmt = $pdo->prepare("UPDATE tickets SET log_type=:type, status=:status, concern_category=:concern, sub_category=:subCategory, requestor_type=:requestorType, department=:department, job_title=:jobTitle, requestor_specific=:requestorSpecific, title=:title, message=:message WHERE id=:id");
                $stmt->execute([
                    ':type' => $log['type'], ':status' => $log['status'], ':concern' => $log['concern'],
                    ':subCategory' => $log['subCategory'], ':requestorType' => $log['requestorType'],
                    ':department' => $log['department'], ':jobTitle' => $log['jobTitle'], ':requestorSpecific' => $log['requestorSpecific'],
                    ':title' => $log['title'], ':message' => $log['message'], ':id' => $log['editId']
                ]);
            } else {
                $clientAttachmentPath = null;
                if (!empty($log['clientAttachmentBase64'])) {
                    $clientAttachmentPath = saveBase64File($log['clientAttachmentBase64']);
                }

                $stmt = $pdo->prepare("INSERT INTO tickets (ticket_number, log_type, status, concern_category, sub_category, requestor_type, department, job_title, requestor_specific, title, logged_by, assigned_to, message, client_attachment, date, time, timestamp_val, replies) VALUES (:ticketNumber, :type, :status, :concern, :subCategory, :requestorType, :department, :jobTitle, :requestorSpecific, :title, :user, :assignedTo, :message, :clientAttachment, :date, :time, :timestamp, '[]')");
                $stmt->execute([
                    ':ticketNumber' => $log['ticketNumber'], ':type' => $log['type'], ':status' => $log['status'],
                    ':concern' => $log['concern'], ':subCategory' => $log['subCategory'],
                    ':requestorType' => $log['requestorType'], ':department' => $log['department'],
                    ':jobTitle' => $log['jobTitle'], ':requestorSpecific' => $log['requestorSpecific'], 
                    ':title' => $log['title'], ':user' => $log['user'], ':assignedTo' => null, ':message' => $log['message'],
                    ':clientAttachment' => $clientAttachmentPath, ':date' => $log['date'], ':time' => $log['time'], ':timestamp' => $log['timestamp']
                ]);
            }
            die(json_encode(['status' => 'success']));
        }

        if ($json_data['action'] === 'delete_log') {
            if (!isset($_SESSION['is_superadmin']) || $_SESSION['is_superadmin'] !== true) {
                http_response_code(403); die(json_encode(['error' => 'Forbidden']));
            }
            $stmt = $pdo->prepare("DELETE FROM tickets WHERE id = :id");
            $stmt->execute([':id' => $json_data['id']]);
            die(json_encode(['status' => 'success']));
        }

        if ($json_data['action'] === 'clear_logs') {
            if (!isset($_SESSION['is_superadmin']) || $_SESSION['is_superadmin'] !== true) {
                http_response_code(403); die(json_encode(['error' => 'Forbidden']));
            }
            $pdo->query("TRUNCATE TABLE tickets");
            die(json_encode(['status' => 'success']));
        }
        
        if ($json_data['action'] === 'save_settings') {
            if (!isset($_SESSION['can_manage']) || $_SESSION['can_manage'] !== true) {
                http_response_code(403); die(json_encode(['error' => 'Forbidden']));
            }
            file_put_contents($settings_file, json_encode($json_data['settings'], JSON_PRETTY_PRINT));
            die(json_encode(['status' => 'success']));
        }

        if ($json_data['action'] === 'save_profile') {
            $profiles = json_decode(file_get_contents($profiles_file), true);
            $profiles[$_SESSION['username']] = $json_data['image'];
            file_put_contents($profiles_file, json_encode($profiles, JSON_PRETTY_PRINT));
            die(json_encode(['status' => 'success']));
        }
    }
}
?>