// Perilaku sidebar & navbar admin (dipakai semua halaman yang memakai layouts/admin)
function setSidebar(open) {
  const sidebar = document.getElementById('mainSidebar');
  const backdrop = document.getElementById('sidebarBackdrop');
  if (!sidebar) return;
  sidebar.classList.toggle('-translate-x-full', !open);
  if (backdrop) backdrop.classList.toggle('hidden', !open);
}

function openSidebar() { setSidebar(true); }
function closeSidebar() { setSidebar(false); }

// toggleSidebar(true) = buka, toggleSidebar(false) = tutup, tanpa argumen = ganti
function toggleSidebar(show) {
  const sidebar = document.getElementById('mainSidebar');
  if (!sidebar) return;
  setSidebar(typeof show === 'boolean' ? show : sidebar.classList.contains('-translate-x-full'));
}

function focusSearchInput() {
  const input = document.getElementById('headerSearchInput');
  if (input) { input.focus(); input.select(); }
}

document.addEventListener('keydown', function (e) {
  if (e.key === 'Escape') closeSidebar();
  if (e.key === 'F2') { e.preventDefault(); focusSearchInput(); }
});

window.addEventListener('resize', function () {
  if (window.innerWidth >= 1024) closeSidebar();
});

// Dipanggil dari atribut onclick di Blade, jadi harus global
Object.assign(window, { openSidebar, closeSidebar, toggleSidebar, focusSearchInput });