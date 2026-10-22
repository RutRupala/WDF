# Practical 1: Project Initiation, Requirement Analysis, Sitemap, Wireframe, and GitHub Setup

**Course:** Web Development Frameworks (ITUE203)  
**Semester:** 3rd, 2026-27 (ODD)  
**Institution:** Charotar University of Science and Technology (CHARUSAT)  
**Faculty:** Faculty of Technology and Engineering (FTE)  
**CO Mapping:** CO1 | **PO Mapping:** PO1(3), PO2(2), PO3(3), PO4(2), PO5(3), PO9(1), PO10(3)

---

## 1. Problem Definition & Scope
The goal is to initiate the semester-long **StudentHub Portal**—a centralized, responsive, role-based academic portal designed for students, faculty, and administrative staff at CHARUSAT. The portal unifies academic announcements, student profiles, event registrations, study resource archives, and administrative control panels into a unified web application.

### Identified User Roles
1. **Student:** Browse notices, explore calendar, register for campus events, view personalized academic dashboard, download resources, submit feedback.
2. **Faculty / Event Coordinator:** Publish academic events, approve student registrations, manage study resources.
3. **Administrator:** System configuration, user account management (status toggles, role assignments), event moderation, and audit log inspection.

### Key Modules
- **Authentication & RBAC:** Registration, secure login, role-based dashboards, session timeout, remember-me cookies.
- **Academic Directory:** Searchable & filterable student records with CSV exports.
- **Campus Events Hub:** Dynamic event listings, AJAX poster uploads, seat availability tracking, real-time registration.
- **Resource Repository:** Subject-wise downloadable lecture notes and academic calendar.
- **Admin Control Center:** Real-time KPI statistics, dynamic navigation menus, and immutable audit logging.

---

## 2. Navigation Flow & Page Map (Minimum 10 Pages)
1. `index.html` - StudentHub Landing & Home Page
2. `about.html` - About University, Vision, Faculty & Department Information
3. `register.html` - Student & User Registration Portal
4. `login.html` - Secure Authentication Portal
5. `dashboard.html` - Interactive Student Academic Dashboard
6. `events.html` - Campus Events Directory & Participation System
7. `calendar.html` - Academic Calendar, Exam Dates, & Schedules
8. `resources.html` - Departmental Study Materials & Guides
9. `contact.html` - Institutional Directory & Contact Form
10. `admin.html` - Administrative Management & Moderation Center
11. `faq.html` - Frequently Asked Questions & Student Help Desk
12. `feedback.html` - Student Experience & Suggestion Form

---

## 3. Analysis & Key Questions Answered

### Q1: What is a URL and what are the parts of a URL?
A **Uniform Resource Locator (URL)** is a specific type of Uniform Resource Identifier (URI) that specifies the web address of a resource and the mechanism used to retrieve it.
- **Protocol / Scheme:** (e.g., `https://` or `http://`) determines the transfer protocol and encryption.
- **Subdomain:** (e.g., `studenthub.` in `studenthub.charusat.ac.in`) partitions services within a domain.
- **Domain Name / Host:** (e.g., `charusat.ac.in`) maps to an IP address via DNS.
- **Port:** (e.g., `:80`, `:443`, `:8080`) network port endpoint (defaults to 80 for HTTP, 443 for HTTPS).
- **Path:** (e.g., `/events/register.php`) hierarchical file or virtual route on the server.
- **Query String:** (e.g., `?id=101&category=workshop`) key-value parameters sent to backend scripts.
- **Fragment / Hash:** (e.g., `#schedule`) client-side anchor jump within the page.

### Q2: How is an HTML file processed in a web browser?
1. **Network Fetch:** Browser sends HTTP GET request and receives byte streams.
2. **Conversion & Tokenization:** HTML bytes are converted to characters based on charset (UTF-8), then tokenized into HTML tags (`<html>`, `<head>`, `<body>`, `<section>`).
3. **DOM Tree Construction:** Tokens are converted into Node objects forming the Document Object Model (DOM) tree.
4. **CSSOM Tree Construction:** Linked stylesheets and `<style>` blocks are parsed into the CSS Object Model (CSSOM).
5. **Render Tree Generation:** DOM and CSSOM combine into the Render Tree, filtering out non-rendered elements (e.g., `<head>`, elements with `display: none`).
6. **Layout / Reflow:** The browser calculates geometry, coordinates, and dimensions for every node on the viewport.
7. **Painting:** Nodes are drawn onto layers (colors, borders, shadows, text).
8. **Compositing:** Separate layers are combined onto the screen via GPU hardware acceleration.

### Q3: How will page navigation flow be managed among all HTML pages?
Navigation is managed through a **unified, responsive semantic navigation bar** and **standardized breadcrumb trail** sharing a centralized design system. Relative hierarchical paths maintain consistent state, active page links are highlighted via JavaScript and CSS, and keyboard accessibility (Tab, Enter) is preserved.

### Q4: How will GitHub commits be maintained after each practical?
We adopt a standardized Git workflow:
- `main`: Production-ready, verified codebase.
- Branch per practical: `feature/practical-01` through `feature/practical-15`.
- Commit Message Convention: Follows Conventional Commits:
  - `feat(P-01): project initiation, requirements analysis and visual sitemap`
  - `feat(P-02): semantic HTML5 layout with WCAG accessibility tags`
  - `feat(P-03): mobile-first responsive grid and flexbox layout`
- Semantic tagging for lab milestones: `git tag -a v1.0 -m "Completed Practical 1"`.

---

## 4. Deliverables in this Folder
- `index.html` - Home page structure
- `sitemap.html` - Interactive visual sitemap tree
- `wireframes.html` - Low-fidelity wireframe representations
- `sitemap.txt` - Textual folder hierarchy
- Supporting HTML skeletons: `about.html`, `events.html`, `calendar.html`, `resources.html`, `contact.html`, `login.html`, `register.html`, `forms.html`
