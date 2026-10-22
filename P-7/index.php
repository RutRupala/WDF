<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$errors = $_SESSION['flash_errors'] ?? [];
unset($_SESSION['flash_errors']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical 7: PHP Form Processing & File Storage</title>
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
                    <span class="subtitle">ITUE203 Practical 07</span>
                </div>
            </a>
            <nav aria-label="Primary Site Navigation">
                <ul class="sh-nav-menu">
                    <li class="sh-nav-item active"><a href="index.php">PHP Form Processor</a></li>
                    <li class="sh-nav-item"><a href="records.php">Stored Records Viewer</a></li>
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
                <span class="current">Practical 07</span>
            </nav>
            <span class="sh-practical-tag">Practical 07: PHP & CSV/JSON</span>
        </div>
    </div>

    <main id="main-content" class="sh-main">
        <header style="margin-bottom: 2rem;">
            <h1 class="text-gradient">PHP Form Processing with Server-Side Validation</h1>
            <p class="lead-text">Processes POST form submissions, performs input sanitization, validates CSRF tokens, and appends records safely to CSV and JSON storage files.</p>
        </header>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <strong>Please correct the following errors:</strong>
                <ul style="margin-top: 8px; padding-left: 20px;">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <section class="card" style="max-width: 680px; margin: 0 auto 3rem;">
            <header class="card-header">
                <h2>Student Registration (POST to PHP)</h2>
                <a href="records.php" class="btn btn-sm btn-outline">View CSV/JSON Records →</a>
            </header>

            <form action="process.php" method="POST">
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <div class="form-group">
                    <label for="fullname" class="form-label">Full Name <span class="req">*</span></label>
                    <input type="text" id="fullname" name="fullname" class="form-control" placeholder="e.g. Diya Joshi" required>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address <span class="req">*</span></label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="e.g. diya.j@charusat.edu.in" required>
                </div>

                <div class="form-group">
                    <label for="mobile" class="form-label">Mobile Number <span class="req">*</span></label>
                    <input type="tel" id="mobile" name="mobile" class="form-control" placeholder="e.g. 9825012345" required>
                </div>

                <div class="form-group">
                    <label for="course" class="form-label">Academic Program <span class="req">*</span></label>
                    <select id="course" name="course" class="form-select" required>
                        <option value="">-- Select Course --</option>
                        <option value="B.Tech IT">B.Tech Information Technology</option>
                        <option value="B.Tech CSE">B.Tech Computer Science & Engineering</option>
                        <option value="B.Tech CE">B.Tech Computer Engineering</option>
                        <option value="B.Tech AIML">B.Tech Artificial Intelligence & ML</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Process & Store Record (PHP)</button>
            </form>
        </section>
    </main>

    <footer class="sh-footer">
        <div class="sh-footer-inner">
            <div><strong>StudentHub Portal</strong> — CHARUSAT FTE | ITUE203 Practical 07</div>
            <ul class="sh-footer-links">
                <li><a href="records.php">Records</a></li>
                <li><a href="../index.html">Practicals Hub</a></li>
            </ul>
        </div>
    </footer>

    <script src="../shared/js/common.js"></script>
</body>
</html>
