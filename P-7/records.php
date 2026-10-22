<?php
session_start();
$successMsg = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_success']);

// Read CSV records
$csvRecords = [];
$csvFile = __DIR__ . '/data/registrations.csv';
if (file_exists($csvFile)) {
    if (($handle = fopen($csvFile, 'r')) !== FALSE) {
        $header = fgetcsv($handle);
        while (($data = fgetcsv($handle)) !== FALSE) {
            if (count($data) >= 5) {
                $csvRecords[] = $data;
            }
        }
        fclose($handle);
    }
}

// Read JSON records
$jsonRecords = [];
$jsonFile = __DIR__ . '/data/registrations.json';
if (file_exists($jsonFile)) {
    $content = file_get_contents($jsonFile);
    $decoded = json_decode($content, true);
    if (is_array($decoded)) {
        $jsonRecords = $decoded;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stored Records - Practical 7</title>
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
                    <li class="sh-nav-item"><a href="index.php">PHP Form Processor</a></li>
                    <li class="sh-nav-item active"><a href="records.php">Stored Records Viewer</a></li>
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
                <a href="index.php">Practical 07</a>
                <span class="sep">/</span>
                <span class="current">Stored Records</span>
            </nav>
            <span class="sh-practical-tag">Practical 07: CSV/JSON Viewer</span>
        </div>
    </div>

    <main id="main-content" class="sh-main">
        <header style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 14px;">
            <div>
                <h1 class="text-gradient">Stored Form Submissions (File Storage)</h1>
                <p class="lead-text">Viewing verified registrations persisted into server-side flat files (CSV & JSON format).</p>
            </div>
            <a href="index.php" class="btn btn-primary">+ Submit Another Record</a>
        </header>

        <?php if ($successMsg): ?>
            <div class="alert alert-success">
                <span>✅ <?= htmlspecialchars($successMsg) ?></span>
            </div>
        <?php endif; ?>

        <!-- CSV Table -->
        <section class="card" style="margin-bottom: 2rem;">
            <header class="card-header">
                <h2>1. Records Read from <code>registrations.csv</code> (<?= count($csvRecords) ?> entries)</h2>
                <a href="data/registrations.csv" class="btn btn-sm btn-outline" download>Download CSV</a>
            </header>
            <div class="table-wrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Full Name</th>
                            <th scope="col">Email Address</th>
                            <th scope="col">Mobile Number</th>
                            <th scope="col">Program</th>
                            <th scope="col">Logged Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($csvRecords)): ?>
                            <tr><td colspan="5" style="text-align: center; color: var(--text-muted);">No records found in CSV.</td></tr>
                        <?php else: ?>
                            <?php foreach ($csvRecords as $row): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($row[0]) ?></strong></td>
                                    <td><?= htmlspecialchars($row[1]) ?></td>
                                    <td><?= htmlspecialchars($row[2]) ?></td>
                                    <td><span class="badge badge-info"><?= htmlspecialchars($row[3]) ?></span></td>
                                    <td><code><?= htmlspecialchars($row[4]) ?></code></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- JSON Table -->
        <section class="card">
            <header class="card-header">
                <h2>2. Records Read from <code>registrations.json</code> (<?= count($jsonRecords) ?> entries)</h2>
                <a href="data/registrations.json" class="btn btn-sm btn-outline" download>Download JSON</a>
            </header>
            <div class="table-wrap">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Full Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Mobile</th>
                            <th scope="col">Course</th>
                            <th scope="col">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($jsonRecords)): ?>
                            <tr><td colspan="5" style="text-align: center; color: var(--text-muted);">No records found in JSON.</td></tr>
                        <?php else: ?>
                            <?php foreach ($jsonRecords as $item): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($item['fullname']) ?></strong></td>
                                    <td><?= htmlspecialchars($item['email']) ?></td>
                                    <td><?= htmlspecialchars($item['mobile']) ?></td>
                                    <td><span class="badge badge-success"><?= htmlspecialchars($item['course']) ?></span></td>
                                    <td><code><?= htmlspecialchars($item['timestamp']) ?></code></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer class="sh-footer">
        <div class="sh-footer-inner">
            <div><strong>StudentHub Portal</strong> — CHARUSAT FTE | ITUE203 Practical 07</div>
            <ul class="sh-footer-links">
                <li><a href="index.php">Form</a></li>
                <li><a href="../index.html">Practicals Hub</a></li>
            </ul>
        </div>
    </footer>

    <script src="../shared/js/common.js"></script>
</body>
</html>
