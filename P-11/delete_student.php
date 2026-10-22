<?php
session_start();
require_once __DIR__ . '/db.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'soft';

if ($id && $pdo) {
    try {
        if ($type === 'soft') {
            // Soft delete: Toggle status to Inactive (Advanced Extension)
            $stmt = $pdo->prepare("UPDATE students SET status = 'Inactive' WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['flash_success'] = "Student #{$id} has been soft-deleted (Status set to Inactive).";
        } else {
            // Hard permanent delete
            $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['flash_success'] = "Student #{$id} has been permanently deleted.";
        }
    } catch (Exception $e) {
        $_SESSION['flash_error'] = "Delete failed: " . $e->getMessage();
    }
}

header('Location: index.php');
exit;
?>
