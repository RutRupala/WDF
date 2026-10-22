/**
 * Practical 5: Frontend Form Validation, Regex, Password Strength Meter & Canvas CAPTCHA
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT FTE
 */

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('registration-form');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const mobileInput = document.getElementById('mobile');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const courseSelect = document.getElementById('course');
    const yearRadios = document.getElementsByName('year');
    const genderRadios = document.getElementsByName('gender');
    const termsCheck = document.getElementById('terms');
    const captchaInput = document.getElementById('captcha-input');
    const captchaCanvas = document.getElementById('captcha-canvas');
    const refreshCaptchaBtn = document.getElementById('refresh-captcha');

    let generatedCaptcha = '';

    // --- 1. Canvas CAPTCHA Generator ---
    function generateCaptcha() {
        if (!captchaCanvas) return;
        const ctx = captchaCanvas.getContext('2d');
        const chars = '23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz';
        generatedCaptcha = '';
        for (let i = 0; i < 6; i++) {
            generatedCaptcha += chars.charAt(Math.floor(Math.random() * chars.length));
        }

        ctx.clearRect(0, 0, captchaCanvas.width, captchaCanvas.height);
        ctx.fillStyle = '#f1f5f9';
        ctx.fillRect(0, 0, captchaCanvas.width, captchaCanvas.height);

        // Add noise lines
        for (let i = 0; i < 4; i++) {
            ctx.strokeStyle = `rgba(${Math.random()*150},${Math.random()*150},${Math.random()*150},0.5)`;
            ctx.beginPath();
            ctx.moveTo(Math.random() * captchaCanvas.width, Math.random() * captchaCanvas.height);
            ctx.lineTo(Math.random() * captchaCanvas.width, Math.random() * captchaCanvas.height);
            ctx.stroke();
        }

        // Draw CAPTCHA characters with varied tilt
        ctx.font = 'bold 22px "JetBrains Mono", monospace';
        for (let i = 0; i < generatedCaptcha.length; i++) {
            ctx.save();
            ctx.fillStyle = '#1e293b';
            ctx.translate(20 + i * 22, 28);
            ctx.rotate((Math.random() - 0.5) * 0.4);
            ctx.fillText(generatedCaptcha[i], 0, 0);
            ctx.restore();
        }
    }

    if (refreshCaptchaBtn) {
        refreshCaptchaBtn.addEventListener('click', generateCaptcha);
        generateCaptcha();
    }

    // --- 2. Helper validation displays ---
    function setFieldState(input, isValid, message) {
        const errorEl = document.getElementById(`${input.id}-error`);
        if (isValid) {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            if (errorEl) errorEl.style.display = 'none';
        } else {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            if (errorEl) {
                errorEl.textContent = message;
                errorEl.style.display = 'block';
            }
        }
        return isValid;
    }

    // --- 3. Regex Validations ---
    function validateName() {
        const val = nameInput.value.trim();
        const regex = /^[A-Za-z\s]{3,50}$/;
        if (!val) return setFieldState(nameInput, false, 'Full name is required.');
        if (!regex.test(val)) return setFieldState(nameInput, false, 'Name must contain only letters and spaces (min 3 chars).');
        return setFieldState(nameInput, true);
    }

    function validateEmail() {
        const val = emailInput.value.trim();
        const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!val) return setFieldState(emailInput, false, 'Email address is required.');
        if (!regex.test(val)) return setFieldState(emailInput, false, 'Enter a valid email address (e.g., student@charusat.edu.in).');
        return setFieldState(emailInput, true);
    }

    function validateMobile() {
        const val = mobileInput.value.trim();
        const regex = /^[6-9]\d{9}$/;
        if (!val) return setFieldState(mobileInput, false, 'Mobile number is required.');
        if (!regex.test(val)) return setFieldState(mobileInput, false, 'Enter a valid 10-digit Indian mobile number starting with 6-9.');
        return setFieldState(mobileInput, true);
    }

    // --- 4. Password Strength Meter ---
    function evaluatePassword() {
        const val = passwordInput.value;
        const meterFill = document.getElementById('password-meter-fill');
        const meterText = document.getElementById('password-meter-text');

        const rules = {
            length: val.length >= 8,
            upper: /[A-Z]/.test(val),
            lower: /[a-z]/.test(val),
            number: /[0-9]/.test(val),
            special: /[@$!%*?&#^_-]/.test(val)
        };

        // Update requirement list icons
        Object.keys(rules).forEach(rule => {
            const el = document.getElementById(`rule-${rule}`);
            if (el) {
                if (rules[rule]) {
                    el.classList.add('valid');
                    el.querySelector('.icon').textContent = '✓';
                } else {
                    el.classList.remove('valid');
                    el.querySelector('.icon').textContent = '✗';
                }
            }
        });

        const passedCount = Object.values(rules).filter(Boolean).length;
        let strength = 'Weak';
        let color = 'var(--danger)';
        let percent = (passedCount / 5) * 100;

        if (passedCount <= 2) {
            strength = 'Weak';
            color = 'var(--danger)';
        } else if (passedCount <= 4) {
            strength = 'Moderate';
            color = 'var(--warning)';
        } else {
            strength = 'Strong';
            color = 'var(--success)';
        }

        if (meterFill) {
            meterFill.style.width = `${percent}%`;
            meterFill.style.backgroundColor = color;
        }
        if (meterText) {
            meterText.textContent = `Strength: ${strength}`;
            meterText.style.color = color;
        }

        return passedCount >= 4;
    }

    function validateConfirmPassword() {
        const val = confirmPasswordInput.value;
        if (!val) return setFieldState(confirmPasswordInput, false, 'Please confirm your password.');
        if (val !== passwordInput.value) return setFieldState(confirmPasswordInput, false, 'Passwords do not match.');
        return setFieldState(confirmPasswordInput, true);
    }

    // Live Event Listeners
    if (nameInput) nameInput.addEventListener('input', validateName);
    if (emailInput) emailInput.addEventListener('input', validateEmail);
    if (mobileInput) mobileInput.addEventListener('input', validateMobile);
    if (passwordInput) passwordInput.addEventListener('input', evaluatePassword);
    if (confirmPasswordInput) confirmPasswordInput.addEventListener('input', validateConfirmPassword);

    // Form Submission Handler
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const isNameValid = validateName();
            const isEmailValid = validateEmail();
            const isMobileValid = validateMobile();
            const isPasswordValid = evaluatePassword();
            const isConfirmValid = validateConfirmPassword();

            // Course selection validation
            const isCourseValid = !!courseSelect.value;
            setFieldState(courseSelect, isCourseValid, 'Please select your academic course.');

            // Year radio validation
            let yearSelected = false;
            for (let r of yearRadios) { if (r.checked) yearSelected = true; }
            const yearError = document.getElementById('year-error');
            if (yearError) yearError.style.display = yearSelected ? 'none' : 'block';

            // Gender validation
            let genderSelected = false;
            for (let r of genderRadios) { if (r.checked) genderSelected = true; }
            const genderError = document.getElementById('gender-error');
            if (genderError) genderError.style.display = genderSelected ? 'none' : 'block';

            // Terms validation
            const isTermsValid = termsCheck.checked;
            const termsError = document.getElementById('terms-error');
            if (termsError) termsError.style.display = isTermsValid ? 'none' : 'block';

            // CAPTCHA check
            const isCaptchaValid = captchaInput.value.trim().toLowerCase() === generatedCaptcha.toLowerCase();
            setFieldState(captchaInput, isCaptchaValid, 'Incorrect CAPTCHA code. Please try again.');

            if (isNameValid && isEmailValid && isMobileValid && isPasswordValid && isConfirmValid && isCourseValid && yearSelected && genderSelected && isTermsValid && isCaptchaValid) {
                window.showToast('Registration submitted successfully with verified frontend validation!', 'success');
                const successBox = document.getElementById('form-success-banner');
                if (successBox) {
                    successBox.style.display = 'block';
                    form.reset();
                    generateCaptcha();
                }
            } else {
                window.showToast('Please correct highlighted validation errors.', 'danger');
            }
        });
    }
});
