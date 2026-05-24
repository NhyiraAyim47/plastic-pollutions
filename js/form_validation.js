// js/form_validation.js – Client-side form validation utilities
'use strict';

/* ─── Generic auth form validator ───────────────────────── */
function validateAuthForm(form) {
  let valid = true;
  form.querySelectorAll('[required]').forEach(field => {
    clearFieldError(field);
    if (!field.value.trim()) {
      setFieldError(field, 'This field is required.');
      valid = false;
      return;
    }
    if (field.type === 'email' && !isValidEmail(field.value)) {
      setFieldError(field, 'Please enter a valid email address.');
      valid = false;
    }
    if (field.name === 'password' && field.value.length < 8) {
      setFieldError(field, 'Password must be at least 8 characters.');
      valid = false;
    }
    if ((field.name === 'password') && !/^(?=.*[A-Z])(?=.*\d).{8,}$/.test(field.value)) {
      setFieldError(field, 'Password needs 1 uppercase letter and 1 number.');
      valid = false;
    }
    if (field.name === 'confirm_password') {
      const pwd = form.querySelector('[name="password"]');
      if (pwd && field.value !== pwd.value) {
        setFieldError(field, 'Passwords do not match.');
        valid = false;
      }
    }
  });
  return valid;
}

/* ─── Field error helpers ───────────────────────────────── */
function setFieldError(field, message) {
  field.classList.add('is-invalid');
  const fb = field.nextElementSibling;
  if (fb && fb.classList.contains('invalid-feedback')) fb.textContent = message;
  else {
    const div = document.createElement('div');
    div.className = 'invalid-feedback';
    div.textContent = message;
    field.parentNode.insertBefore(div, field.nextSibling);
  }
}
function clearFieldError(field) {
  field.classList.remove('is-invalid');
  field.classList.remove('is-valid');
}
function setFieldSuccess(field) {
  field.classList.remove('is-invalid');
  field.classList.add('is-valid');
}

/* ─── Real-time password strength ───────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
  const pwdInputs = document.querySelectorAll('[name="password"]');
  pwdInputs.forEach(input => {
    const barId   = input.id === 'regPassword'  ? 'regPwdBar'     : 'pwdStrengthBar';
    const labelId = input.id === 'regPassword'  ? 'regPwdStrengthLabel' : null;
    const bar     = document.getElementById(barId);
    const label   = labelId ? document.getElementById(labelId) : null;
    if (!bar) return;

    input.addEventListener('input', () => {
      const score = passwordStrength(input.value);
      const configs = [
        { width:'0%',   color:'transparent', text:'' },
        { width:'25%',  color:'#f44336',     text:'Weak' },
        { width:'50%',  color:'#ff9800',     text:'Fair' },
        { width:'75%',  color:'#2196f3',     text:'Good' },
        { width:'100%', color:'#00c853',     text:'Strong' },
      ];
      const c = configs[score];
      bar.style.width     = c.width;
      bar.style.background = c.color;
      bar.style.transition = 'width .3s ease, background .3s ease';
      if (label) { label.textContent = c.text; label.style.color = c.color; }
    });
  });

  // Real-time confirm password match
  document.querySelectorAll('[name="confirm_password"]').forEach(confirm => {
    confirm.addEventListener('input', () => {
      const form = confirm.closest('form');
      const pwd  = form?.querySelector('[name="password"]');
      if (!pwd) return;
      if (confirm.value === pwd.value && confirm.value.length > 0) {
        setFieldSuccess(confirm);
      } else if (confirm.value.length > 0) {
        setFieldError(confirm, 'Passwords do not match.');
      }
    });
  });

  // Real-time email validation
  document.querySelectorAll('[type="email"]').forEach(input => {
    input.addEventListener('blur', () => {
      if (input.value && !isValidEmail(input.value)) setFieldError(input, 'Please enter a valid email.');
      else if (input.value) setFieldSuccess(input);
    });
  });
});

/* ─── Password strength score (0-4) ─────────────────────── */
function passwordStrength(pw) {
  let score = 0;
  if (!pw) return 0;
  if (pw.length >= 8)                score++;
  if (/[A-Z]/.test(pw))             score++;
  if (/\d/.test(pw))                score++;
  if (/[^A-Za-z0-9]/.test(pw))     score++;
  return Math.min(score, 4);
}

/* ─── Email regex check ─────────────────────────────────── */
function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

/* ─── Contact form validation ───────────────────────────── */
function validateContactForm(form) {
  let valid = true;
  const name    = form.querySelector('[name="name"]');
  const email   = form.querySelector('[name="email"]');
  const subject = form.querySelector('[name="subject"]');
  const message = form.querySelector('[name="message"]');

  [name, email, subject, message].forEach(f => { if (f) clearFieldError(f); });

  if (name  && name.value.trim().length < 2)   { setFieldError(name,  'Name must be at least 2 characters.'); valid = false; }
  if (email && !isValidEmail(email.value))      { setFieldError(email, 'Enter a valid email address.');       valid = false; }
  if (subject && subject.value.trim().length < 4) { setFieldError(subject, 'Please enter a subject.');       valid = false; }
  if (message && message.value.trim().length < 10) { setFieldError(message, 'Message must be at least 10 characters.'); valid = false; }

  return valid;
}

/* ─── Donation form validation ──────────────────────────── */
function validateDonationForm(form) {
  let valid = true;
  const amount = form.querySelector('[name="amount"]');
  if (amount) {
    clearFieldError(amount);
    const val = parseFloat(amount.value);
    if (isNaN(val) || val < 1) { setFieldError(amount, 'Please enter a donation amount of at least GHS 1.'); valid = false; }
    if (val > 100000)           { setFieldError(amount, 'Maximum single donation is GHS 100,000.'); valid = false; }
  }
  return valid;
}

/* Export helpers */
window.validateAuthForm     = validateAuthForm;
window.validateContactForm  = validateContactForm;
window.validateDonationForm = validateDonationForm;
window.setFieldError        = setFieldError;
window.setFieldSuccess      = setFieldSuccess;
window.clearFieldError      = clearFieldError;
window.isValidEmail         = isValidEmail;
