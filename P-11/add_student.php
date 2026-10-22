<?php
session_start();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pdo) {
    $rollNo = trim($_POST['roll_no'] ?? '');
    $name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $semester = intval($_POST['semester'] ?? 3);
    $cgpa = floatval($_POST['cgpa'] ?? 8.0);

    if ($rollNo && $name && $email) {
        try {
            $stmt = $pdo->prepare("INSERT INTO students (roll_no, full_name, email, mobile, department, semester, cgpa, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'Active')");
            $stmt->execute([$rollNo, $name, $email, $mobile, $department, $semester, $cgpa]);
            $_SESSION['flash_success'] = "Student '{$name}' successfully added!";
        } catch (Exception $e) {
            $_SESSION['flash_error'] = "Failed to add student: " . $e->getMessage();
        }
    } else {
        $_SESSION['flash_error'] = "Roll Number, Full Name, and Email are required.";
    }
}

header('Location: index.php');
exit;
?>
