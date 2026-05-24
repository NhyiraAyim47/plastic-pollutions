document.getElementById('changePwdForm').addEventListener('submit', async function(e){
  e.preventDefault();
  const btn = this.querySelector('[type="submit"]');
  const alertEl = document.getElementById('pwdAlert');
  btn.querySelector('.btn-text').classList.add('d-none');
  btn.querySelector('.btn-spinner').classList.remove('d-none');
  btn.disabled = true;
  try {
    const siteUrl = this.dataset.siteUrl;
    const res = await fetch(siteUrl + '/dashboard/change_password.php',{method:'POST',body:new FormData(this)});
    const json = await res.json();
    alertEl.innerHTML = `<div class="alert alert-${json.success?'success':'danger'}">${json.message}</div>`;
    if (json.success) { this.reset(); setTimeout(()=>window.location.href='<?= SITE_URL ?>/dashboard/index.php',1500); }
  } catch(e){ alertEl.innerHTML='<div class="alert alert-danger">Network error.</div>'; }
  finally { btn.querySelector('.btn-text').classList.remove('d-none'); btn.querySelector('.btn-spinner').classList.add('d-none'); btn.disabled=false; }
});
// Strength bar for new password field
document.getElementById('newPwd')?.addEventListener('input', function(){
  const bar = document.getElementById('newPwdBar');
  if(!bar) return;
  const score = passwordStrength(this.value);
  const w=['0%','25%','50%','75%','100%']; const c=['transparent','#f44336','#ff9800','#2196f3','#00c853'];
  bar.style.cssText=`width:${w[score]};background:${c[score]};height:4px;border-radius:2px;transition:all .3s`;
});