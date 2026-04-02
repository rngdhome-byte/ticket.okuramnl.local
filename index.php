<?php
session_start();
date_default_timezone_set('Asia/Manila');

require_once 'config.php';
require_once 'includes/functions.php';

$timeout_duration = 1800; 
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $timeout_duration)) {
    session_unset();
    session_destroy();
    header("Location: index.php?timeout=1");
    exit;
}
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    session_regenerate_id(true); 
    $_SESSION['LAST_ACTIVITY'] = time();
    track_active_user();
}

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php" . (isset($_GET['timeout']) ? "?timeout=1" : ""));
    exit;
}

$error = "";
if (isset($_GET['timeout'])) { $error = "Session expired due to inactivity. Please log in again."; }
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    require_once 'includes/auth.php'; 
}

$settings_file = 'log_settings.json';
if(!file_exists($settings_file)) {
    file_put_contents($settings_file, json_encode(["employeeDepartments" => []], JSON_PRETTY_PRINT));
}
$settings_arr = json_decode(file_get_contents($settings_file), true);
$default_logo = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%238957e5'><path d='M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 2.18l7 3.12v4.7c0 4.67-3.13 8.9-7 10.02-3.87-1.12-7-5.35-7-10.02V6.3l7-3.12zm0 1.82a4 4 0 00-4 4c0 2.21 1.79 4 4 4s4-1.79 4-4-1.79-4-4-4zm0 2a2 2 0 110 4 2 2 0 010-4z'/></svg>";
$logo_img = (!empty($settings_arr['logoImage'])) ? $settings_arr['logoImage'] : $default_logo;
$bg_img = (!empty($settings_arr['bgImage'])) ? $settings_arr['bgImage'] : "";

// --- NEW: Custom Login Banner Logic ---
$default_banner = "Sign in with your standard network credentials to request support or view operations.";
$login_banner = (!empty($settings_arr['loginBanner'])) ? $settings_arr['loginBanner'] : $default_banner;

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    require_once 'views/login.php';
    exit;
}

$is_it_staff = isset($_SESSION['role']) && $_SESSION['role'] === 'IT';
$is_superadmin = isset($_SESSION['is_superadmin']) && $_SESSION['is_superadmin'];
$is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
$can_manage = $is_superadmin || $is_admin;

$current_user = $_SESSION['username'] ?? '';
$current_displayname = $_SESSION['displayname'] ?? $_SESSION['username'] ?? '';

require_once 'views/header.php';

if ($is_it_staff) {
    require_once 'views/dashboard_it.php';
} else {
    require_once 'views/dashboard_client.php';
}

require_once 'views/footer.php';
?>