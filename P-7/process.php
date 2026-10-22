<?php
session_start();

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. CSRF Token Validation (Advanced Extension)
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $errors[] = 'Invalid or expired CSRF security token.';
    }

    // 2. Input Sanitization
    $fullname = trim(filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_SPECIAL_CHARS));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $mobile = trim(filter_input(INPUT_POST, 'mobile', FILTER_SANITIZE_NUMBER_INT));
    $course = trim(filter_input(INPUT_POST, 'course', FILTER_SANITIZE_SPECIAL_CHARS));

    // 3. Server-Side Validations
    if (empty($fullname) || !preg_match("/^[A-Za-z\s]{3,50}$/", $fullname)) {
        $errors[] = 'Full name must contain only letters and spaces (min 3 characters).';
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please provide a valid email address.';
    }

    if (empty($mobile) || !preg_match("/^[6-9]\d{9}$/", $mobile)) {
        $errors[] = 'Mobile number must be a 10-digit Indian number starting with 6-9.';
    }

    if (empty($course)) {
        $errors[] = 'Please select a valid academic course.';
    }

    // 4. File Storage (CSV & JSON)
    if (empty($errors)) {
        $dataDir = __DIR__ . '/data';
        if (!is_dir($dataDir)) {
            mkdir($dataDir, 0777, true);
        }

        $timestamp = date('Y-m-d H:i:s');

        // CSV File Writing with Safe Locking
        $csvFile = $dataDir . '/registrations.csv';
        $writeHeader = !file_exists($csvFile) || filesize($csvFile) === 0;
        $fp = fopen($csvFile, 'a');
        if ($fp && flock($fp, LOCK_EX)) {
            if ($writeHeader) {
                fputcsv($fp, ['Full Name', 'Email', 'Mobile', 'Course', 'Timestamp']);
            }
            fputcsv($fp, [$fullname, $email, $mobile, $course, $timestamp]);
            flock($fp, LOCK_UN);
            fclose($fp);
        }

        // JSON File Writing with Safe Locking
        $jsonFile = $dataDir . '/registrations.json';
        $currentJson = [];
        if (file_exists($jsonFile)) {
            $jsonContent = file_get_contents($jsonFile);
            $decoded = json_decode($jsonContent, true);
            if (is_array($decoded)) $currentJson = $decoded;
        }

        $currentJson[] = [
            'fullname' => $fullname,
            'email' => $email,
            'mobile' => $mobile,
            'course' => $course,
            'timestamp' => $timestamp
        ];

        file_put_contents($jsonFile, json_encode($currentJson, JSON_PRETTY_PRINT), LOCK_EX);

        $_SESSION['flash_success'] = 'Registration successfully recorded in both CSV and JSON storage!';
        header('Location: records.php');
        exit;
    } else {
        $_SESSION['flash_errors'] = $errors;
        header('Location: index.php');
        exit;
    }
}
?>
