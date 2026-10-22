<?php
require_once __DIR__ . '/session_check.php';
// Allows student or admin to view
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - Practical 10</title>
    <link rel="stylesheet" href="../shared/css/common.css">
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <header class="sh-header">
        <div class="sh-nav-container">
            <a href="student_dashboard.php" class="sh-brand">
                <div class="sh-brand-badge">SH</div>
                <div class="sh-brand-text">
                    <span class="title">StudentHub</span>
                    <span class="subtitle">Student Session</span>
                </div>
            </a>
            <nav aria-label="Primary Site Navigation">
                <ul class="sh-nav-menu">
                    <li class="sh-nav-item active"><a href="student_dashboard.php">Dashboard</a></li>
                    <li class="sh-nav-item"><a href="events.html">Events</a></li>
                    <li class="sh-nav-item"><a href="profile.html">Profile</a></li>
                </ul>
            </nav>
            <div class="sh-nav-actions">
                <span class="badge badge-success">Logged in: <?= htmlspecialchars($_SESSION['username']) ?> (<?= htmlspecialchars($_SESSION['role']) ?>)</span>
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
                <span class="current">Student Dashboard</span>
            </nav>
            <span class="sh-practical-tag">Protected Student Session</span>
        </div>
    </div>

    <main id="main-content" class="sh-main">
        <header style="margin-bottom: 2rem;">
            <h1 class="text-gradient">Welcome to Student Portal, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
            <p class="lead-text">Session actively verified with automated 15-minute inactivity security timeout.</p>
        </header>

        <div class="grid grid-3" style="margin-bottom: 2rem;">
            <div class="card">
                <h3>Session Status</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 6px;">Role: <strong><?= strtoupper($_SESSION['role']) ?></strong></p>
                <p style="color: var(--text-muted); font-size: 0.85rem;">Last Activity: <?= date('H:i:s', $_SESSION['last_activity']) ?></p>
            </div>
            <div class="card">
                <h3>Registered Courses</h3>
                <p style="font-size: 1.8rem; font-weight: 700; margin: 4px 0;">6</p>
                <span class="badge badge-primary">Semester III ODD</span>
            </div>
            <div class="card">
                <h3>Campus Events</h3>
                <p style="font-size: 1.8rem; font-weight: 700; margin: 4px 0;">3 Confirmed</p>
                <span class="badge badge-success">Next on Nov 5</span>
            </div>
        </div>

        <section class="card">
            <header class="card-header">
                <h2>Role-Based Navigation Guard Demo</h2>
            </header>
            <p style="color: var(--text-secondary); line-height: 1.7;">
                Try attempting to open the <a href="admin_dashboard.php"><strong>Admin Dashboard</strong></a> directly. If logged in as student, the middleware immediately blocks access and redirects back to login with an unauthorized notification.
            </p>
        </section>
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
