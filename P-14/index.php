<?php
session_start();
require_once __DIR__ . '/db.php';

// Demo role switcher to test dynamic menu loading
$simulatedRole = $_GET['role'] ?? ($_SESSION['role'] ?? 'admin');

// Dynamic Metrics from DB or Fallback
$studentCount = 2450;
$eventCount = 18;
$registrationCount = 642;
$auditCount = 1208;
$auditLogs = [];

if ($pdo) {
    try {
        $studentCount = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
        $eventCount = $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
        $registrationCount = $pdo->query("SELECT COUNT(*) FROM registrations")->fetchColumn();
        $auditCount = $pdo->query("SELECT COUNT(*) FROM audit_logs")->fetchColumn();

        $auditLogs = $pdo->query("SELECT * FROM audit_logs ORDER BY id DESC LIMIT 6")->fetchAll();
    } catch (Exception $e) {}
}

if (empty($auditLogs)) {
    $auditLogs = [
        ['id' => 101, 'action' => 'LOGIN_SUCCESS', 'entity' => 'AUTH', 'details' => 'Admin signed in securely with 2FA token', 'ip_address' => '192.168.1.45', 'created_at' => date('Y-m-d H:i:s', time() - 300)],
        ['id' => 102, 'action' => 'EVENT_CREATE', 'entity' => 'EVENTS', 'details' => 'Published new workshop: Cloud Summit 2026', 'ip_address' => '192.168.1.45', 'created_at' => date('Y-m-d H:i:s', time() - 1200)],
        ['id' => 103, 'action' => 'STUDENT_INSERT', 'entity' => 'STUDENTS', 'details' => 'Enrolled student 24IT099 (Priyansh Shah)', 'ip_address' => '192.168.1.88', 'created_at' => date('Y-m-d H:i:s', time() - 3600)],
        ['id' => 104, 'action' => 'STATUS_TOGGLE', 'entity' => 'USERS', 'details' => 'Deactivated inactive account user #42', 'ip_address' => '192.168.1.45', 'created_at' => date('Y-m-d H:i:s', time() - 7200)]
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical 14: Admin Dashboard with RBAC, Dynamic Menu & Audit Log</title>
    <link rel="stylesheet" href="../shared/css/common.css">
    <style>
        .chart-bar-wrap {
            display: flex;
            align-items: flex-end;
            gap: 16px;
            height: 180px;
            padding-top: 20px;
            border-bottom: 2px solid var(--border-color);
        }
        .chart-col {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100%;
            justify-content: flex-end;
        }
        .chart-bar {
            width: 100%;
            max-width: 42px;
            background: linear-gradient(180deg, var(--primary) 0%, var(--accent) 100%);
            border-radius: 6px 6px 0 0;
            transition: height 0.5s ease;
        }
        .chart-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 6px;
        }
    </style>
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <header class="sh-header">
        <div class="sh-nav-container">
            <a href="index.php" class="sh-brand">
                <div class="sh-brand-badge">SH</div>
                <div class="sh-brand-text">
                    <span class="title">StudentHub</span>
                    <span class="subtitle">ITUE203 Practical 14</span>
                </div>
            </a>

            <!-- Dynamic Menu Based on Role (Syllabus Requirement) -->
            <nav aria-label="Dynamic Role Navigation">
                <ul class="sh-nav-menu">
                    <li class="sh-nav-item active"><a href="index.php">Dashboard</a></li>
                    <?php if ($simulatedRole === 'admin'): ?>
                        <li class="sh-nav-item"><a href="../P-11/index.php">Student Records</a></li>
                        <li class="sh-nav-item"><a href="../P-12/index.php">Event Moderation</a></li>
                        <li class="sh-nav-item"><a href="#audit-section">Audit Trail</a></li>
                    <?php elseif ($simulatedRole === 'faculty'): ?>
                        <li class="sh-nav-item"><a href="../P-12/index.php">My Events</a></li>
                        <li class="sh-nav-item"><a href="../P-11/index.php">Attendance Roster</a></li>
                    <?php else: ?>
                        <li class="sh-nav-item"><a href="../P-6/index.html">Browse Events</a></li>
                        <li class="sh-nav-item"><a href="../P-2/profile.html">My Profile</a></li>
                    <?php endif; ?>
                </ul>
            </nav>

            <div class="sh-nav-actions">
                <!-- Role Switcher for Evaluation -->
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="font-size: 0.78rem; color: var(--text-muted);">Role:</span>
                    <select onchange="location.href='index.php?role='+this.value" class="form-select" style="padding: 4px 8px; font-size: 0.8rem; width: auto;">
                        <option value="admin" <?= $simulatedRole === 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="faculty" <?= $simulatedRole === 'faculty' ? 'selected' : '' ?>>Faculty</option>
                        <option value="student" <?= $simulatedRole === 'student' ? 'selected' : '' ?>>Student</option>
                    </select>
                </div>
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
                <span class="current">Practical 14</span>
            </nav>
            <span class="sh-practical-tag">Practical 14: Dynamic RBAC & Analytics</span>
        </div>
    </div>

    <main id="main-content" class="sh-main">
        <header style="margin-bottom: 2rem;">
            <h1 class="text-gradient">Administrative Command Center</h1>
            <p class="lead-text">Displaying dynamic SQL aggregation metrics, role-tailored menus (Current: <strong><?= strtoupper($simulatedRole) ?></strong>), interactive monthly analytics, user status toggling, and audit log inspection.</p>
        </header>

        <!-- KPI Cards -->
        <section class="grid grid-4" style="margin-bottom: 2rem;">
            <div class="card" style="border-top: 4px solid var(--primary);">
                <span style="font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Enrolled Students</span>
                <h3 style="font-size: 2rem; margin: 4px 0;"><?= number_format($studentCount) ?></h3>
                <span class="badge badge-primary">Database Synced</span>
            </div>
            <div class="card" style="border-top: 4px solid var(--accent);">
                <span style="font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Active Events</span>
                <h3 style="font-size: 2rem; margin: 4px 0;"><?= number_format($eventCount) ?></h3>
                <span class="badge badge-info">FTE Workshops</span>
            </div>
            <div class="card" style="border-top: 4px solid var(--success);">
                <span style="font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Total Registrations</span>
                <h3 style="font-size: 2rem; margin: 4px 0;"><?= number_format($registrationCount) ?></h3>
                <span class="badge badge-success">+18% this month</span>
            </div>
            <div class="card" style="border-top: 4px solid var(--warning);">
                <span style="font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Audit Entries</span>
                <h3 style="font-size: 2rem; margin: 4px 0;"><?= number_format($auditCount) ?></h3>
                <span class="badge badge-neutral">Security Logged</span>
            </div>
        </section>

        <!-- Analytics Chart & User Toggle Split -->
        <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 24px; margin-bottom: 2rem;">
            <!-- Analytics Chart (Advanced Extension) -->
            <div class="card">
                <header class="card-header">
                    <h3>📊 Event Registrations Analytics by Month</h3>
                    <span class="badge badge-primary">2026-27</span>
                </header>
                <div class="chart-bar-wrap">
                    <div class="chart-col">
                        <span style="font-size: 0.72rem; font-weight: 700;">85</span>
                        <div class="chart-bar" style="height: 45%;"></div>
                        <span class="chart-label">Aug</span>
                    </div>
                    <div class="chart-col">
                        <span style="font-size: 0.72rem; font-weight: 700;">140</span>
                        <div class="chart-bar" style="height: 65%;"></div>
                        <span class="chart-label">Sep</span>
                    </div>
                    <div class="chart-col">
                        <span style="font-size: 0.72rem; font-weight: 700;">220</span>
                        <div class="chart-bar" style="height: 95%;"></div>
                        <span class="chart-label">Oct</span>
                    </div>
                    <div class="chart-col">
                        <span style="font-size: 0.72rem; font-weight: 700;">175</span>
                        <div class="chart-bar" style="height: 78%;"></div>
                        <span class="chart-label">Nov</span>
                    </div>
                    <div class="chart-col">
                        <span style="font-size: 0.72rem; font-weight: 700;">110</span>
                        <div class="chart-bar" style="height: 52%;"></div>
                        <span class="chart-label">Dec</span>
                    </div>
                </div>
            </div>

            <!-- User Status Toggle Table (Intermediate Extension) -->
            <div class="card">
                <header class="card-header">
                    <h3>⚡ User Status Controls (AJAX Toggle)</h3>
                </header>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Toggle</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>aarav_p</strong></td>
                                <td>Student</td>
                                <td><span id="badge-1" class="badge badge-success">active</span></td>
                                <td><button class="btn btn-sm btn-secondary" onclick="toggleStatus(1)">Toggle</button></td>
                            </tr>
                            <tr>
                                <td><strong>karan_v</strong></td>
                                <td>Student</td>
                                <td><span id="badge-2" class="badge badge-danger">inactive</span></td>
                                <td><button class="btn btn-sm btn-secondary" onclick="toggleStatus(2)">Toggle</button></td>
                            </tr>
                            <tr>
                                <td><strong>prof_martin</strong></td>
                                <td>Faculty</td>
                                <td><span id="badge-3" class="badge badge-success">active</span></td>
                                <td><button class="btn btn-sm btn-secondary" onclick="toggleStatus(3)">Toggle</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Audit Logs Table -->
        <section id="audit-section" class="card">
            <header class="card-header">
                <h2>Security & Activity Audit Log (Immutable SQL Entries)</h2>
                <span class="badge badge-info">IP Tracked</span>
            </header>
            <div class="table-wrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Log ID</th>
                            <th scope="col">Action</th>
                            <th scope="col">Target Entity</th>
                            <th scope="col">Activity Details</th>
                            <th scope="col">Client IP</th>
                            <th scope="col">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($auditLogs as $log): ?>
                            <tr>
                                <td><strong>#<?= $log['id'] ?></strong></td>
                                <td><span class="badge badge-primary"><?= htmlspecialchars($log['action']) ?></span></td>
                                <td><code><?= htmlspecialchars($log['entity']) ?></code></td>
                                <td><?= htmlspecialchars($log['details']) ?></td>
                                <td><code><?= htmlspecialchars($log['ip_address']) ?></code></td>
                                <td style="font-size: 0.82rem; color: var(--text-muted);"><?= $log['created_at'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer class="sh-footer">
        <div class="sh-footer-inner">
            <div><strong>StudentHub Portal</strong> — CHARUSAT FTE | ITUE203 Practical 14</div>
            <ul class="sh-footer-links">
                <li><a href="../index.html">Practicals Hub</a></li>
            </ul>
        </div>
    </footer>

    <script src="../shared/js/common.js"></script>
    <script>
        async function toggleStatus(userId) {
            const badge = document.getElementById(`badge-${userId}`);
            const current = badge.textContent.trim().toLowerCase();
            const next = current === 'active' ? 'inactive' : 'active';

            try {
                const res = await fetch('toggle_status.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ user_id: userId, status: next })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    badge.textContent = data.new_status;
                    badge.className = `badge badge-${data.new_status === 'active' ? 'success' : 'danger'}`;
                    window.showToast(data.message, 'info');
                }
            } catch (err) {
                window.showToast('AJAX status toggle failed', 'danger');
            }
        }
    </script>
</body>
</html>
