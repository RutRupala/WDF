# ITUE203: Web Development Frameworks
### Charotar University of Science and Technology (CHARUSAT)
**Faculty of Technology and Engineering (FTE)**  
**Semester:** 3rd, 2026-27 (ODD)  
**Semester Project:** StudentHub Academic Portal

---

## 🏛️ Course Faculty
- Dr. Martin Parmar (CSPIT-CE)
- Dr. Mrugendra Rahevar (CSPIT-CE)
- Ms. Srushti Gajjar (CSPIT-CSE)
- Ms. Vaibhavi Patel (CSPIT-CSE)
- Mr. Ravi Patel (CSPIT-IT)
- Mr. Dhaval Patel (CSPIT-IT)
- Dr. Jalpesh Vasa (CSPIT-AIML)
- Ms. Priyanka Padhiyar (DEPSTAR-CE)
- Mr. Jay Patel (DEPSTAR-CE)
- Mr. Vaibhav Matalia (DEPSTAR-CSE)
- Ms. Pooja Singh Chaudhary (DEPSTAR-IT)
- Dr. Nikita Bhatt (Head-CE, CSPIT)

---

## 📁 Repository Structure & Practicals Summary

| Practical | Folder | Title & Core Concepts | Primary Files |
| :---: | :---: | :--- | :--- |
| **01** | [`P-1`](file:///e:/CLG/WEB/Prac/P-1) | Project Initiation, Requirement Analysis, Sitemap, Wireframe & GitHub | `sitemap.html`, `wireframes.html`, `README.md` |
| **02** | [`P-2`](file:///e:/CLG/WEB/Prac/P-2) | Semantic HTML5 Pages with Accessibility-Ready Structure | 11 HTML5 pages, `accessibility_checklist.html` |
| **03** | [`P-3`](file:///e:/CLG/WEB/Prac/P-3) | Responsive UI Design using CSS Grid and Flexbox (Mobile-First) | CSS Grid & Flex layouts across 3 viewports |
| **04** | [`P-4`](file:///e:/CLG/WEB/Prac/P-4) | JavaScript DOM Manipulation, Events & UI Interactivity | `script.js` (Accordion, Modal, Slider, Toast, Preferences) |
| **05** | [`P-5`](file:///e:/CLG/WEB/Prac/P-5) | Registration Form with Frontend Validation & Canvas CAPTCHA | `validation.js` (Regex, Password Meter, CAPTCHA) |
| **06** | [`P-6`](file:///e:/CLG/WEB/Prac/P-6) | Rendering External JSON using Fetch API, Search, Filter & Pagination | `app.js`, `data/events.json`, `data/students.json` |
| **07** | [`P-7`](file:///e:/CLG/WEB/Prac/P-7) | PHP Form Processing, Server-Side Sanitization & CSV/JSON Storage | `index.php`, `process.php`, `records.php` |
| **08** | [`P-8`](file:///e:/CLG/WEB/Prac/P-8) | MySQL Schema Design, ER Model, PDO & Stored Procedures | `schema.sql`, `db.php`, `test_db.php`, `index.php` |
| **09** | [`P-9`](file:///e:/CLG/WEB/Prac/P-9) | Secure Registration, Duplicate Check & BCRYPT Password Hashing | `process_register.php`, `users_list.php` |
| **10** | [`P-10`](file:///e:/CLG/WEB/Prac/P-10) | Sessions, Role-Based Access (RBAC), Timeout & Remember-Me Token | `login.php`, `auth.php`, `session_check.php`, `logout.php` |
| **11** | [`P-11`](file:///e:/CLG/WEB/Prac/P-11) | Student Management CRUD with Search, Filter & CSV Export | `index.php`, `add_student.php`, `export_csv.php` |
| **12** | [`P-12`](file:///e:/CLG/WEB/Prac/P-12) | Event Management CRUD with Validated Poster Upload (2MB MIME) | `index.php`, `add_event.php`, `uploads/.htaccess` |
| **13** | [`P-13`](file:///e:/CLG/WEB/Prac/P-13) | PHP REST JSON API and AJAX/Fetch-Based CRUD (Zero Reload) | `api/events.php`, `index.html` (Network Inspector) |
| **14** | [`P-14`](file:///e:/CLG/WEB/Prac/P-14) | Admin Dashboard with Dynamic Menu, Analytics Chart & Audit Log | `index.php`, `toggle_status.php` |
| **15** | [`P-15`](file:///e:/CLG/WEB/Prac/P-15) | Capstone Integration, Testing Matrix & Local Deployment | `index.php`, `DEPLOYMENT_GUIDE.md`, `TEST_REPORT.md` |

---

## 🎨 Single Common Design System
All website-based practicals share a central, responsive design system located at:
- **CSS Design System:** [`shared/css/common.css`](file:///e:/CLG/WEB/Prac/shared/css/common.css)
- **Shared JavaScript Engine:** [`shared/js/common.js`](file:///e:/CLG/WEB/Prac/shared/js/common.js)
  - Features: Light/Dark theme switching with `localStorage` memory, mobile hamburger drawer, accessible modal controllers, and dynamic toast notifications.

---

## 🚀 How to Run Locally

### Method A: Built-in PHP Development Server (Quickest)
Run the following command from the root directory:
```bash
php -S localhost:8000
```
Then open `http://localhost:8000/index.html` in your browser.

### Method B: XAMPP Apache & MySQL
1. Place or link this folder inside `C:\xampp\htdocs\studenthub`.
2. Start **Apache** and **MySQL** from XAMPP Control Panel.
3. Import the database schema:
   - Open phpMyAdmin (`http://localhost/phpmyadmin`).
   - Create `studenthub_db` and import `P-8/schema.sql` (or `P-15/database/studenthub.sql`).
4. Access `http://localhost/studenthub/index.html`.
