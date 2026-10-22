<?php
session_start();
require_once __DIR__ . '/db.php';

$success = $_SESSION['flash_success'] ?? null;
$errors = $_SESSION['flash_errors'] ?? [];
unset($_SESSION['flash_success'], $_SESSION['flash_errors']);

$categoryFilter = trim($_GET['category'] ?? '');
$statusFilter = trim($_GET['status'] ?? '');

$events = [];
if ($pdo) {
    $sql = "SELECT * FROM events WHERE 1=1";
    $params = [];
    if (!empty($categoryFilter)) {
        $sql .= " AND category = ?";
        $params[] = $categoryFilter;
    }
    if (!empty($statusFilter)) {
        $sql .= " AND status = ?";
        $params[] = $statusFilter;
    }
    $sql .= " ORDER BY event_date ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $events = $stmt->fetchAll();
} else {
    // Fallback sample events if DB not imported
    $events = [
        ['id' => 1, 'title' => 'National Cloud Summit 2026', 'category' => 'Technical', 'event_date' => '2026-11-05', 'venue' => 'FTE Auditorium B', 'max_seats' => 150, 'registered_seats' => 45, 'poster_image' => 'default_event.jpg', 'status' => 'Upcoming'],
        ['id' => 2, 'title' => 'CHARUSAT Hack-a-Fest 4.0', 'category' => 'Hackathon', 'event_date' => '2026-11-18', 'venue' => 'Central Computing Lab', 'max_seats' => 80, 'registered_seats' => 78, 'poster_image' => 'default_event.jpg', 'status' => 'Upcoming'],
        ['id' => 3, 'title' => 'Spoural 2027 Sports Meet', 'category' => 'Sports', 'event_date' => '2026-12-02', 'venue' => 'University Sports Ground', 'max_seats' => 400, 'registered_seats' => 110, 'poster_image' => 'default_event.jpg', 'status' => 'Upcoming']
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical 12: Event Management CRUD with File Upload</title>
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
                    <span class="subtitle">ITUE203 Practical 12</span>
                </div>
            </a>
            <nav aria-label="Primary Site Navigation">
                <ul class="sh-nav-menu">
                    <li class="sh-nav-item active"><a href="index.php">Event Management</a></li>
                    <li class="sh-nav-item"><a href="dashboard.html">Dashboard</a></li>
                    <li class="sh-nav-item"><a href="events.html">Public Events View</a></li>
                </ul>
            </nav>
            <div class="sh-nav-actions">
                <button type="button" class="btn btn-sm btn-primary" onclick="openModal('add-event-modal')">+ Create Event</button>
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
                <span class="current">Practical 12</span>
            </nav>
            <span class="sh-practical-tag">Practical 12: File Validation & Event CRUD</span>
        </div>
    </div>

    <main id="main-content" class="sh-main">
        <header style="margin-bottom: 2rem;">
            <h1 class="text-gradient">Event Management CRUD & Poster Upload</h1>
            <p class="lead-text">Admin module with MIME-type verification, 2MB size cap, automated file renaming, directory protection, and category filtering.</p>
        </header>

        <?php if ($success): ?>
            <div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Filters -->
        <section class="card" style="margin-bottom: 1.5rem; padding: 18px;">
            <form method="GET" action="index.php" style="display: flex; gap: 14px; align-items: flex-end; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 200px;">
                    <label for="category" class="form-label" style="font-size: 0.82rem;">Filter Category</label>
                    <select id="category" name="category" class="form-select">
                        <option value="">All Categories</option>
                        <option value="Technical" <?= $categoryFilter === 'Technical' ? 'selected' : '' ?>>Technical</option>
                        <option value="Hackathon" <?= $categoryFilter === 'Hackathon' ? 'selected' : '' ?>>Hackathon</option>
                        <option value="Sports" <?= $categoryFilter === 'Sports' ? 'selected' : '' ?>>Sports</option>
                        <option value="Career" <?= $categoryFilter === 'Career' ? 'selected' : '' ?>>Career</option>
                    </select>
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label for="status" class="form-label" style="font-size: 0.82rem;">Filter Status</label>
                    <select id="status" name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="Upcoming" <?= $statusFilter === 'Upcoming' ? 'selected' : '' ?>>Upcoming</option>
                        <option value="Completed" <?= $statusFilter === 'Completed' ? 'selected' : '' ?>>Completed</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Apply Filter</button>
                <a href="index.php" class="btn btn-secondary">Reset</a>
            </form>
        </section>

        <!-- Events Grid -->
        <div class="grid grid-3">
            <?php foreach ($events as $ev): ?>
                <article class="card card-hover">
                    <div class="card-header">
                        <span class="badge badge-primary"><?= htmlspecialchars($ev['category']) ?></span>
                        <span class="badge badge-success"><?= htmlspecialchars($ev['status']) ?></span>
                    </div>
                    <h3 style="font-size: 1.15rem; margin-bottom: 8px;"><?= htmlspecialchars($ev['title']) ?></h3>
                    <div style="font-size: 0.88rem; color: var(--text-muted); line-height: 1.7; margin-bottom: 12px;">
                        <div>📅 Date: <strong><?= htmlspecialchars($ev['event_date']) ?></strong></div>
                        <div>📍 Venue: <?= htmlspecialchars($ev['venue']) ?></div>
                        <div>👥 Capacity: <?= $ev['registered_seats'] ?> / <?= $ev['max_seats'] ?> seats</div>
                        <div>🖼️ Poster: <code><?= htmlspecialchars($ev['poster_image']) ?></code></div>
                    </div>
                    <div class="card-footer" style="justify-content: flex-end;">
                        <a href="delete_event.php?id=<?= $ev['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete event <?= htmlspecialchars($ev['title']) ?> and its poster?')">Delete Event</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- Modal: Add Event with Poster Upload -->
    <div id="add-event-modal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Publish New Campus Event</h3>
                <button type="button" class="modal-close" onclick="closeModal('add-event-modal')">×</button>
            </div>
            <form action="add_event.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="e_title" class="form-label">Event Title <span class="req">*</span></label>
                    <input type="text" id="e_title" name="title" class="form-control" placeholder="e.g. AI Prompt Engineering Bootcamp" required>
                </div>
                <div class="form-group">
                    <label for="e_cat" class="form-label">Category <span class="req">*</span></label>
                    <select id="e_cat" name="category" class="form-select" required>
                        <option value="Technical">Technical</option>
                        <option value="Hackathon">Hackathon</option>
                        <option value="Sports">Sports</option>
                        <option value="Career">Career & Placement</option>
                        <option value="Cultural">Cultural</option>
                    </select>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="form-group">
                        <label for="e_date" class="form-label">Date <span class="req">*</span></label>
                        <input type="date" id="e_date" name="event_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="e_seats" class="form-label">Seat Quota</label>
                        <input type="number" id="e_seats" name="max_seats" class="form-control" value="100" min="10" max="1000">
                    </div>
                </div>
                <div class="form-group">
                    <label for="e_venue" class="form-label">Venue <span class="req">*</span></label>
                    <input type="text" id="e_venue" name="venue" class="form-control" placeholder="e.g. FTE Seminar Hall 2" required>
                </div>
                <!-- Poster Upload with Validation Restrictions -->
                <div class="form-group">
                    <label for="e_poster" class="form-label">Upload Event Poster (JPEG, PNG, WebP &le; 2MB)</label>
                    <input type="file" id="e_poster" name="poster" class="form-control" accept="image/jpeg,image/png,image/webp">
                    <span class="form-hint">Server verifies MIME type and applies random unique renaming for security.</span>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 16px;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('add-event-modal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Validate & Save Event</button>
                </div>
            </form>
        </div>
    </div>

    <footer class="sh-footer">
        <div class="sh-footer-inner">
            <div><strong>StudentHub Portal</strong> — CHARUSAT FTE | ITUE203 Practical 12</div>
            <ul class="sh-footer-links">
                <li><a href="../index.html">Practicals Hub</a></li>
            </ul>
        </div>
    </footer>

    <script src="../shared/js/common.js"></script>
</body>
</html>
