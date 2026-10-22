<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical 15: Final Full-Stack Integration - StudentHub Portal</title>
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
                    <span class="subtitle">ITUE203 Full-Stack Capstone</span>
                </div>
            </a>
            <nav aria-label="Primary Site Navigation">
                <ul class="sh-nav-menu">
                    <li class="sh-nav-item active"><a href="index.php">Integrated Portal</a></li>
                    <li class="sh-nav-item"><a href="dashboard.html">Dashboard</a></li>
                    <li class="sh-nav-item"><a href="events.html">Events Hub</a></li>
                    <li class="sh-nav-item"><a href="about.html">About</a></li>
                    <li class="sh-nav-item"><a href="faq.html">FAQ</a></li>
                </ul>
            </nav>
            <div class="sh-nav-actions">
                <a href="../P-10/login.php" class="btn btn-sm btn-outline">Log In</a>
                <a href="../P-9/index.php" class="btn btn-sm btn-primary">Register</a>
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
                <span class="current">Practical 15 (Final Integration)</span>
            </nav>
            <span class="sh-practical-tag">Practical 15: Capstone Full-Stack Integration</span>
        </div>
    </div>

    <main id="main-content" class="sh-main">
        <header style="margin-bottom: 2rem;">
            <span class="badge badge-success" style="margin-bottom: 8px;">Semester Capstone Milestone</span>
            <h1 class="text-gradient">StudentHub Full-Stack Integrated Portal</h1>
            <p class="lead-text">Consolidated, production-ready university academic portal uniting all 15 practical components into an interconnected web application with uniform design aesthetics.</p>
        </header>

        <!-- System Integration Matrix Cards -->
        <div class="grid grid-3" style="margin-bottom: 2.5rem;">
            <!-- Auth & Security -->
            <div class="card card-hover">
                <div class="card-header">
                    <h3>🔐 Authentication & RBAC</h3>
                    <span class="badge badge-primary">P-09 & P-10</span>
                </div>
                <p style="font-size: 0.88rem; color: var(--text-secondary); margin-bottom: 1rem;">
                    BCRYPT password hashing, session regeneration, timeout detection (15 mins), and persistent remember-me tokens.
                </p>
                <div class="card-footer">
                    <a href="../P-10/login.php" class="btn btn-sm btn-primary">Open Login Portal →</a>
                </div>
            </div>

            <!-- Student CRUD -->
            <div class="card card-hover">
                <div class="card-header">
                    <h3>👨‍🎓 Student Management</h3>
                    <span class="badge badge-info">P-11</span>
                </div>
                <p style="font-size: 0.88rem; color: var(--text-secondary); margin-bottom: 1rem;">
                    MySQL PDO-backed student directory with real-time multi-field search, department filtering, soft-delete, and CSV export.
                </p>
                <div class="card-footer">
                    <a href="../P-11/index.php" class="btn btn-sm btn-primary">Open Student CRUD →</a>
                </div>
            </div>

            <!-- Event Management -->
            <div class="card card-hover">
                <div class="card-header">
                    <h3>📅 Event Management</h3>
                    <span class="badge badge-warning">P-12 & P-13</span>
                </div>
                <p style="font-size: 0.88rem; color: var(--text-secondary); margin-bottom: 1rem;">
                    Server-validated poster uploads (MIME & size check), protected upload storage, and AJAX zero-reload event operations.
                </p>
                <div class="card-footer">
                    <a href="../P-13/index.html" class="btn btn-sm btn-primary">Launch AJAX Events →</a>
                </div>
            </div>

            <!-- Admin Center -->
            <div class="card card-hover">
                <div class="card-header">
                    <h3>🛡️ Admin Control Center</h3>
                    <span class="badge badge-danger">P-14</span>
                </div>
                <p style="font-size: 0.88rem; color: var(--text-secondary); margin-bottom: 1rem;">
                    Dynamic menu based on user role, monthly analytics charts, AJAX user status toggling, and immutable security audit logs.
                </p>
                <div class="card-footer">
                    <a href="../P-14/index.php" class="btn btn-sm btn-primary">Open Admin Panel →</a>
                </div>
            </div>

            <!-- Frontend Forms & Regex -->
            <div class="card card-hover">
                <div class="card-header">
                    <h3>📝 Validated Forms & Canvas</h3>
                    <span class="badge badge-success">P-05 & P-07</span>
                </div>
                <p style="font-size: 0.88rem; color: var(--text-secondary); margin-bottom: 1rem;">
                    Keystroke regex validation, visual password meter, custom Canvas CAPTCHA, and safe CSV/JSON server-side flat file storage.
                </p>
                <div class="card-footer">
                    <a href="../P-05/index.html" class="btn btn-sm btn-primary">Test Form Validation →</a>
                </div>
            </div>

            <!-- Dynamic Fetch API -->
            <div class="card card-hover">
                <div class="card-header">
                    <h3>⚡ Fetch API & Pagination</h3>
                    <span class="badge badge-neutral">P-06</span>
                </div>
                <p style="font-size: 0.88rem; color: var(--text-secondary); margin-bottom: 1rem;">
                    JSON dataset consumer across events, students, and FAQs with debounced live search and localStorage offline caching.
                </p>
                <div class="card-footer">
                    <a href="../P-06/index.html" class="btn btn-sm btn-primary">Open JSON Explorer →</a>
                </div>
            </div>
        </div>

        <!-- Documentation & Reports Card -->
        <section class="card">
            <header class="card-header">
                <h2>Laboratory Documentation & Verification Reports</h2>
            </header>
            <div class="grid grid-2">
                <div class="card" style="background: var(--bg-subtle);">
                    <h3>🚀 Deployment Instructions</h3>
                    <p style="font-size: 0.88rem; color: var(--text-muted); margin: 6px 0 12px;">Step-by-step Apache VirtualHost setup for <code>http://studenthub.local</code> and MySQL schema import.</p>
                    <a href="DEPLOYMENT_GUIDE.md" class="btn btn-sm btn-outline">Read DEPLOYMENT_GUIDE.md</a>
                </div>
                <div class="card" style="background: var(--bg-subtle);">
                    <h3>🧪 End-to-End Test Matrix</h3>
                    <p style="font-size: 0.88rem; color: var(--text-muted); margin: 6px 0 12px;">Functional test cases, responsive viewport verification, and web security audit checklist.</p>
                    <a href="TEST_REPORT.md" class="btn btn-sm btn-outline">Read TEST_REPORT.md</a>
                </div>
            </div>
        </section>
    </main>

    <footer class="sh-footer">
        <div class="sh-footer-inner">
            <div><strong>StudentHub Portal</strong> — CHARUSAT FTE | ITUE203 Practical 15</div>
            <ul class="sh-footer-links">
                <li><a href="../index.html">Master Practicals Hub</a></li>
                <li><a href="DEPLOYMENT_GUIDE.md">Deployment Guide</a></li>
                <li><a href="TEST_REPORT.md">Test Report</a></li>
            </ul>
        </div>
    </footer>

    <script src="../shared/js/common.js"></script>
</body>
</html>
