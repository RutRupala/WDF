<?php
session_start();

// Unset all session variables
$_SESSION = [];

// Delete session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Clear Remember-Me cookie
if (isset($_COOKIE['studenthub_remember'])) {
    setcookie('studenthub_remember', '', time() - 3600, '/');
}

session_destroy();
session_start();
$_SESSION['flash_success'] = 'You have been safely logged out. Session destroyed.';
header('Location: login.php');
exit;
?>
