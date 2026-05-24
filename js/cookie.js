// js/cookie.js – Cookie consent notification
'use strict';

document.addEventListener('DOMContentLoaded', () => {
  const banner    = document.getElementById('cookieBanner');
  const acceptBtn = document.getElementById('acceptCookies');
  if (!banner || !acceptBtn) return;

  // Check if already accepted
  if (!getCookie('pp_cookie_consent')) {
    setTimeout(() => banner.classList.remove('d-none'), 1500);
  }

  acceptBtn.addEventListener('click', () => {
    setCookie('pp_cookie_consent', '1', 365);
    banner.style.animation = 'none';
    banner.style.transition = 'transform .4s ease, opacity .4s ease';
    banner.style.transform  = 'translateY(100%)';
    banner.style.opacity    = '0';
    setTimeout(() => banner.remove(), 450);
    if (window.showToast) showToast('Cookie preferences saved!', 'success', 2500);
  });
});

function setCookie(name, value, days) {
  const d = new Date();
  d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
  document.cookie = `${name}=${value};expires=${d.toUTCString()};path=/;SameSite=Lax`;
}

function getCookie(name) {
  const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
  return match ? match[2] : null;
}
