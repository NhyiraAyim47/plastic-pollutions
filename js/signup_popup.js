// js/signup_popup.js – Quick sign-up modal form handling
'use strict';

document.addEventListener('DOMContentLoaded', () => {
  const form    = document.getElementById('quickSignupForm');
  const btn     = document.getElementById('quickSignupBtn');
  const alertEl = document.getElementById('quickSignupAlert');
  if (!form) return;

  // Password strength for modal
  const modalPwd = document.getElementById('modalPwd');
  const barEl    = document.getElementById('pwdStrengthBar');
  if (modalPwd && barEl) {
    modalPwd.addEventListener('input', () => {
      const score  = passwordStrength(modalPwd.value);
      const colors = ['transparent','#f44336','#ff9800','#2196f3','#00c853'];
      const widths = ['0%','25%','50%','75%','100%'];
      barEl.style.cssText = `width:${widths[score]};height:4px;background:${colors[score]};border-radius:2px;transition:all .3s ease;`;
    });
  }

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    if (!validateAuthForm(form)) return;

    btn.querySelector('.btn-text').classList.add('d-none');
    btn.querySelector('.btn-spinner').classList.remove('d-none');
    btn.disabled = true;
    alertEl.innerHTML = '';

    const fd       = new FormData(form);
    const siteUrl  = form.dataset.siteUrl;

    try {
      const res  = await fetch(siteUrl + '/auth/register.php', { method: 'POST', body: fd });
      const text = await res.text();
      console.log('Server response:', text);
      const json = JSON.parse(text);

      if (json.success) {
        alertEl.innerHTML = `<div class="alert alert-success">
          <i class="bi bi-check-circle me-2"></i>${json.message}</div>`;
        form.reset();
        setTimeout(() => window.location.href = json.redirect, 2000);
      } else {
        const errs = Array.isArray(json.errors) ? json.errors.join('<br>') : json.message;
        alertEl.innerHTML = `<div class="alert alert-danger">
          <i class="bi bi-exclamation-triangle me-2"></i>${errs}</div>`;
      }
    } catch(err) {
      console.log('Caught error:', err);
      alertEl.innerHTML = `<div class="alert alert-danger">Network error. Please try again.</div>`;
    } finally {
      btn.querySelector('.btn-text').classList.remove('d-none');
      btn.querySelector('.btn-spinner').classList.add('d-none');
      btn.disabled = false;
    }
  });

  // Auto-show modal after 8s for first-time visitors
  function getCookie(n) { const m = document.cookie.match('(^| )' + n + '=([^;]+)'); return m ? m[2] : null; }
  if (!getCookie('pp_cookie_consent') && !getCookie('pp_modal_shown') && !IS_LOGGED_IN) {
    setTimeout(() => {
      const modal = document.getElementById('signUpModal');
      if (modal) {
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
        const d = new Date(); d.setDate(d.getDate() + 1);
        document.cookie = `pp_modal_shown=1;expires=${d.toUTCString()};path=/;SameSite=Lax`;
      }
    }, 8000);
  }
});