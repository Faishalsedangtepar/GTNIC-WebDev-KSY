// Logika halaman Laporan Keuangan
function openSidebar() {
  var sidebar = document.getElementById('mobile-sidebar');
  var backdrop = document.getElementById('mobile-drawer-backdrop');
  if (sidebar) sidebar.classList.remove('-translate-x-full');
  if (backdrop) backdrop.classList.remove('hidden');
  document.body.style.overflow = 'hidden';
  document.documentElement.style.overflow = 'hidden';
}

function closeSidebar() {
  var sidebar = document.getElementById('mobile-sidebar');
  var backdrop = document.getElementById('mobile-drawer-backdrop');
  if (sidebar) sidebar.classList.add('-translate-x-full');
  if (backdrop) backdrop.classList.add('hidden');
  document.body.style.overflow = '';
  document.documentElement.style.overflow = '';
}

// Fungsi dipanggil dari atribut onclick/oninput di HTML, jadi harus global
Object.assign(window, { openSidebar, closeSidebar });