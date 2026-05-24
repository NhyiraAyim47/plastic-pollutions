// Delete confirm
document.getElementById('deleteConfirmInput')?.addEventListener('input', function() {
  const btn = document.getElementById('confirmDeleteBtn');
  btn.classList.toggle('disabled', this.value !== 'DELETE');
});
