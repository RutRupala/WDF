<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$errors = $_SESSION['flash_errors'] ?? [];
$success = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_errors'], $_SESSION['flash_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Login - Practical 10</title>
    <link rel="stylesheet" href="../shared/css/common.css">
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <header class="sh-header">
        <div class="sh-nav-container">
            <a href="login.php" class="sh-brand">
                <div class="sh-brand-badge">SH</div>
                <div class="sh-brand-text">
                    <span class="title">StudentHub</span>
                    <span class="subtitle">ITUE203 Practical 10</span>
                </div>
            </a>
            <nav aria-label="Primary Site Navigation">
                <ul class="sh-nav-menu">
                    <li class="sh-nav-item active"><a href="login.php">Login</a></li>
                    <li class="sh-nav-item"><a href="student_dashboard.php">Student Portal</a></li>
                    <li class="sh-nav-item"><a href="admin_dashboard.php">Admin Portal</a></li>
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
                <span class="current">Practical 10</span>
            </nav>
            <span class="sh-practical-tag">Practical 10: PHP Sessions & RBAC</span>
        </div>
    </div>

    <main id="main-content" class="sh-main">
        <header style="margin-bottom: 2rem; text-align: center;">
            <h1 class="text-gradient">Secure Login & Role-Based Access Control</h1>
            <p class="lead-text" style="max-width: 600px; margin: 8px auto 0;">Authenticates via <code>password_verify()</code>, regenerates session tokens, enforces 15-minute inactivity timeouts, and implements secure Remember-Me cookies.</p>
        </header>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger" style="max-width: 480px; margin: 0 auto 1.5rem;">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success" style="max-width: 480px; margin: 0 auto 1.5rem;">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <section class="card" style="max-width: 480px; margin: 0 auto 2rem;">
            <header class="card-header">
                <h2>Account Authentication</h2>
                <span class="badge badge-primary">PHP Sessions</span>
            </header>

            <form action="auth.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <div class="form-group">
                    <label for="username" class="form-label">Username or Email <span class="req">*</span></label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="admin or student" required>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password <span class="req">*</span></label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
                </div>

                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" id="remember" name="remember">
                        <span>Remember Me (Persistent Hash Token Cookie)</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Verify & Sign In</button>
            </form>

            <!-- Quick Demo Credentials Box -->
            <div class="card" style="background: var(--bg-subtle); margin-top: 20px; padding: 14px;">
                <h4 style="font-size: 0.88rem; margin-bottom: 6px;">⚡ Quick Test Credentials:</h4>
                <div style="font-size: 0.8rem; line-height: 1.6; color: var(--text-secondary);">
                    <div><strong>Admin:</strong> <code>admin</code> / <code>admin123</code> (Redirects to Admin Dashboard)</div>
                    <div><strong>Student:</strong> <code>student</code> / <code>student123</code> (Redirects to Student Dashboard)</div>
                </div>
            </div>
        </section>
    </main>

    <footer class="sh-footer">
        <div class="sh-footer-inner">
            <div><strong>StudentHub Portal</strong> — CHARUSAT FTE | ITUE203 Practical 10</div>
            <ul class="sh-footer-links">
                <li><a href="../index.html">Practicals Hub</a></li>
            </ul>
        </div>
    </footer>

    <script src="../shared/js/common.js"></script>
</body>
</html>
