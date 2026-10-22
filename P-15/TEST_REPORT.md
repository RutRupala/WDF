# StudentHub Portal End-to-End Test Report & Audit
**Course:** ITUE203 Web Development Frameworks | **CHARUSAT FTE**

---

## 1. Functional Test Matrix

| Test ID | Module | Action / Test Case | Expected Outcome | Result |
| :--- | :--- | :--- | :--- | :--- |
| **TC-01** | Navigation | Click all 11 navbar links | All pages render with identical common design | **PASSED** |
| **TC-02** | Validation | Submit invalid phone `12345` | Error banner displayed; form rejected | **PASSED** |
| **TC-03** | Security | Submit duplicate email in registration | Duplicate check query triggers; user warned | **PASSED** |
| **TC-04** | Auth | Login with wrong password | `password_verify()` returns false; login blocked | **PASSED** |
| **TC-05** | RBAC | Student accesses `admin_dashboard.php` | Middleware blocks access; redirects to login | **PASSED** |
| **TC-06** | Timeout | Inactivity for 15 minutes | Session automatically destroyed on next request | **PASSED** |
| **TC-07** | CRUD | Soft-delete student record | Status changed to Inactive; record retained in DB | **PASSED** |
| **TC-08** | File Upload | Upload 5MB `.exe` as poster | Rejected on server side; MIME & size enforced | **PASSED** |
| **TC-09** | AJAX API | Fetch `GET api/events.php?q=hack` | Returns HTTP 200 with JSON matching search | **PASSED** |
| **TC-10** | CSV Export | Click "Export to CSV" | Downloads `.csv` file with student columns | **PASSED** |

---

## 2. Responsive UI Viewport Verification

| Viewport | Target Device | Layout Behavior | Result |
| :--- | :--- | :--- | :--- |
| **375px × 667px** | Mobile (iPhone SE) | Single-column cards, hamburger drawer nav | **PASSED** |
| **768px × 1024px** | Tablet (iPad Mini) | 2-column grid, condensed sidebars | **PASSED** |
| **1440px × 900px** | Desktop (Laptop / PC) | Full 3/4-column grid, sticky nav, live charts | **PASSED** |

---

## 3. Web Security Audit
- **SQL Injection Prevention:** 100% of SQL queries utilize parameterized PDO prepared statements (`$stmt->execute([$param])`).
- **XSS (Cross-Site Scripting) Defense:** User inputs printed in HTML are sanitized with `htmlspecialchars($var, ENT_QUOTES, 'UTF-8')`.
- **CSRF Defense:** Session tokens generated with `random_bytes(32)` validated with `hash_equals()`.
- **Password Protection:** Cryptographic BCRYPT salted hashing via `password_hash($pwd, PASSWORD_BCRYPT)`.
