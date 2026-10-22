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
    <title>Practical 9: Secure Registration with Password Hashing</title>
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
                    <li class="sh-nav-item active"><a href="index.php">Secure Registration</a></li>
                    <li class="sh-nav-item"><a href="users_list.php">Registered Users</a></li>
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
                <span class="current">Practical 09</span>
            </nav>
            <span class="sh-practical-tag">Practical 09: Password Hashing & Duplicate Check</span>
        </div>
    </div>

    <main id="main-content" class="sh-main">
        <header style="margin-bottom: 2rem;">
            <h1 class="text-gradient">Secure User Registration with Database Insert</h1>
            <p class="lead-text">Dual frontend and backend validation, duplicate username/email detection, secure password hashing via <code>password_hash(PASSWORD_BCRYPT)</code>, and transaction-safe MySQL insertion.</p>
        </header>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <strong>Registration Blocked:</strong>
                <ul style="margin-top: 6px; padding-left: 20px;">
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <section class="card" style="max-width: 680px; margin: 0 auto 3rem;">
            <header class="card-header">
                <h2>Student Account Creation</h2>
                <a href="users_list.php" class="btn btn-sm btn-outline">View Registered Database →</a>
            </header>

            <form action="process_register.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <div class="form-group">
                    <label for="username" class="form-label">Desired Username <span class="req">*</span></label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="e.g. rahul_patel" required>
                    <span class="form-hint">Unique identifier checked against MySQL records.</span>
                </div>

                <div class="form-group">
                    <label for="fullname" class="form-label">Full Name <span class="req">*</span></label>
                    <input type="text" id="fullname" name="fullname" class="form-control" placeholder="e.g. Rahul Patel" required>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Institutional Email <span class="req">*</span></label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="rahul@charusat.edu.in" required>
                    <span class="form-hint">Duplicate emails are actively prevented.</span>
                </div>

                <div class="form-group">
                    <label for="mobile" class="form-label">Mobile Number <span class="req">*</span></label>
                    <input type="tel" id="mobile" name="mobile" class="form-control" placeholder="9876543210" required>
                </div>

                <div class="form-group">
                    <label for="department" class="form-label">Academic Department <span class="req">*</span></label>
                    <select id="department" name="department" class="form-select" required>
                        <option value="Information Technology">Information Technology</option>
                        <option value="Computer Science">Computer Science & Engineering</option>
                        <option value="Computer Engineering">Computer Engineering</option>
                        <option value="Artificial Intelligence">Artificial Intelligence & ML</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password (BCRYPT Hashed) <span class="req">*</span></label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Minimum 8 characters" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="form-label">Confirm Password <span class="req">*</span></label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Re-type password" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Hash Password & Register (MySQL Insert)</button>
            </form>
        </section>
    </main>

    <footer class="sh-footer">
        <div class="sh-footer-inner">
            <div><strong>StudentHub Portal</strong> — CHARUSAT FTE | ITUE203 Practical 09</div>
            <ul class="sh-footer-links">
                <li><a href="users_list.php">Users List</a></li>
                <li><a href="../index.html">Practicals Hub</a></li>
            </ul>
        </div>
    </footer>

    <script src="../shared/js/common.js"></script>
</body>
</html>
