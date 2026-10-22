# Practical 2: Semantic HTML5 Pages with Accessibility-Ready Structure

**Course:** Web Development Frameworks (ITUE203)  
**Semester:** 3rd, 2026-27 (ODD)  
**Institution:** Charotar University of Science and Technology (CHARUSAT)  
**CO Mapping:** CO1, CO2 | **PO Mapping:** PO1(3), PO2(2), PO3(3), PO4(2), PO5(3), PO9(1), PO10(3)

---

## 1. Objectives & Scope
Develop static HTML5 skeletons for at least 10 interconnected StudentHub pages incorporating:
1. **Semantic HTML5 Structural Tags:** `<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<aside>`, `<footer>`, `<figure>`, `<figcaption>`, `<time>`, `<address>`.
2. **Accessibility Features (WCAG 2.1 AA Compliance):**
   - Skip-to-content bypass mechanism (`.skip-link`) for keyboard navigation.
   - Form `<label>` tags explicitly linked to form control `id`s.
   - Semantic heading hierarchy with exactly one `<h1>` per page.
   - Meaningful `alt` text on images and SVG icons with `aria-hidden="true"`.
   - Accessible breadcrumb navigation with `aria-label="Breadcrumb"`.
   - Distinct focus states for keyboard users.

---

## 2. Pages Developed (11 Interconnected Pages)
1. `index.html` - Semantic Landing Page with hero section, latest news article, aside sidebar
2. `about.html` - Institutional Vision, Department Mission, Leadership Profile
3. `register.html` - Accessible Student Registration form with labelled controls and helper text
4. `login.html` - Accessible Authentication screen with password visibility indicator
5. `dashboard.html` - Student Academic Dashboard with schedule, grades, and notice widgets
6. `events.html` - Campus Events Directory with `<article>` cards, dates, and venues
7. `profile.html` - Student User Profile with academic details and contact cards
8. `contact.html` - Department Contact Directory with `<address>` and inquiry form
9. `admin.html` - Administration Moderation Center with role management and statistics
10. `faq.html` - Categorized student help and FAQ with `<details>` and `<summary>` tags
11. `feedback.html` - Academic feedback form with accessible rating controls

---

## 3. Analysis & Key Questions Answered

### Q1: Are semantic tags used properly?
Yes. Rather than generic `<div>` soup:
- `<header>` encapsulates top brand banner and primary navigation.
- `<nav>` contains primary and secondary navigation menus.
- `<main>` identifies the central unique content of each page.
- `<section>` groups thematic content blocks (e.g., hero, highlights).
- `<article>` wraps independent, reusable compositions (e.g., news items, event listings).
- `<aside>` holds tangential content like announcements and upcoming deadlines.
- `<footer>` contains site metadata, copyright, and global links.

### Q2: Are form labels, headings, image alt text, and navigation links accessible?
- Every form `<input>` has a matching `<label for="...">`.
- Headings follow strict non-skipping descending hierarchy: `<h1>` -> `<h2>` -> `<h3>`.
- All `<img>` tags supply contextual, descriptive `alt` attributes. Decorative elements have empty `alt=""` or `aria-hidden="true"`.
- Interactive elements possess clear text or `aria-label` attributes.

### Q3: Is the page structure consistent across all pages?
All pages share the unified design system in `../shared/css/common.css`, featuring a consistent header, responsive breadcrumb trail, main content wrapper, and standardized footer.
