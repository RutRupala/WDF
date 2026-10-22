/**
 * Practical 4: JavaScript DOM Manipulation, Event Handling, and UI Interactivity
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT FTE
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Collapsible FAQ Accordion
    const faqButtons = document.querySelectorAll('.p4-faq-header');
    faqButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const body = this.nextElementSibling;
            const isOpen = body.style.display === 'block';
            
            // Close other items (accordion behavior)
            document.querySelectorAll('.p4-faq-body').forEach(b => b.style.display = 'none');
            document.querySelectorAll('.p4-faq-header').forEach(h => h.classList.remove('active'));

            if (!isOpen) {
                body.style.display = 'block';
                this.classList.add('active');
            }
        });
    });

    // 2. Modal Popup
    const openModalBtn = document.getElementById('open-demo-modal');
    const closeModalBtn = document.getElementById('close-demo-modal');
    const modal = document.getElementById('p4-modal');

    if (openModalBtn && modal) {
        openModalBtn.addEventListener('click', () => {
            modal.classList.add('active');
        });
    }
    if (closeModalBtn && modal) {
        closeModalBtn.addEventListener('click', () => {
            modal.classList.remove('active');
        });
    }

    // 3. Image / Content Slider
    const slides = document.querySelectorAll('.p4-slide');
    const prevBtn = document.getElementById('p4-prev-slide');
    const nextBtn = document.getElementById('p4-next-slide');
    let currentSlide = 0;

    function showSlide(index) {
        slides.forEach((s, i) => {
            s.style.display = i === index ? 'block' : 'none';
        });
        const counter = document.getElementById('p4-slide-counter');
        if (counter) counter.textContent = `Slide ${index + 1} of ${slides.length}`;
    }

    if (slides.length > 0) {
        showSlide(currentSlide);
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                currentSlide = (currentSlide + 1) % slides.length;
                showSlide(currentSlide);
            });
        }
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                showSlide(currentSlide);
            });
        }
    }

    // 4. Notification Banner / Toast
    const triggerToastBtn = document.getElementById('p4-trigger-toast');
    if (triggerToastBtn) {
        triggerToastBtn.addEventListener('click', () => {
            const types = ['success', 'info', 'warning'];
            const randomType = types[Math.floor(Math.random() * types.length)];
            window.showToast(`Dynamic Alert: Notification generated at ${new Date().toLocaleTimeString()}!`, randomType);
        });
    }

    // 5. User Preference Persistence (localStorage)
    const fontSizeSelect = document.getElementById('p4-font-size');
    if (fontSizeSelect) {
        const savedSize = localStorage.getItem('p4-user-font-size') || '16px';
        document.body.style.fontSize = savedSize;
        fontSizeSelect.value = savedSize;

        fontSizeSelect.addEventListener('change', function () {
            document.body.style.fontSize = this.value;
            localStorage.setItem('p4-user-font-size', this.value);
            window.showToast(`Font preference saved: ${this.value}`, 'info', 2000);
        });
    }
});
