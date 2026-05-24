// js/main.js – PlasticPollutions Global JavaScript
'use strict';

/* ─── DOM Ready ─────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
  initNavScroll();
  initBackToTop();
  initScrollReveal();
  initRippleButtons();
  initTogglePassword();
  initLazyImages();
  initPageLoader();
  loadVisitorCount();
});

/* ─── Navbar scroll behaviour ───────────────────────────── */
function initNavScroll() {
  const nav = document.getElementById('mainNav');
  if (!nav) return;
  const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 60);
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
}

/* ─── Back-to-top button ────────────────────────────────── */
function initBackToTop() {
  const btn = document.getElementById('backToTop');
  if (!btn) return;
  window.addEventListener('scroll', () => {
    btn.classList.toggle('visible', window.scrollY > 400);
  }, { passive: true });
  btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}

/* ─── Scroll-reveal (IntersectionObserver) ──────────────── */
function initScrollReveal() {
  if (!window.IntersectionObserver) return;
  const io = new IntersectionObserver((entries) => {
    entries.forEach(el => {
      if (el.isIntersecting) { el.target.classList.add('active', 'visible'); io.unobserve(el.target); }
    });
  }, { threshold: 0.12 });
  document.querySelectorAll('.reveal, .animate-on-scroll').forEach(el => io.observe(el));
}

/* ─── Ripple effect on buttons ──────────────────────────── */
function initRippleButtons() {
  document.querySelectorAll('.btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
      const r    = document.createElement('span');
      const rect = this.getBoundingClientRect();
      const size = Math.max(rect.width, rect.height);
      r.style.cssText = `width:${size}px;height:${size}px;left:${e.clientX-rect.left-size/2}px;top:${e.clientY-rect.top-size/2}px`;
      r.classList.add('ripple');
      this.classList.add('ripple-btn');
      this.appendChild(r);
      setTimeout(() => r.remove(), 700);
    });
  });
}

/* ─── Toggle password visibility ───────────────────────── */
function initTogglePassword() {
  document.querySelectorAll('.toggle-pwd').forEach(btn => {
    btn.addEventListener('click', function() {
      const targetId = this.dataset.target;
      const input    = document.getElementById(targetId);
      if (!input) return;
      const isText   = input.type === 'text';
      input.type     = isText ? 'password' : 'text';
      this.querySelector('i').className = isText ? 'bi bi-eye' : 'bi bi-eye-slash';
    });
  });
}

/* ─── Lazy image loading ─────────────────────────────────── */
function initLazyImages() {
  if (!window.IntersectionObserver) return;
  const io = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const img = entry.target;
        if (img.dataset.src) {
          img.src = img.dataset.src;
          img.classList.add('lazy-loaded');
          io.unobserve(img);
        }
      }
    });
  });
  document.querySelectorAll('img[data-src]').forEach(img => {
    img.classList.add('lazy-loading');
    io.observe(img);
  });
}

/* ─── Page loader ───────────────────────────────────────── */
function initPageLoader() {
  const loader = document.getElementById('pageLoader');
  if (!loader) return;
  window.addEventListener('load', () => {
    setTimeout(() => {
      loader.classList.add('hidden');
      setTimeout(() => loader.remove(), 600);
    }, 300);
  });
}

/* ─── Visitor count (AJAX) ──────────────────────────────── */
async function loadVisitorCount() {
  const el = document.getElementById('footerVisitorCount');
  if (!el) return;
  try {
    const res  = await fetch(SITE_URL + '/api/visitor_count.php');
    const data = await res.json();
    if (data.total) {
      animateNumber(el, 0, data.total, 1200);
    }
  } catch(e) { el.textContent = '5,200+'; }
}

/* ─── Utility: animate a number ────────────────────────── */
function animateNumber(el, from, to, duration = 1500) {
  const start   = performance.now();
  const prefix  = el.dataset.prefix || '';
  const suffix  = el.dataset.suffix || '';
  const update  = (ts) => {
    const progress = Math.min((ts - start) / duration, 1);
    const eased    = 1 - Math.pow(1 - progress, 3);
    const current  = Math.floor(from + (to - from) * eased);
    el.textContent = prefix + current.toLocaleString() + suffix;
    if (progress < 1) requestAnimationFrame(update);
  };
  requestAnimationFrame(update);
}
window.animateNumber = animateNumber;

/* ─── Toast notifications ───────────────────────────────── */
function showToast(message, type = 'success', duration = 4000) {
  let container = document.querySelector('.toast-container-pp');
  if (!container) {
    container = document.createElement('div');
    container.className = 'toast-container-pp';
    document.body.appendChild(container);
  }
  const icons = { success: '✅', error: '❌', info: 'ℹ️', warning: '⚠️' };
  const toast = document.createElement('div');
  toast.className = `toast-pp ${type}`;
  toast.innerHTML = `<span>${icons[type] || '📢'}</span><span>${message}</span>
    <button onclick="this.parentElement.remove()" style="border:none;background:none;cursor:pointer;margin-left:auto;font-size:1rem;color:#999;">&times;</button>`;
  container.appendChild(toast);
  requestAnimationFrame(() => toast.classList.add('show'));
  setTimeout(() => { toast.classList.remove('show'); setTimeout(() => toast.remove(), 400); }, duration);
}
window.showToast = showToast;

/* ─── Format currency ───────────────────────────────────── */
function formatCurrency(amount, currency = 'GHS') {
  return currency + ' ' + parseFloat(amount).toLocaleString('en-GH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
window.formatCurrency = formatCurrency;

/* ─── Debounce helper ───────────────────────────────────── */
function debounce(fn, wait) {
  let t;
  return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), wait); };
}
window.debounce = debounce;
