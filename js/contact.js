// Char counter
document.querySelector('[name="message"]')?.addEventListener('input', function() {
  document.getElementById('msgCount').textContent = this.value.length;
  if (this.value.length > 1000) this.value = this.value.slice(0, 1000);
});

// Submit
document.getElementById('contactForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  if (!validateContactForm(this)) return;
  const btn     = document.getElementById('contactBtn');
  const alertEl = document.getElementById('contactAlert');
  btn.querySelector('.btn-text').classList.add('d-none');
  btn.querySelector('.btn-spinner').classList.remove('d-none');
  btn.disabled = true; alertEl.innerHTML = '';
  try {
    const siteUrl = this.dataset.siteUrl;
    const res = await fetch(siteUrl + '/pages/contact.php', {method:'POST',body:new FormData(this)});
    const json = await res.json();
    if (json.success) {
      alertEl.innerHTML = `<div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>${json.message}</div>`;
      this.reset(); document.getElementById('msgCount').textContent = '0';
    } else {
      alertEl.innerHTML = `<div class="alert alert-danger">${(json.errors||[json.message]).join('<br>')}</div>`;
    }
  } catch { alertEl.innerHTML = `<div class="alert alert-danger">Network error. Please try again.</div>`; }
  finally {
    btn.querySelector('.btn-text').classList.remove('d-none');
    btn.querySelector('.btn-spinner').classList.add('d-none');
    btn.disabled = false;
  }
});
