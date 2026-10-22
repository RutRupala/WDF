<?php
session_start();
require_once __DIR__ . '/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. CSRF Token Validation
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $errors[] = 'Security token invalid or expired.';
    }

    if (!$pdo) {
        $errors[] = 'Database is currently offline. Please ensure MySQL is started.';
    }

    $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS));
    $fullname = trim(filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_SPECIAL_CHARS));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $mobile = trim(filter_input(INPUT_POST, 'mobile', FILTER_SANITIZE_NUMBER_INT));
    $department = trim(filter_input(INPUT_POST, 'department', FILTER_SANITIZE_SPECIAL_CHARS));
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validations
    if (empty($username) || !preg_match("/^[a-zA-Z0-9_]{3,20}$/", $username)) {
        $errors[] = 'Username must be 3-20 alphanumeric characters or underscores.';
    }
    if (empty($fullname) || !preg_match("/^[A-Za-z\s]{3,50}$/", $fullname)) {
        $errors[] = 'Full name must contain only letters and spaces.';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required.';
    }
    if (empty($password) || strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long.';
    }
    if ($password !== $confirm_password) {
        $errors[] = 'Password and Confirm Password do not match.';
    }

    // 2. Duplicate Check
    if (empty($errors) && $pdo) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);
        if ($stmt->fetch()) {
            $errors[] = 'A student account with this Email or Username already exists!';
        }
    }

    // 3. Password Hashing & Insert with Prepared Statements
    if (empty($errors) && $pdo) {
        try {
            $pdo->beginTransaction();

            // Secure BCRYPT Hash
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

            // Insert User
            $userStmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role, status) VALUES (?, ?, ?, 'student', 'active')");
            $userStmt->execute([$username, $email, $hashedPassword]);
            $userId = $pdo->lastInsertId();

            // Insert Student Profile
            $rollNo = '24' . strtoupper(substr($department, 0, 2)) . str_pad($userId, 3, '0', STR_PAD_LEFT);
            $studentStmt = $pdo->prepare("INSERT INTO students (user_id, roll_no, full_name, email, mobile, department, semester, cgpa, status) VALUES (?, ?, ?, ?, ?, ?, 3, 8.50, 'Active')");
            $studentStmt->execute([$userId, $rollNo, $fullname, $email, $mobile, $department]);

            // Audit Log Entry (Advanced Extension)
            $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
            $auditStmt = $pdo->prepare("INSERT INTO audit_logs (user_id, action, entity, details, ip_address) VALUES (?, 'REGISTRATION', 'USERS', ?, ?)");
            $auditStmt->execute([$userId, "New student registration for user: {$username}", $ip]);

            $pdo->commit();

            $_SESSION['flash_success'] = "Account successfully registered for {$fullname}! Password securely hashed with BCRYPT.";
            header('Location: users_list.php');
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = 'Database transaction failed: ' . $e->getMessage();
        }
    }

    $_SESSION['flash_errors'] = $errors;
    header('Location: index.php');
    exit;
}
?>
