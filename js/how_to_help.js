
document.getElementById('pledgeForm')?.addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn = document.getElementById('pledgeBtn');
  const alertEl = document.getElementById('pledgeAlert');
  btn.querySelector('.btn-text').classList.add('d-none');
  btn.querySelector('.btn-spinner').classList.remove('d-none');
  btn.disabled = true; alertEl.innerHTML='';
  try {
    const siteUrl = document.getElementById('pledgeForm').dataset.siteUrl;
    const res = await fetch(siteUrl + '/pages/how_to_help.php',{method:'POST',body:new FormData(this)});
    const json= await res.json();
    alertEl.innerHTML=`<div class="alert alert-${json.success?'success':'warning'}">${json.message}</div>`;
    if (json.success) {
      this.reset();
      if (json.count) document.getElementById('pledgeCount').textContent = parseInt(json.count).toLocaleString();
    }
  } catch { alertEl.innerHTML='<div class="alert alert-danger">Network error.</div>'; }
  finally { btn.querySelector('.btn-text').classList.remove('d-none'); btn.querySelector('.btn-spinner').classList.add('d-none'); btn.disabled=false; }
});
