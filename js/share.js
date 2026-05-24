// Copy individual caption by element ID
function copyCaption(elId, btn) {
  const el = document.getElementById(elId);
  if (!el) return;
  navigator.clipboard.writeText(el.textContent).then(() => {
    const original = btn ? btn.innerHTML : '';
    if (btn) { btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Copied!'; btn.style.background='#00c853'; }
    setTimeout(() => { if (btn) { btn.innerHTML = original; btn.style.background = ''; } }, 2000);
    showToast('Caption copied to clipboard! 📋', 'success');
  });
}

// Copy site link
function copyLink() {
  const input = document.getElementById('copyLinkInput');
  const btn   = document.getElementById('copyLinkBtn');
  navigator.clipboard.writeText(input.value).then(() => {
    btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Copied!';
    btn.style.background = '#00c853';
    showToast('Link copied to clipboard!', 'success');
    setTimeout(() => {
      btn.innerHTML = '<i class="bi bi-clipboard me-2"></i>Copy Link';
      btn.style.background = '';
    }, 2000);
  });
}

// Copy Instagram/TikTok caption (uses the last caption card)
function copyTikTokCaption() {
  const captions = document.querySelectorAll('.caption-text');
  if (!captions.length) return;
  const last = captions[captions.length - 1];
  navigator.clipboard.writeText(last.textContent).then(() => {
    showToast('TikTok caption copied! 📋', 'success');
  });
}
