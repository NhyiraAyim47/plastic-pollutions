// Quick amount buttons
document.querySelectorAll('.quick-amount-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.quick-amount-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('donationAmount').value = btn.dataset.amount;
    updateImpact(parseFloat(btn.dataset.amount));
  });
});

// Custom amount impact
document.getElementById('donationAmount')?.addEventListener('input', function() {
  const val = parseFloat(this.value);
  document.querySelectorAll('.quick-amount-btn').forEach(b => {
    b.classList.toggle('active', parseFloat(b.dataset.amount) === val);
  });
  updateImpact(val);
});

function updateImpact(amount) {
  const preview = document.getElementById('impactPreview');
  if (!preview) return;
  let text = 'Select an amount to see your impact';
  let icon = '🌿';
  if (amount >= 500) { icon='🏫'; text='Your donation can bring PlasticFreeSchools to a new school!'; }
  else if (amount >= 200) { icon='📢'; text='You are sponsoring a Parliament policy advocacy campaign!'; }
  else if (amount >= 100) { icon='🌊'; text='You are funding ocean monitoring for an entire month!'; }
  else if (amount >= 50)  { icon='🧹'; text='You are equipping a full shoreline cleanup team!'; }
  else if (amount >= 10)  { icon='📚'; text='You are funding a school workshop for 30 students!'; }
  else if (amount > 0)    { icon='❤️'; text='Every cedi counts! Thank you for your generosity.'; }
  preview.querySelector('.impact-icon').textContent = icon;
  preview.querySelector('.impact-text').textContent = text;
}

// Submit
document.getElementById('donateForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  if (!validateDonationForm(this)) return;
  const btn = document.getElementById('donateBtn');
  const alertEl = document.getElementById('donateAlert');
  btn.querySelector('.btn-text').classList.add('d-none');
  btn.querySelector('.btn-spinner').classList.remove('d-none');
  btn.disabled = true;
  alertEl.innerHTML = '';
  try {
    const siteUrl = this.dataset.siteUrl;
    const res = await fetch(siteUrl + '/donations/donate.php',{method:'POST',body:new FormData(this)});
    const json = await res.json();
    if (json.success) {
      alertEl.innerHTML = `<div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>${json.message}</div>`;
      this.reset();
      document.querySelectorAll('.quick-amount-btn').forEach(b=>b.classList.remove('active'));
      updateImpact(0);
      showToast('Donation successful! Thank you! 🌿', 'success', 5000);
    } else {
      alertEl.innerHTML = `<div class="alert alert-danger">${json.message}</div>`;
    }
  } catch(err) {
    alertEl.innerHTML = `<div class="alert alert-danger">Network error. Please try again.</div>`;
  } finally {
    btn.querySelector('.btn-text').classList.remove('d-none');
    btn.querySelector('.btn-spinner').classList.add('d-none');
    btn.disabled = false;
  }
});
