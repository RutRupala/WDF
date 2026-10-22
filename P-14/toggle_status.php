<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$raw = file_get_contents('php://input');
$payload = json_decode($raw, true);

$userId = filter_var($payload['user_id'] ?? null, FILTER_VALIDATE_INT);
$newStatus = ($payload['status'] ?? '') === 'active' ? 'active' : 'inactive';

if ($userId && $pdo) {
    try {
        $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
        $stmt->execute([$newStatus, $userId]);

        // Record in audit_logs
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $audit = $pdo->prepare("INSERT INTO audit_logs (user_id, action, entity, details, ip_address) VALUES (1, 'STATUS_TOGGLE', 'USERS', ?, ?)");
        $audit->execute(["Toggled status for user #{$userId} to '{$newStatus}'", $ip]);

        echo json_encode(['status' => 'success', 'message' => "User #{$userId} status changed to {$newStatus}", 'new_status' => $newStatus]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// Fallback demo simulation
echo json_encode(['status' => 'success', 'message' => "User status toggled to {$newStatus} (Demo mode)", 'new_status' => $newStatus]);
exit;
?>
