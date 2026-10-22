<?php
session_start();
require_once __DIR__ . '/db.php';

$successMsg = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_success']);

$users = [];
if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT u.id, u.username, u.email, u.password_hash, u.role, u.status, u.created_at, s.full_name, s.department 
                             FROM users u 
                             LEFT JOIN students s ON u.id = s.user_id 
                             ORDER BY u.id DESC");
        $users = $stmt->fetchAll();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Users - Practical 9</title>
    <link rel="stylesheet" href="../shared/css/common.css">
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <header class="sh-header">
        <div class="sh-nav-container">
            <a href="index.php" class="sh-brand">
                <div class="sh-brand-badge">SH</div>
                <div class="sh-brand-text">
                    <span class="title">StudentHub</span>
                    <span class="subtitle">ITUE203 Practical 09</span>
                </div>
            </a>
            <nav aria-label="Primary Site Navigation">
                <ul class="sh-nav-menu">
                    <li class="sh-nav-item"><a href="index.php">Secure Registration</a></li>
                    <li class="sh-nav-item active"><a href="users_list.php">Registered Users</a></li>
                    <li class="sh-nav-item"><a href="dashboard.html">Dashboard</a></li>
                </ul>
            </nav>
            <div class="sh-nav-actions">
                <button type="button" class="sh-theme-btn" id="theme-toggle-btn" aria-label="Toggle theme">🌙</button>
                <button type="button" class="sh-hamburger" aria-label="Toggle navigation menu">☰</button>
            </div>
        </div>
    </header>

    <div class="sh-breadcrumb-banner">
        <div class="sh-banner-inner">
            <nav class="sh-breadcrumbs" aria-label="Breadcrumb">
                <a href="../index.html">Practicals Hub</a>
                <span class="sep">/</span>
                <a href="index.php">Practical 09</a>
                <span class="sep">/</span>
                <span class="current">Registered Users</span>
            </nav>
            <span class="sh-practical-tag">Practical 09: Password Hashing Verification</span>
        </div>
    </div>

    <main id="main-content" class="sh-main">
        <header style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 14px;">
            <div>
                <h1 class="text-gradient">Registered Users & Password Hash Verification</h1>
                <p class="lead-text">Inspect stored MySQL rows showing BCRYPT salted cryptographic hashes. Plaintext passwords are never stored.</p>
            </div>
            <a href="index.php" class="btn btn-primary">+ Register Another Account</a>
        </header>

        <?php if ($successMsg): ?>
            <div class="alert alert-success">
                <span>✅ <?= htmlspecialchars($successMsg) ?></span>
            </div>
        <?php endif; ?>

        <div class="table-wrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Username</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">BCRYPT Password Hash</th>
                        <th scope="col">Role</th>
                        <th scope="col">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr><td colspan="7" style="text-align: center; color: var(--text-muted);">No user records found (or database is not yet connected).</td></tr>
                    <?php else: ?>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td><strong>#<?= $u['id'] ?></strong></td>
                                <td><code><?= htmlspecialchars($u['username']) ?></code></td>
                                <td><?= htmlspecialchars($u['full_name'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td><code style="font-size: 0.76rem; background: var(--bg-subtle); padding: 3px 6px; border-radius: 4px;"><?= htmlspecialchars(substr($u['password_hash'], 0, 30)) ?>...</code></td>
                                <td><span class="badge badge-primary"><?= htmlspecialchars($u['role']) ?></span></td>
                                <td style="font-size: 0.82rem; color: var(--text-muted);"><?= $u['created_at'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer class="sh-footer">
        <div class="sh-footer-inner">
            <div><strong>StudentHub Portal</strong> — CHARUSAT FTE | ITUE203 Practical 09</div>
            <ul class="sh-footer-links">
                <li><a href="index.php">Register</a></li>
                <li><a href="../index.html">Practicals Hub</a></li>
            </ul>
        </div>
    </footer>

    <script src="../shared/js/common.js"></script>
</body>
</html>
