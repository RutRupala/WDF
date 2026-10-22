<?php
session_start();
require_once __DIR__ . '/db.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id && $pdo) {
    try {
        $stmt = $pdo->prepare("SELECT poster_image FROM events WHERE id = ?");
        $stmt->execute([$id]);
        $event = $stmt->fetch();

        if ($event && $event['poster_image'] !== 'default_event.jpg') {
            $filePath = __DIR__ . '/uploads/posters/' . $event['poster_image'];
            if (file_exists($filePath)) @unlink($filePath);
        }

        $delStmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
        $delStmt->execute([$id]);
        $_SESSION['flash_success'] = "Event #{$id} and associated poster file removed successfully.";
    } catch (Exception $e) {
        $_SESSION['flash_errors'] = ["Failed to delete: " . $e->getMessage()];
    }
}

header('Location: index.php');
exit;
?>
