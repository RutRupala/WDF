<?php
session_start();
require_once __DIR__ . '/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS));
    $category = trim(filter_input(INPUT_POST, 'category', FILTER_SANITIZE_SPECIAL_CHARS));
    $date = trim($_POST['event_date'] ?? '');
    $venue = trim(filter_input(INPUT_POST, 'venue', FILTER_SANITIZE_SPECIAL_CHARS));
    $seats = intval($_POST['max_seats'] ?? 100);

    if (empty($title) || empty($category) || empty($date) || empty($venue)) {
        $errors[] = 'All required text fields must be filled.';
    }

    // Poster File Upload Validation
    $posterFilename = 'default_event.jpg';
    if (isset($_FILES['poster']) && $_FILES['poster']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['poster'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'File upload error code: ' . $file['error'];
        } else {
            // 1. Max File Size Validation (2MB)
            $maxBytes = 2 * 1024 * 1024;
            if ($file['size'] > $maxBytes) {
                $errors[] = 'Poster file size exceeds the 2MB limit.';
            }

            // 2. MIME Type Validation using finfo
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($file['tmp_name']);
            $allowedMimes = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp'
            ];

            if (!array_key_exists($mime, $allowedMimes)) {
                $errors[] = 'Invalid file type. Only JPEG, PNG, and WebP images are allowed.';
            } else {
                // 3. Secure Unique Renaming (Advanced Extension)
                $ext = $allowedMimes[$mime];
                $posterFilename = uniqid('event_', true) . '.' . $ext;
                $targetDir = __DIR__ . '/uploads/posters/';
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                $targetPath = $targetDir . $posterFilename;

                if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                    $errors[] = 'Failed to save uploaded poster file.';
                }
            }
        }
    }

    if (empty($errors) && $pdo) {
        try {
            $stmt = $pdo->prepare("INSERT INTO events (title, category, event_date, venue, max_seats, registered_seats, poster_image, status) VALUES (?, ?, ?, ?, ?, 0, ?, 'Upcoming')");
            $stmt->execute([$title, $category, $date, $venue, $seats, $posterFilename]);
            $_SESSION['flash_success'] = "Event '{$title}' created successfully with verified poster upload!";
        } catch (Exception $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }

    if (!empty($errors)) {
        $_SESSION['flash_errors'] = $errors;
    }
}

header('Location: index.php');
exit;
?>
