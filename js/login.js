document.getElementById('loginForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn   = document.getElementById('loginBtn');
  const alert = document.getElementById('loginAlert');

  // Basic check
  const email = this.querySelector('[name="email"]').value.trim();
  const pwd   = this.querySelector('[name="password"]').value;
  if (!email || !pwd) {
    alert.innerHTML = '<div class="alert alert-warning">Please fill in all fields.</div>';
    return;
  }

  btn.querySelector('.btn-text').classList.add('d-none');
  btn.querySelector('.btn-spinner').classList.remove('d-none');
  btn.disabled = true;
  alert.innerHTML = '';

  try {
    const siteUrl = this.dataset.siteUrl;
    const res = await fetch(siteUrl + '/auth/login.php', {
      method: 'POST',
      body: new FormData(this)
    });
    const json = await res.json();

    if (json.success) {
      alert.innerHTML = `<div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>${json.message}</div>`;
      setTimeout(() => window.location.href = json.redirect, 1000);
    } else {
      alert.innerHTML = `<div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i>${json.message}</div>`;
      if (json.redirect) {
        setTimeout(() => window.location.href = json.redirect, 2000);
      }
      if (json.locked) {
        // Show lockout countdown (3 min = 180s)
        const banner   = document.getElementById('lockoutBanner');
        const msg      = document.getElementById('lockoutMsg');
        const progress = document.getElementById('lockoutProgress');
        banner.classList.remove('d-none');
        msg.textContent = ' Please wait 3 minutes before trying again.';
        let secs = 180;
        const interval = setInterval(() => {
          secs--;
          progress.style.width = ((secs / 180) * 100) + '%';
          if (secs <= 0) {
            clearInterval(interval);
            banner.classList.add('d-none');
            btn.disabled = false;
          }
        }, 1000);
      }
    }
  } catch(err) {
    alert.innerHTML = `<div class="alert alert-danger">Network error. Please try again.</div>`;
  } finally {
    btn.querySelector('.btn-text').classList.remove('d-none');
    btn.querySelector('.btn-spinner').classList.add('d-none');
    if (!btn.dataset.locked) btn.disabled = false;
  }
});
