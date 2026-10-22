<?php
require_once __DIR__ . '/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connectivity Test - Practical 8</title>
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
                    <span class="subtitle">ITUE203 Practical 08</span>
                </div>
            </a>
            <nav aria-label="Primary Site Navigation">
                <ul class="sh-nav-menu">
                    <li class="sh-nav-item"><a href="index.php">ER Model & Schema</a></li>
                    <li class="sh-nav-item active"><a href="test_db.php">PDO Connectivity Test</a></li>
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
                <a href="index.php">Practical 08</a>
                <span class="sep">/</span>
                <span class="current">PDO Connectivity</span>
            </nav>
            <span class="sh-practical-tag">Practical 08: MySQL & PDO</span>
        </div>
    </div>

    <main id="main-content" class="sh-main">
        <header style="margin-bottom: 2rem;">
            <h1 class="text-gradient">MySQL Database Connectivity Diagnostics</h1>
            <p class="lead-text">Testing PDO driver connection, prepared statement safety, and inspecting live table status.</p>
        </header>

        <?php if ($pdo): ?>
            <div class="alert alert-success">
                <span>✅ <strong>PDO Connection Established Successfully!</strong> Connected to <code>studenthub_db</code> on port 3306 with UTF-8mb4 character set.</span>
            </div>

            <div class="grid grid-3" style="margin-bottom: 2rem;">
                <?php
                $tables = ['users', 'students', 'events', 'registrations'];
                foreach ($tables as $tbl):
                    try {
                        $stmt = $pdo->query("SELECT COUNT(*) AS total FROM `{$tbl}`");
                        $count = $stmt->fetch()['total'];
                    } catch (Exception $e) {
                        $count = 'Table not initialized yet';
                    }
                ?>
                <div class="card">
                    <div class="card-header">
                        <h3>Table: <code><?= $tbl ?></code></h3>
                        <span class="badge badge-success">Online</span>
                    </div>
                    <p style="font-size: 1.8rem; font-weight: 700; margin: 10px 0;"><?= $count ?> <small style="font-size: 0.9rem; font-weight: 400; color: var(--text-muted);">records</small></p>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <div>
                    <strong>MySQL Server Status: Standby</strong>
                    <p style="margin-top: 6px;">Could not connect to MySQL server. If XAMPP is running, import <code>schema.sql</code> via phpMyAdmin to create the database.</p>
                    <p style="font-size: 0.82rem; color: var(--text-muted); margin-top: 4px;">Detail: <?= htmlspecialchars($db_error ?? 'Connection offline') ?></p>
                </div>
            </div>
        <?php endif; ?>

        <section class="card">
            <header class="card-header">
                <h2>Import Database Instructions</h2>
                <a href="schema.sql" class="btn btn-sm btn-outline" download>Download schema.sql</a>
            </header>
            <p style="color: var(--text-secondary); margin-bottom: 12px;">Run this command in terminal or import via phpMyAdmin (<code>http://localhost/phpmyadmin</code>):</p>
            <pre style="background: var(--bg-subtle); padding: 14px; border-radius: var(--radius-md); font-family: var(--font-mono); font-size: 0.88rem; overflow-x: auto;">mysql -u root -p < P-8/schema.sql</pre>
        </section>
    </main>

    <footer class="sh-footer">
        <div class="sh-footer-inner">
            <div><strong>StudentHub Portal</strong> — CHARUSAT FTE | ITUE203 Practical 08</div>
            <ul class="sh-footer-links">
                <li><a href="index.php">Schema</a></li>
                <li><a href="../index.html">Practicals Hub</a></li>
            </ul>
        </div>
    </footer>

    <script src="../shared/js/common.js"></script>
</body>
</html>
