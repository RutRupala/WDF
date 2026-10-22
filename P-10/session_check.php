<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$SESSION_TIMEOUT_SECONDS = 900; // 15 Minutes Inactivity Timeout

// 1. Authentication Check
if (empty($_SESSION['user_id'])) {
    $_SESSION['flash_errors'] = ['Access Denied: Please log in to view this protected page.'];
    header('Location: login.php');
    exit;
}

// 2. Inactivity Timeout Check
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $SESSION_TIMEOUT_SECONDS)) {
    session_unset();
    session_destroy();
    session_start();
    $_SESSION['flash_errors'] = ['Session timed out due to 15 minutes of inactivity. Please log in again.'];
    header('Location: login.php');
    exit;
}

// Refresh activity timestamp
$_SESSION['last_activity'] = time();

// Helper to enforce role access
function enforceRole($requiredRole) {
    if (empty($_SESSION['role']) || $_SESSION['role'] !== $requiredRole) {
        $_SESSION['flash_errors'] = ["Access Denied: You do not have '{$requiredRole}' privileges."];
        header('Location: login.php');
        exit;
    }
}
?>
