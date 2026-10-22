<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical 8: MySQL Schema Design & ER Model</title>
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
                    <li class="sh-nav-item active"><a href="index.php">ER Model & Schema</a></li>
                    <li class="sh-nav-item"><a href="test_db.php">PDO Connectivity Test</a></li>
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
                <span class="current">Practical 08</span>
            </nav>
            <span class="sh-practical-tag">Practical 08: MySQL Schema & ER Model</span>
        </div>
    </div>

    <main id="main-content" class="sh-main">
        <header style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 14px;">
            <div>
                <h1 class="text-gradient">Relational Database Design & ER Model</h1>
                <p class="lead-text">3NF Normalized MySQL schema with primary/foreign key constraints, cascading referential integrity, stored procedures, and PHP PDO connection pooling.</p>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="test_db.php" class="btn btn-primary">Test PDO Connection →</a>
                <a href="schema.sql" class="btn btn-outline" download>Download schema.sql</a>
            </div>
        </header>

        <!-- Visual Entity-Relationship Diagram -->
        <section class="card" style="margin-bottom: 2rem;">
            <header class="card-header">
                <h2>Entity-Relationship (ER) Architecture</h2>
                <span class="badge badge-info">Crow's Foot Notation</span>
            </header>
            <div style="background: var(--bg-subtle); padding: 1.5rem; border-radius: var(--radius-md); font-family: var(--font-mono); font-size: 0.84rem; overflow-x: auto; line-height: 1.6;">
[ USERS ]
├── id (PK)
├── username (UNIQUE)
├── email (UNIQUE)
├── password_hash
├── role [admin | student | faculty]
└── status [active | inactive]
       │
       ├── (1 : 1 optional) ──► [ STUDENTS ]
       │                         ├── id (PK)
       │                         ├── user_id (FK - users.id)
       │                         ├── roll_no (UNIQUE)
       │                         ├── full_name, email, mobile
       │                         └── department, semester, cgpa
       │                                  │
       │                                  └── (1 : N) ──► [ REGISTRATIONS ] (Junction)
       │                                                        ├── id (PK)
       │                                                        ├── student_id (FK)
       │                                                        ├── event_id (FK)
       │                                                        ├── status [Confirmed | Waitlisted]
       │                                                        └── registration_date
       │                                                                ▲
       │                                                                │ (1 : N)
       └── (1 : N) ───────────► [ EVENTS ] ─────────────────────────────┘
                                 ├── id (PK)
                                 ├── title, category, event_date, venue
                                 ├── max_seats, registered_seats, poster_image
                                 └── created_by (FK - users.id)
            </div>
        </section>

        <!-- Tables Dictionary -->
        <div class="grid grid-2">
            <div class="card">
                <h3>Table: <code>students</code></h3>
                <p style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 12px;">Represents enrolled university students with academic records.</p>
                <ul style="font-size: 0.9rem; line-height: 1.8; padding-left: 20px;">
                    <li><code>id</code> (INT, PK, AUTO_INCREMENT)</li>
                    <li><code>user_id</code> (INT, FK -> users.id, ON DELETE SET NULL)</li>
                    <li><code>roll_no</code> (VARCHAR 20, UNIQUE)</li>
                    <li><code>full_name</code> (VARCHAR 100)</li>
                    <li><code>department</code> & <code>semester</code></li>
                    <li><code>cgpa</code> (DECIMAL 3,2)</li>
                </ul>
            </div>

            <div class="card">
                <h3>Table: <code>events</code></h3>
                <p style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 12px;">Stores workshops, fests, hackathons, and seat quotas.</p>
                <ul style="font-size: 0.9rem; line-height: 1.8; padding-left: 20px;">
                    <li><code>id</code> (INT, PK, AUTO_INCREMENT)</li>
                    <li><code>title</code> (VARCHAR 150)</li>
                    <li><code>category</code> (VARCHAR 50)</li>
                    <li><code>event_date</code> & <code>venue</code></li>
                    <li><code>max_seats</code> & <code>registered_seats</code></li>
                    <li><code>poster_image</code> (VARCHAR 255)</li>
                </ul>
            </div>
        </div>
    </main>

    <footer class="sh-footer">
        <div class="sh-footer-inner">
            <div><strong>StudentHub Portal</strong> — CHARUSAT FTE | ITUE203 Practical 08</div>
            <ul class="sh-footer-links">
                <li><a href="test_db.php">Test DB</a></li>
                <li><a href="../index.html">Practicals Hub</a></li>
            </ul>
        </div>
    </footer>

    <script src="../shared/js/common.js"></script>
</body>
</html>
