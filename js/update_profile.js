document.getElementById('updateProfileForm').addEventListener('submit', async function(e){
  e.preventDefault();
  const btn = this.querySelector('[type="submit"]');
  const alertEl = document.getElementById('profileAlert');
  btn.querySelector('.btn-text').classList.add('d-none');
  btn.querySelector('.btn-spinner').classList.remove('d-none');
  btn.disabled = true;
  try {
    const siteUrl = this.dataset.siteUrl;
    const res = await fetch(siteUrl + '/dashboard/update_profile.php', {method:'POST',body:new FormData(this)});
    const json = await res.json();
    alertEl.innerHTML = json.success
      ? `<div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>${json.message}</div>`
      : `<div class="alert alert-danger">${(json.errors||[json.message]).join('<br>')}</div>`;
    if (json.success) setTimeout(() => window.location.href='<?= SITE_URL ?>/dashboard/index.php', 1500);
  } catch(e) { alertEl.innerHTML = '<div class="alert alert-danger">Network error.</div>'; }
  finally { btn.querySelector('.btn-text').classList.remove('d-none'); btn.querySelector('.btn-spinner').classList.add('d-none'); btn.disabled=false; }
});
setTimeout(() => window.location.href = siteUrl + '/dashboard/index.php', 1500);