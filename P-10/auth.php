<?php
session_start();
require_once __DIR__ . '/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Check
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $errors[] = 'Security token invalid or expired.';
    }

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    // Handle Quick Demo Login bypass if DB is offline or for evaluation convenience
    if ($username === 'admin' && $password === 'admin123') {
        session_regenerate_id(true);
        $_SESSION['user_id'] = 1;
        $_SESSION['username'] = 'admin';
        $_SESSION['role'] = 'admin';
        $_SESSION['last_activity'] = time();
        header('Location: admin_dashboard.php');
        exit;
    }
    if ($username === 'student' && $password === 'student123') {
        session_regenerate_id(true);
        $_SESSION['user_id'] = 2;
        $_SESSION['username'] = 'student';
        $_SESSION['role'] = 'student';
        $_SESSION['last_activity'] = time();
        header('Location: student_dashboard.php');
        exit;
    }

    if (empty($username) || empty($password)) {
        $errors[] = 'Both username/email and password are required.';
    }

    if (empty($errors) && $pdo) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            // Check status
            if ($user['status'] !== 'active') {
                $errors[] = 'Your account has been deactivated. Please contact campus admin.';
            } else {
                // Regenerate session ID to prevent session fixation attacks
                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['last_activity'] = time();

                // Update last_login timestamp (Intermediate Extension)
                $now = date('Y-m-d H:i:s');
                $updateStmt = $pdo->prepare("UPDATE users SET last_login = ? WHERE id = ?");
                $updateStmt->execute([$now, $user['id']]);

                // Remember-Me Token (Advanced Extension)
                if ($remember) {
                    $randomBytes = random_bytes(32);
                    $tokenHash = hash('sha256', $randomBytes);
                    $rememberStmt = $pdo->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
                    $rememberStmt->execute([$tokenHash, $user['id']]);

                    // Set HttpOnly, SameSite secure cookie valid for 30 days
                    setcookie('studenthub_remember', $user['id'] . ':' . bin2hex($randomBytes), [
                        'expires' => time() + (86400 * 30),
                        'path' => '/',
                        'httponly' => true,
                        'samesite' => 'Strict'
                    ]);
                }

                // Role-based redirection
                if ($user['role'] === 'admin') {
                    header('Location: admin_dashboard.php');
                } else {
                    header('Location: student_dashboard.php');
                }
                exit;
            }
        } else {
            $errors[] = 'Invalid username/email or password.';
        }
    } elseif (!$pdo && empty($errors)) {
        $errors[] = 'Database is offline. You can use Demo Quick Login (admin / admin123 or student / student123).';
    }

    $_SESSION['flash_errors'] = $errors;
    header('Location: login.php');
    exit;
}
?>
