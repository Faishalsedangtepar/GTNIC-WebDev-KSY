// Logika halaman Data Stok Barang
  function toggleMobileSidebar() {
    const sidebar = document.getElementById('sidebarDrawer');
    const backdrop = document.getElementById('sidebarBackdrop');
    
    if (sidebar.classList.contains('-translate-x-full')) {
      sidebar.classList.remove('-translate-x-full');
      backdrop.classList.remove('hidden');
    } else {
      sidebar.classList.add('-translate-x-full');
      backdrop.classList.add('hidden');
    }
  }

  function selectSidebarItem(e, targetPath) {
    if (e) e.preventDefault();
    const items = document.querySelectorAll('.sidebar-item');
    items.forEach(item => {
      const path = item.getAttribute('data-path');
      const textSpan = item.querySelector('span:not(.material-symbols-outlined)');
      if (path === targetPath) {
        item.className = 'sidebar-item flex items-center gap-space-md px-3 py-2.5 rounded-lg bg-secondary-container text-white font-bold shadow-sm transition-all';
        item.setAttribute('aria-current', 'page');
        if (textSpan) {
          textSpan.className = 'font-title-md text-title-md';
        }
      } else {
        item.className = 'sidebar-item flex items-center gap-space-md px-3 py-2.5 rounded-lg text-on-primary-container hover:bg-white/10 hover:text-white transition-all font-body-md text-body-md';
        item.removeAttribute('aria-current');
        if (textSpan) {
          textSpan.className = '';
        }
      }
    });

    if (window.innerWidth < 1024) {
      const sidebar = document.getElementById('sidebarDrawer');
      if (sidebar && !sidebar.classList.contains('-translate-x-full')) {
        toggleMobileSidebar();
      }
    }
  }

  function openModal(type, data = null) {
    if (type === 'add') {
      document.getElementById('modalAdd').classList.remove('hidden');
    } else if (type === 'edit') {
      if (data) {
        document.getElementById('editKode').value = data.kode || '';
        document.getElementById('editNama').value = data.nama || '';
        document.getElementById('editKategori').value = data.kategori || '';
        document.getElementById('editBrand').value = data.brand || '';
        document.getElementById('editStok').value = data.stok || '';
        document.getElementById('editSatuan').value = data.satuan || 'Zak';
        document.getElementById('editBeli').value = data.beli || '';
        document.getElementById('editJual').value = data.jual || '';
      }
      document.getElementById('modalEdit').classList.remove('hidden');
    } else if (type === 'adjust') {
      if (data) {
        document.getElementById('adjustTargetText').textContent = (data.kode || '') + ' - ' + (data.nama || '');
        document.getElementById('adjustCurrentStock').textContent = (data.stok || '0') + ' ' + (data.satuan || '');
      }
      document.getElementById('modalAdjust').classList.remove('hidden');
    } else if (type === 'delete') {
      if (data) {
        document.getElementById('deleteTargetText').textContent = 'SKU: ' + data.kode + ' (' + data.nama + ')';
      }
      document.getElementById('modalDelete').classList.remove('hidden');
    }
  }

  function closeModal(type) {
    if (type === 'add') document.getElementById('modalAdd').classList.add('hidden');
    if (type === 'edit') document.getElementById('modalEdit').classList.add('hidden');
    if (type === 'adjust') document.getElementById('modalAdjust').classList.add('hidden');
    if (type === 'delete') document.getElementById('modalDelete').classList.add('hidden');
  }

  function confirmDeleteAction() {
    closeModal('delete');
    alert('Data barang telah berhasil dihapus dari sistem gudang.');
  }

  // Close modals on Escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeModal('add');
      closeModal('edit');
      closeModal('adjust');
      closeModal('delete');
      const sidebar = document.getElementById('sidebarDrawer');
      if (!sidebar.classList.contains('-translate-x-full') && window.innerWidth < 1024) {
        toggleMobileSidebar();
      }
    }
  });

  let currentPage = 1;
  const totalPages = 84;
  const pageSize = 10;
  const totalItems = 840;

  function goToPage(page) {
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    
    const start = ((currentPage - 1) * pageSize) + 1;
    const end = Math.min(currentPage * pageSize, totalItems);
    
    const infoRange = document.getElementById('pageInfoRange');
    const infoBadge = document.getElementById('pageCurrentBadge');
    if (infoRange) infoRange.textContent = start + ' - ' + end;
    if (infoBadge) infoBadge.textContent = currentPage;
    
    const btnPrev = document.getElementById('btnPrevPage');
    const btnNext = document.getElementById('btnNextPage');
    if (btnPrev) btnPrev.disabled = (currentPage === 1);
    if (btnNext) btnNext.disabled = (currentPage === totalPages);
    
    const pageButtons = document.querySelectorAll('.page-btn');
    pageButtons.forEach(btn => {
      const p = parseInt(btn.getAttribute('data-page'));
      if (p === currentPage) {
        btn.className = 'page-btn w-8 h-8 rounded-lg bg-primary text-white font-bold text-xs flex items-center justify-center shadow-sm transition-all';
      } else {
        btn.className = 'page-btn w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-700 font-medium text-xs flex items-center justify-center transition-colors';
      }
    });
  }

// ===== Filter kategori & pencarian (di desain asli dipanggil tapi belum ditulis) =====
let activeCategory = 'Semua';
const CHIP_ACTIVE = 'category-tab px-3.5 py-1.5 rounded-lg bg-primary text-white font-semibold text-xs shrink-0 shadow-sm transition-transform active:scale-95 cursor-pointer';
const CHIP_IDLE = 'category-tab px-3.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-xs shrink-0 transition-colors cursor-pointer';

function filterByCategory(category) {
  activeCategory = category;
  document.querySelectorAll('.category-tab').forEach(function (btn) {
    btn.className = btn.dataset.category === category ? CHIP_ACTIVE : CHIP_IDLE;
  });
  applyInventoryFilter();
}

function applyInventoryFilter() {
  const q = (document.getElementById('tableSearch').value || '').trim().toLowerCase();
  let shown = 0;
  document.querySelectorAll('.inventory-row').forEach(function (row) {
    const ok = (activeCategory === 'Semua' || row.dataset.category === activeCategory) && (!q || row.dataset.text.includes(q));
    row.style.display = ok ? '' : 'none';
    if (ok) shown++;
  });
  document.querySelectorAll('.inventory-card-mobile').forEach(function (card) {
    const ok = (activeCategory === 'Semua' || card.dataset.category === activeCategory) && (!q || card.dataset.text.includes(q));
    card.style.display = ok ? '' : 'none';
  });
  document.getElementById('noResultsRow').classList.toggle('hidden', shown > 0);
  document.getElementById('noResultsMobile').classList.toggle('hidden', shown > 0);
  document.getElementById('pageInfoRange').textContent = shown ? '1 - ' + shown : '0 - 0';
  document.getElementById('pageTotalCount').textContent = (activeCategory === 'Semua' && !q) ? '840' : String(shown);
}


// Fungsi dipanggil dari atribut onclick/oninput di HTML, jadi harus global
Object.assign(window, { toggleMobileSidebar, selectSidebarItem, openModal, closeModal, confirmDeleteAction, goToPage, filterByCategory, applyInventoryFilter });