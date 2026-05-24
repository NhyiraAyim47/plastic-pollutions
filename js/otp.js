// js/otp.js – OTP input handling, countdown timer, verify & resend
'use strict';

function initOTPPage({ verifyUrl, expiryMins = 10 }) {
  const digits    = document.querySelectorAll('.otp-digit');
  const hidden    = document.getElementById('otpHidden');
  const verifyBtn = document.getElementById('verifyBtn');
  const resendBtn = document.getElementById('resendBtn');
  const timerEl   = document.getElementById('otpTimer');
  const alertEl   = document.getElementById('otpAlert');

  if (!digits.length) return;

  // ─── Digit input handling ──────────────────────────────────
  digits.forEach((input, i) => {
    input.addEventListener('input', (e) => {
      // Only digits
      input.value = input.value.replace(/\D/g, '').slice(-1);
      if (input.value) {
        input.classList.add('filled');
        if (i < digits.length - 1) digits[i + 1].focus();
      } else {
        input.classList.remove('filled');
      }
      syncHidden();
    });

    input.addEventListener('keydown', (e) => {
      if (e.key === 'Backspace' && !input.value && i > 0) {
        digits[i - 1].focus();
        digits[i - 1].value = '';
        digits[i - 1].classList.remove('filled');
        syncHidden();
      }
      if (e.key === 'ArrowLeft'  && i > 0)              digits[i - 1].focus();
      if (e.key === 'ArrowRight' && i < digits.length-1) digits[i + 1].focus();
    });

    // Handle paste
    input.addEventListener('paste', (e) => {
      e.preventDefault();
      const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
      if (pasted.length === digits.length) {
        pasted.split('').forEach((ch, idx) => {
          digits[idx].value = ch;
          digits[idx].classList.add('filled');
        });
        syncHidden();
        digits[digits.length - 1].focus();
      }
    });
  });

  function syncHidden() {
    const otp = Array.from(digits).map(d => d.value).join('');
    hidden.value = otp;
    verifyBtn.disabled = otp.length !== digits.length;
  }

  // ─── Countdown timer ───────────────────────────────────────
  let totalSeconds = expiryMins * 60;
  let timerInterval = null;

  function startTimer() {
    clearInterval(timerInterval);
    totalSeconds = expiryMins * 60;
    resendBtn.disabled = true;

    timerInterval = setInterval(() => {
      totalSeconds--;
      const m = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
      const s = String(totalSeconds % 60).padStart(2, '0');
      if (timerEl) timerEl.textContent = `${m}:${s}`;

      if (totalSeconds <= 60 && timerEl) timerEl.style.color = '#f44336';
      if (totalSeconds <= 0) {
        clearInterval(timerInterval);
        if (timerEl) timerEl.textContent = 'Expired';
        verifyBtn.disabled = true;
        resendBtn.disabled = false;
        showAlert('Your OTP has expired. Please request a new one.', 'warning');
      }
    }, 1000);
  }

  startTimer();

  // ─── Resend countdown (30s cooldown) ──────────────────────
  let resendCooldown = null;
  function startResendCooldown(secs = 30) {
    let rem = secs;
    const span = document.getElementById('resendCountdown');
    resendBtn.disabled = true;
    resendCooldown = setInterval(() => {
      rem--;
      if (span) span.textContent = `(${rem}s)`;
      if (rem <= 0) {
        clearInterval(resendCooldown);
        resendBtn.disabled = false;
        if (span) span.textContent = '';
      }
    }, 1000);
  }

  // ─── Verify button ─────────────────────────────────────────
  verifyBtn.addEventListener('click', async () => {
    const otp = hidden.value;
    if (otp.length !== digits.length) return;

    verifyBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Verifying...';
    verifyBtn.disabled  = true;
    clearAlert();

    const fd = new FormData();
    fd.append('action', 'verify');
    fd.append('otp', otp);

    try {
      const res  = await fetch(verifyUrl, { method: 'POST', body: fd });
      const json = await res.json();

      if (json.success) {
        showAlert(json.message, 'success');
        digits.forEach(d => { d.disabled = true; d.style.borderColor = '#00c853'; });
        verifyBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Verified!';
        verifyBtn.style.background = 'linear-gradient(135deg,#00c853,#00a040)';
        clearInterval(timerInterval);
        setTimeout(() => window.location.href = json.redirect, 1500);
      } else {
        showAlert(json.message, 'danger');
        // Shake animation
        digits.forEach(d => {
          d.value = '';
          d.classList.remove('filled');
          d.style.borderColor = '#f44336';
          setTimeout(() => d.style.borderColor = '', 1500);
        });
        hidden.value = '';
        verifyBtn.innerHTML = '<i class="bi bi-shield-check me-2"></i>Verify Email';
        verifyBtn.disabled  = true;
        digits[0].focus();
      }
    } catch(e) {
      showAlert('Network error. Please try again.', 'danger');
      verifyBtn.innerHTML = '<i class="bi bi-shield-check me-2"></i>Verify Email';
      verifyBtn.disabled  = false;
    }
  });

  // ─── Resend button ─────────────────────────────────────────
  resendBtn.addEventListener('click', async () => {
    resendBtn.disabled   = true;
    resendBtn.textContent = 'Sending...';
    clearAlert();

    const fd = new FormData();
    fd.append('action', 'resend');

    try {
      const res  = await fetch(verifyUrl, { method: 'POST', body: fd });
      const json = await res.json();

      if (json.success) {
        showAlert('A new OTP has been sent to your email.', 'success');
        // Reset input
        digits.forEach(d => { d.value = ''; d.classList.remove('filled'); });
        hidden.value = '';
        verifyBtn.disabled = true;
        digits[0].focus();
        // Restart timer
        if (timerEl) timerEl.style.color = '';
        startTimer();
        startResendCooldown(30);
      } else {
        showAlert(json.message, 'danger');
        resendBtn.disabled = false;
      }
    } catch(e) {
      showAlert('Network error. Could not resend OTP.', 'danger');
      resendBtn.disabled = false;
    }

    resendBtn.textContent = 'Resend OTP';
  });

  // ─── Alert helpers ─────────────────────────────────────────
  function showAlert(msg, type) {
    if (!alertEl) return;
    const icons = { success: 'check-circle', danger: 'exclamation-triangle', warning: 'exclamation-circle', info: 'info-circle' };
    alertEl.innerHTML = `<div class="alert alert-${type} d-flex align-items-center gap-2">
      <i class="bi bi-${icons[type] || 'info-circle'}"></i><span>${msg}</span></div>`;
  }
  function clearAlert() { if (alertEl) alertEl.innerHTML = ''; }
}

window.initOTPPage = initOTPPage;
