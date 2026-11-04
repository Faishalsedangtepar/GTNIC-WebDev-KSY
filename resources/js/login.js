// Logika halaman Login
function togglePinVisibility() {
  const pinInput = document.getElementById('pin-input');
  const eyeIcon = document.getElementById('eye-icon');
  const eyeText = document.getElementById('eye-text');
  const hidden = pinInput.type === 'password';
  pinInput.type = hidden ? 'text' : 'password';
  eyeIcon.textContent = hidden ? 'visibility_off' : 'visibility';
  eyeText.textContent = hidden ? 'Tutup' : 'Lihat';
}

function quickPinAction() {
  const pinInput = document.getElementById('pin-input');
  pinInput.classList.add('bg-amber-100');
  setTimeout(() => pinInput.classList.remove('bg-amber-100'), 350);
}

// Dipanggil dari atribut onclick di Blade, jadi harus global
Object.assign(window, { togglePinVisibility, quickPinAction });