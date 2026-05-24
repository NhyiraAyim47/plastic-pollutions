function loadVideo() {
  const ph = document.getElementById('videoPlaceholder');
  const ve = document.getElementById('videoEmbed');
  const iframe = ve.querySelector('iframe');
  iframe.src = iframe.dataset.src;
  ph.classList.add('d-none');
  ve.classList.remove('d-none');
}

document.getElementById('petitionForm')?.addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn = document.getElementById('petitionBtn');
  const alertEl = document.getElementById('petitionAlert');
  btn.querySelector('.btn-text').classList.add('d-none');
  btn.querySelector('.btn-spinner').classList.remove('d-none');
  btn.disabled = true; alertEl.innerHTML='';
  try {
    const siteUrl = document.getElementById('petitionForm').dataset.siteUrl;
    const res = await fetch(siteUrl + '/pages/what_to_do.php',{method:'POST',body:new FormData(this)});
    const json = await res.json();
    const type = json.success ? 'success' : 'warning';
    alertEl.innerHTML = `<div class="alert alert-${type}">${json.message}</div>`;
    if (json.success && json.count) {
      document.getElementById('petitionCount').textContent = parseInt(json.count).toLocaleString();
      const pct = Math.min(100, (json.count/10000)*100);
      document.getElementById('petitionBar').style.width = pct+'%';
    }
  } catch { alertEl.innerHTML='<div class="alert alert-danger">Network error.</div>'; }
  finally {
    btn.querySelector('.btn-text').classList.remove('d-none');
    btn.querySelector('.btn-spinner').classList.add('d-none');
    btn.disabled=false;
  }
});