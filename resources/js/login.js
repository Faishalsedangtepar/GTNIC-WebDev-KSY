
window.togglePinVisibility = function () {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-icon');
    const eyeText = document.getElementById('eye-text');

    if (!passwordInput) {
        console.error('Input password tidak ditemukan!');
        return;
    }

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.textContent = 'visibility_off';
        eyeText.textContent = 'Tutup';
    } else {
        passwordInput.type = 'password';
        eyeIcon.textContent = 'visibility';
        eyeText.textContent = 'Lihat';
    }
};
