/**
 * StudentHub Portal - Unified Shared JavaScript Library
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT FTE
 */

(function () {
    'use strict';

    // --- 1. Immediate Theme Setup (prevents flash of unstyled theme) ---
    const THEME_KEY = 'studenthub-theme';
    const savedTheme = localStorage.getItem(THEME_KEY) || 
        (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    
    document.documentElement.setAttribute('data-theme', savedTheme);

    // Update theme toggle button icon
    function updateThemeIcon(theme) {
        const btns = document.querySelectorAll('.sh-theme-btn, #theme-toggle-btn');
        btns.forEach(btn => {
            btn.innerHTML = theme === 'dark' ? '☀️' : '🌙';
            btn.setAttribute('aria-label', `Switch to ${theme === 'dark' ? 'light' : 'dark'} mode`);
            btn.setAttribute('title', `Switch to ${theme === 'dark' ? 'light' : 'dark'} mode`);
        });
    }

    // Toggle theme function
    window.toggleTheme = function () {
        const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem(THEME_KEY, newTheme);
        updateThemeIcon(newTheme);
        window.showToast?.(`Switched to ${newTheme} mode`, 'info', 2000);
    };

    // --- 2. Toast Notification System ---
    window.showToast = function (message, type = 'info', duration = 3500) {
        let container = document.querySelector('.toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'toast-container';
            container.setAttribute('aria-live', 'polite');
            document.body.appendChild(container);
        }

        const icons = {
            success: '✅',
            danger: '❌',
            warning: '⚠️',
            info: 'ℹ️'
        };

        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <span style="font-size: 1.2rem;">${icons[type] || 'ℹ️'}</span>
            <div style="flex: 1;">${message}</div>
            <button style="background: none; border: none; cursor: pointer; color: inherit; font-size: 1.1rem; padding: 2px;" onclick="this.parentElement.remove()">×</button>
        `;

        container.appendChild(toast);

        if (duration > 0) {
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(10px)';
                toast.style.transition = 'all 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }
    };

    // --- 3. Accessible Modal Dialog Engine ---
    window.openModal = function (modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';

        // Focus trap initial focus
        const focusable = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
        if (focusable.length) focusable[0].focus();
    };

    window.closeModal = function (modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        modal.classList.remove('active');
        document.body.style.overflow = '';
    };

    // Global Modal Backdrop & Esc-key Handler
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.active').forEach(modal => {
                modal.classList.remove('active');
            });
            document.body.style.overflow = '';
        }
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('modal-overlay')) {
            e.target.classList.remove('active');
            document.body.style.overflow = '';
        }
    });

    // --- 4. DOM Initialization On Ready ---
    document.addEventListener('DOMContentLoaded', function () {
        const activeTheme = document.documentElement.getAttribute('data-theme') || 'light';
        updateThemeIcon(activeTheme);

        // Mobile Hamburger Toggle
        const hamburger = document.querySelector('.sh-hamburger');
        const navMenu = document.querySelector('.sh-nav-menu');
        if (hamburger && navMenu) {
            hamburger.addEventListener('click', () => {
                navMenu.classList.toggle('open');
                const isOpen = navMenu.classList.contains('open');
                hamburger.setAttribute('aria-expanded', isOpen);
            });
        }

        // Connect theme toggle buttons
        document.querySelectorAll('.sh-theme-btn, #theme-toggle-btn').forEach(btn => {
            btn.addEventListener('click', window.toggleTheme);
        });

        // Highlight Active Link in Navigation
        const currentPath = window.location.pathname.split('/').pop() || 'index.html';
        document.querySelectorAll('.sh-nav-item a').forEach(link => {
            const href = link.getAttribute('href');
            if (href === currentPath || (currentPath === '' && href === 'index.html')) {
                link.parentElement.classList.add('active');
            }
        });
    });

})();
