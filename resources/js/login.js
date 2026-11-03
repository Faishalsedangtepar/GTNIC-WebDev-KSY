const pinInput = document.getElementById('pin-input');
const eyeIcon = document.getElementById('eye-icon');
const eyeText = document.getElementById('eye-text');

// Tampilkan / sembunyikan PIN
document.getElementById('toggle-pin').addEventListener('click', function () {
  const hidden = pinInput.type === 'password';
  pinInput.type = hidden ? 'text' : 'password';
  eyeIcon.textContent = hidden ? 'visibility_off' : 'visibility';
  eyeText.textContent = hidden ? 'Tutup' : 'Lihat';
});

// Tombol PIN Cepat & Biometrik (efek kilat sementara)
document.getElementById('quick-pin').addEventListener('click', function () {
  pinInput.classList.add('flash');
  setTimeout(function () {
    pinInput.classList.remove('flash');
  }, 350);
});
