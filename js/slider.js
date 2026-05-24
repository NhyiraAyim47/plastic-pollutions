// js/slider.js – Hero image slider logic
'use strict';

document.addEventListener('DOMContentLoaded', () => {
  const slides    = document.querySelectorAll('.hero-slider .slide');
  const dotsWrap  = document.getElementById('sliderDots');
  const prevBtn   = document.querySelector('.slider-prev');
  const nextBtn   = document.querySelector('.slider-next');

  if (!slides.length) return;

  let current  = 0;
  let timer    = null;
  const DELAY  = 5000;

  // Build dots
  slides.forEach((_, i) => {
    const dot = document.createElement('button');
    dot.className = 'dot' + (i === 0 ? ' active' : '');
    dot.setAttribute('aria-label', `Slide ${i + 1}`);
    dot.addEventListener('click', () => goTo(i));
    dotsWrap.appendChild(dot);
  });

  const dots = () => document.querySelectorAll('.dot');

  function goTo(index) {
    slides[current].classList.remove('active');
    dots()[current].classList.remove('active');
    current = (index + slides.length) % slides.length;
    slides[current].classList.add('active');
    dots()[current].classList.add('active');
    resetTimer();
  }

  function next() { goTo(current + 1); }
  function prev() { goTo(current - 1); }

  function resetTimer() {
    clearInterval(timer);
    timer = setInterval(next, DELAY);
  }

  // Controls
  if (nextBtn) nextBtn.addEventListener('click', () => { next(); });
  if (prevBtn) prevBtn.addEventListener('click', () => { prev(); });

  // Keyboard navigation
  document.addEventListener('keydown', e => {
    if (e.key === 'ArrowLeft')  prev();
    if (e.key === 'ArrowRight') next();
  });

  // Touch swipe
  let touchStartX = 0;
  const sliderEl = document.querySelector('.hero-slider');
  if (sliderEl) {
    sliderEl.addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; }, { passive: true });
    sliderEl.addEventListener('touchend', e => {
      const diff = touchStartX - e.changedTouches[0].clientX;
      if (Math.abs(diff) > 50) { diff > 0 ? next() : prev(); }
    });
  }

  // Auto-advance
  resetTimer();
});
