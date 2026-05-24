// js/counter.js – Animated environmental stats counters
'use strict';

document.addEventListener('DOMContentLoaded', () => {
  if (!window.IntersectionObserver) {
    // Fallback: set final values immediately
    document.querySelectorAll('.stat-number').forEach(el => setFinalValue(el));
    return;
  }

  const io = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        animateCounter(entry.target);
        io.unobserve(entry.target);
      }
    });
  }, { threshold: 0.3 });

  document.querySelectorAll('.stat-number').forEach(el => io.observe(el));
});

function animateCounter(el) {
  const target   = parseInt(el.dataset.target || '0', 10);
  const prefix   = el.dataset.prefix || '';
  const suffix   = el.dataset.suffix !== undefined ? el.dataset.suffix : '+';
  const duration = 2000;
  const start    = performance.now();

  el.classList.add('counter-active');

  function update(ts) {
    const progress = Math.min((ts - start) / duration, 1);
    // Ease out cubic
    const eased    = 1 - Math.pow(1 - progress, 3);
    const current  = Math.floor(target * eased);
    el.textContent = prefix + current.toLocaleString() + suffix;
    if (progress < 1) requestAnimationFrame(update);
    else el.textContent = prefix + target.toLocaleString() + suffix; // ensure exact final
  }

  requestAnimationFrame(update);
}

function setFinalValue(el) {
  const target = parseInt(el.dataset.target || '0', 10);
  const prefix = el.dataset.prefix || '';
  const suffix = el.dataset.suffix !== undefined ? el.dataset.suffix : '+';
  el.textContent = prefix + target.toLocaleString() + suffix;
}
