document.getElementById('registerForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const form    = this;
  const btn     = document.getElementById('registerBtn');
  const alert   = document.getElementById('registerAlert');
  const siteUrl = this.dataset.siteUrl;  // ← moved up here, before everything else
  console.log('Handler reached');
  // Client-side validate
  if (!validateAuthForm(form)) return;
  console.log('Validation passed');

  btn.querySelector('.btn-text').classList.add('d-none');
  btn.querySelector('.btn-spinner').classList.remove('d-none');
  btn.disabled = true;
  alert.innerHTML = '';

  const data = new FormData(form);

  try {
    console.log('Form submitted');
    console.log('siteUrl:', siteUrl);
    console.log('Form data:', data);
    const res  = await fetch(siteUrl + '/auth/register.php', { method: 'POST', body: data });
    const text = await res.text();
    console.log(text);
    const json = JSON.parse(text);

    if (json.success) {
      alert.innerHTML = `<div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>${json.message}</div>`;
      setTimeout(() => window.location.href = json.redirect, 1800);
    } else {
      const errHtml = json.errors.map(e => `<li>${e}</li>`).join('');
      alert.innerHTML = `<div class="alert alert-danger"><ul class="mb-0">${errHtml}</ul></div>`;
    }
  } catch (err) {
    console.log('Caught error:', err);
    alert.innerHTML = `<div class="alert alert-danger">Network error. Please try again.</div>`;
  } finally {
    btn.querySelector('.btn-text').classList.remove('d-none');
    btn.querySelector('.btn-spinner').classList.add('d-none');
    btn.disabled = false;
  }
});