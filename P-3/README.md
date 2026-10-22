# Practical 3: Responsive UI Design using CSS2.0/CSS3.0, CSS Grid and Flexbox

**Course:** Web Development Frameworks (ITUE203) | **Semester:** 3rd, 2026-27 (ODD)  
**Institution:** Charotar University of Science and Technology (CHARUSAT)  
**CO Mapping:** CO2 | **PO Mapping:** PO1(3), PO2(2), PO3(3), PO4(2), PO5(3), PO9(1), PO10(3)

---

## 1. Problem Definition & Objectives
Design responsive, mobile-first layouts for StudentHub pages (Home, About, Registration, Dashboard, and Events) using modern CSS Grid and Flexbox.

### Key Techniques
- **CSS Grid:** Multi-dimensional layout for dashboards (`grid-template-columns: repeat(auto-fit, minmax(280px, 1fr))`), KPI metrics cards, and event listings.
- **Flexbox:** One-dimensional alignment for header navigation, button toolbars, and card headers/footers (`display: flex; justify-content: space-between; align-items: center`).
- **Mobile-First Breakpoints:**
  - Mobile Phones: `< 640px`
  - Tablets: `640px - 1024px`
  - Desktop Displays: `> 1024px`

---

## 2. Key Analysis Questions Answered

### Q1: How does the layout adapt for mobile, tablet, and desktop screens?
- **Mobile (< 640px):** Single-column stacked layouts, hamburger navigation drawer, horizontal scroll prevention, full-width buttons.
- **Tablet (640px - 1024px):** 2-column card grids, condensed sidebars, flexible form rows.
- **Desktop (> 1024px):** 3 or 4-column grids, fixed sidebars, multi-column dashboard tables and schedule widgets.

### Q2: Which layout technique is used and why?
- **CSS Grid** is used for page-level multi-column layouts (dashboard widgets, event galleries) because it controls both rows and columns simultaneously.
- **Flexbox** is used for navigation bars, card internal alignments, and action button groups because it excels at content-based 1D distribution and wrapping.
