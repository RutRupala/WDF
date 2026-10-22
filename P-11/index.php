<?php
session_start();
require_once __DIR__ . '/db.php';

$success = $_SESSION['flash_success'] ?? null;
$error = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

// Search & Filter parameters
$search = trim($_GET['search'] ?? '');
$deptFilter = trim($_GET['department'] ?? '');
$statusFilter = trim($_GET['status'] ?? '');

$students = [];
if ($pdo) {
    $sql = "SELECT * FROM students WHERE 1=1";
    $params = [];

    if (!empty($search)) {
        $sql .= " AND (full_name LIKE ? OR roll_no LIKE ? OR email LIKE ?)";
        $searchTerm = "%{$search}%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }

    if (!empty($deptFilter)) {
        $sql .= " AND department = ?";
        $params[] = $deptFilter;
    }

    if (!empty($statusFilter)) {
        $sql .= " AND status = ?";
        $params[] = $statusFilter;
    }

    $sql .= " ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $students = $stmt->fetchAll();
} else {
    // Fallback seed array if DB not yet imported
    $students = [
        ['id' => 1, 'roll_no' => '24IT001', 'full_name' => 'Aarav Sharma', 'email' => 'aarav.s@charusat.edu.in', 'mobile' => '9876543210', 'department' => 'Information Technology', 'semester' => 3, 'cgpa' => 9.12, 'status' => 'Active'],
        ['id' => 2, 'roll_no' => '24CSE015', 'full_name' => 'Diya Joshi', 'email' => 'diya.j@charusat.edu.in', 'mobile' => '9825012345', 'department' => 'Computer Science', 'semester' => 3, 'cgpa' => 8.94, 'status' => 'Active'],
        ['id' => 3, 'roll_no' => '24CE089', 'full_name' => 'Karan Verma', 'email' => 'karan.v@charusat.edu.in', 'mobile' => '9898098980', 'department' => 'Computer Engineering', 'semester' => 3, 'cgpa' => 7.85, 'status' => 'Inactive'],
        ['id' => 4, 'roll_no' => '24AIML004', 'full_name' => 'Manan Desai', 'email' => 'manan.d@charusat.edu.in', 'mobile' => '9876500112', 'department' => 'Artificial Intelligence', 'semester' => 3, 'cgpa' => 8.70, 'status' => 'Active']
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical 11: Student Management CRUD with Search & Filter</title>
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
                    <span class="subtitle">ITUE203 Practical 11</span>
                </div>
            </a>
            <nav aria-label="Primary Site Navigation">
                <ul class="sh-nav-menu">
                    <li class="sh-nav-item active"><a href="index.php">Student Directory</a></li>
                    <li class="sh-nav-item"><a href="dashboard.html">Dashboard</a></li>
                    <li class="sh-nav-item"><a href="events.html">Events</a></li>
                </ul>
            </nav>
            <div class="sh-nav-actions">
                <button type="button" class="btn btn-sm btn-primary" onclick="openModal('add-student-modal')">+ Add New Student</button>
                <a href="export_csv.php" class="btn btn-sm btn-outline">📥 Export to CSV</a>
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
                <span class="current">Practical 11</span>
            </nav>
            <span class="sh-practical-tag">Practical 11: MySQLi/PDO CRUD</span>
        </div>
    </div>

    <main id="main-content" class="sh-main">
        <header style="margin-bottom: 2rem;">
            <h1 class="text-gradient">Student Management System (CRUD)</h1>
            <p class="lead-text">Complete administrative module to Add, View, Edit, Search, Filter, Soft Delete, and Export student records with prepared statements.</p>
        </header>

        <?php if ($success): ?>
            <div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger">❌ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Search & Filter Bar -->
        <section class="card" style="margin-bottom: 1.5rem; padding: 18px;">
            <form method="GET" action="index.php" style="display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 14px; align-items: flex-end;">
                <div>
                    <label for="search" class="form-label" style="font-size: 0.82rem;">Search by Name, Roll No, or Email</label>
                    <input type="text" id="search" name="search" class="form-control" placeholder="Search students..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <div>
                    <label for="department" class="form-label" style="font-size: 0.82rem;">Department</label>
                    <select id="department" name="department" class="form-select">
                        <option value="">All Departments</option>
                        <option value="Information Technology" <?= $deptFilter === 'Information Technology' ? 'selected' : '' ?>>Information Technology</option>
                        <option value="Computer Science" <?= $deptFilter === 'Computer Science' ? 'selected' : '' ?>>Computer Science</option>
                        <option value="Computer Engineering" <?= $deptFilter === 'Computer Engineering' ? 'selected' : '' ?>>Computer Engineering</option>
                        <option value="Artificial Intelligence" <?= $deptFilter === 'Artificial Intelligence' ? 'selected' : '' ?>>Artificial Intelligence</option>
                    </select>
                </div>
                <div>
                    <label for="status" class="form-label" style="font-size: 0.82rem;">Enrollment Status</label>
                    <select id="status" name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="Active" <?= $statusFilter === 'Active' ? 'selected' : '' ?>>Active Only</option>
                        <option value="Inactive" <?= $statusFilter === 'Inactive' ? 'selected' : '' ?>>Inactive (Soft-Deleted)</option>
                    </select>
                </div>
                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="index.php" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </section>

        <!-- Students Table -->
        <section class="card">
            <header class="card-header">
                <h2>Enrolled Student Directory (<?= count($students) ?> found)</h2>
                <span class="badge badge-primary">PDO Prepared Statements</span>
            </header>
            <div class="table-wrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Roll No</th>
                            <th scope="col">Student Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Department</th>
                            <th scope="col">Sem</th>
                            <th scope="col">CGPA</th>
                            <th scope="col">Status</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($students)): ?>
                            <tr><td colspan="8" style="text-align: center; color: var(--text-muted); padding: 2rem;">No matching student records found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($students as $s): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($s['roll_no']) ?></strong></td>
                                    <td><?= htmlspecialchars($s['full_name']) ?></td>
                                    <td><?= htmlspecialchars($s['email']) ?></td>
                                    <td><span class="badge badge-info"><?= htmlspecialchars($s['department']) ?></span></td>
                                    <td><?= htmlspecialchars($s['semester']) ?></td>
                                    <td><strong><?= htmlspecialchars($s['cgpa']) ?></strong></td>
                                    <td>
                                        <?php if ($s['status'] === 'Active'): ?>
                                            <span class="badge badge-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 6px;">
                                            <a href="delete_student.php?id=<?= $s['id'] ?>&type=soft" class="btn btn-sm btn-secondary" onclick="return confirm('Soft-delete student #<?= $s['id'] ?> (set to Inactive)?')">Deactivate</a>
                                            <a href="delete_student.php?id=<?= $s['id'] ?>&type=hard" class="btn btn-sm btn-danger" onclick="return confirm('PERMANENTLY delete student #<?= $s['id'] ?> from database?')">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- Modal: Add Student -->
    <div id="add-student-modal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Add New Student Record</h3>
                <button type="button" class="modal-close" onclick="closeModal('add-student-modal')">×</button>
            </div>
            <form action="add_student.php" method="POST">
                <div class="form-group">
                    <label for="m_roll" class="form-label">Roll Number <span class="req">*</span></label>
                    <input type="text" id="m_roll" name="roll_no" class="form-control" placeholder="e.g. 24IT099" required>
                </div>
                <div class="form-group">
                    <label for="m_name" class="form-label">Full Name <span class="req">*</span></label>
                    <input type="text" id="m_name" name="full_name" class="form-control" placeholder="e.g. Priyansh Shah" required>
                </div>
                <div class="form-group">
                    <label for="m_email" class="form-label">Email Address <span class="req">*</span></label>
                    <input type="email" id="m_email" name="email" class="form-control" placeholder="student@charusat.edu.in" required>
                </div>
                <div class="form-group">
                    <label for="m_mobile" class="form-label">Mobile Number</label>
                    <input type="tel" id="m_mobile" name="mobile" class="form-control" placeholder="9876543210">
                </div>
                <div class="form-group">
                    <label for="m_dept" class="form-label">Department <span class="req">*</span></label>
                    <select id="m_dept" name="department" class="form-select" required>
                        <option value="Information Technology">Information Technology</option>
                        <option value="Computer Science">Computer Science</option>
                        <option value="Computer Engineering">Computer Engineering</option>
                        <option value="Artificial Intelligence">Artificial Intelligence</option>
                    </select>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="form-group">
                        <label for="m_sem" class="form-label">Semester</label>
                        <input type="number" id="m_sem" name="semester" class="form-control" value="3" min="1" max="8">
                    </div>
                    <div class="form-group">
                        <label for="m_cgpa" class="form-label">CGPA</label>
                        <input type="number" step="0.01" id="m_cgpa" name="cgpa" class="form-control" value="8.50" min="0" max="10">
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 14px;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('add-student-modal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Student to Database</button>
                </div>
            </form>
        </div>
    </div>

    <footer class="sh-footer">
        <div class="sh-footer-inner">
            <div><strong>StudentHub Portal</strong> — CHARUSAT FTE | ITUE203 Practical 11</div>
            <ul class="sh-footer-links">
                <li><a href="export_csv.php">Export CSV</a></li>
                <li><a href="../index.html">Practicals Hub</a></li>
            </ul>
        </div>
    </footer>

    <script src="../shared/js/common.js"></script>
</body>
</html>
