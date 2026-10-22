<?php
require_once __DIR__ . '/session_check.php';
enforceRole('admin'); // Restrict strictly to admin
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Practical 10</title>
    <link rel="stylesheet" href="../shared/css/common.css">
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <header class="sh-header">
        <div class="sh-nav-container">
            <a href="admin_dashboard.php" class="sh-brand">
                <div class="sh-brand-badge">SH</div>
                <div class="sh-brand-text">
                    <span class="title">StudentHub</span>
                    <span class="subtitle">Admin Session</span>
                </div>
            </a>
            <nav aria-label="Primary Site Navigation">
                <ul class="sh-nav-menu">
                    <li class="sh-nav-item active"><a href="admin_dashboard.php">Admin Center</a></li>
                    <li class="sh-nav-item"><a href="student_dashboard.php">Student View</a></li>
                </ul>
            </nav>
            <div class="sh-nav-actions">
                <span class="badge badge-warning">🛡️ Master Admin: <?= htmlspecialchars($_SESSION['username']) ?></span>
                <a href="logout.php" class="btn btn-sm btn-danger">Log Out</a>
                <button type="button" class="sh-theme-btn" id="theme-toggle-btn" aria-label="Toggle theme">🌙</button>
            </div>
        </div>
    </header>

    <div class="sh-breadcrumb-banner">
        <div class="sh-banner-inner">
            <nav class="sh-breadcrumbs" aria-label="Breadcrumb">
                <a href="../index.html">Practicals Hub</a>
                <span class="sep">/</span>
                <a href="login.php">Practical 10</a>
                <span class="sep">/</span>
                <span class="current">Admin Dashboard</span>
            </nav>
            <span class="sh-practical-tag">RBAC: Admin Role Authorized</span>
        </div>
    </div>

    <main id="main-content" class="sh-main">
        <header style="margin-bottom: 2rem;">
            <h1 class="text-gradient">Administrative Management Console</h1>
            <p class="lead-text">Role-Based Access Control verified. Student users cannot view this restricted dashboard.</p>
        </header>

        <div class="grid grid-4" style="margin-bottom: 2rem;">
            <div class="card">
                <span style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted);">ACTIVE SESSIONS</span>
                <h3 style="font-size: 1.8rem; margin: 4px 0;">18</h3>
                <span class="badge badge-success">0 Breaches</span>
            </div>
            <div class="card">
                <span style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted);">TOTAL USERS</span>
                <h3 style="font-size: 1.8rem; margin: 4px 0;">2,450</h3>
                <span class="badge badge-primary">MySQL DB</span>
            </div>
            <div class="card">
                <span style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted);">INACTIVITY TIMEOUT</span>
                <h3 style="font-size: 1.8rem; margin: 4px 0;">15 min</h3>
                <span class="badge badge-info">Auto-Logout</span>
            </div>
            <div class="card">
                <span style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted);">COOKIE TOKEN</span>
                <h3 style="font-size: 1.8rem; margin: 4px 0;">SHA-256</h3>
                <span class="badge badge-warning">Remember-Me</span>
            </div>
        </div>
    </main>

    <footer class="sh-footer">
        <div class="sh-footer-inner">
            <div><strong>StudentHub Portal</strong> — CHARUSAT FTE | ITUE203 Practical 10</div>
            <ul class="sh-footer-links">
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </footer>

    <script src="../shared/js/common.js"></script>
</body>
</html>
