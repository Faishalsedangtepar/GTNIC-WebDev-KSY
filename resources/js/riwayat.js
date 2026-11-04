// Logika halaman Riwayat Nota (diambil apa adanya dari desain)
(function() {
    // Quick category filtering logic
    const filterButtons = document.querySelectorAll('.filter-pill');
    const transactionItems = document.querySelectorAll('.transaction-item');
    const searchInput = document.getElementById('nota-search-input');
    const clearSearchBtn = document.getElementById('clear-search-btn');

    let currentFilter = 'all';

    function applyFilterAndSearch() {
      const query = (searchInput.value || '').toLowerCase().trim();

      transactionItems.forEach(item => {
        const itemType = item.getAttribute('data-type') || '';
        const itemText = item.textContent.toLowerCase();

        const matchesFilter = currentFilter === 'all' || itemType.includes(currentFilter);
        const matchesQuery = query === '' || itemText.includes(query);

        if (matchesFilter && matchesQuery) {
          item.style.display = 'flex';
        } else {
          item.style.display = 'none';
        }
      });
    }

    filterButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        filterButtons.forEach(b => {
          b.className = 'filter-pill bg-surface-container text-on-surface hover:bg-surface-container-high px-space-md py-1 rounded-full font-label-md text-label-md whitespace-nowrap transition-colors flex items-center gap-1.5 cursor-pointer';
        });

        btn.className = 'filter-pill bg-primary-container text-on-primary px-space-md py-1 rounded-full font-label-md text-label-md whitespace-nowrap shadow-sm flex items-center gap-1.5 cursor-pointer';
        currentFilter = btn.getAttribute('data-filter') || 'all';
        applyFilterAndSearch();
      });
    });

    if (searchInput) {
      searchInput.addEventListener('input', applyFilterAndSearch);
    }

    if (clearSearchBtn) {
      clearSearchBtn.addEventListener('click', () => {
        if (searchInput) {
          searchInput.value = '';
          applyFilterAndSearch();
          searchInput.focus();
        }
      });
    }

    // Modal Details Logic & Data Mapping
    const notaDataMap = {
      'NOTA-018': {
        id: 'NOTA-018',
        datetime: '27 Mei 2024, 14:35 WIB',
        kasir: 'Kasir Hendra',
        status: 'Lunas - Tunai',
        statusClass: 'bg-primary-container text-on-primary',
        customer: 'Pak Joko (Mandor Proyek Ruko Permata)',
        customerType: 'Langganan Kontraktor',
        phone: '+62 812-9988-3421 (WhatsApp)',
        address: 'Jl. Permata Regency Blok B-12, Grogol (Kirim Truk Toko)',
        items: [
          { name: 'Semen Tiga Roda 50kg', detail: '10 Sak × Rp 65.000', total: 'Rp 650.000' },
          { name: 'Besi Beton Polos 10mm SNI', detail: '15 Batang × Rp 72.000', total: 'Rp 1.080.000' }
        ],
        subtotal: 'Rp 1.730.000',
        shipping: 'Rp 0 (Gratis)',
        discount: 'Rp 0',
        grandtotal: 'Rp 1.730.000',
        payLabel: 'Bayar Tunai',
        paidAmount: 'Rp 2.000.000',
        changeLabel: 'Kembalian',
        changeAmount: 'Rp 270.000'
      },
      'NOTA-017': {
        id: 'NOTA-017',
        datetime: '27 Mei 2024, 13:10 WIB',
        kasir: 'Kasir Hendra',
        status: 'Lunas - QRIS',
        statusClass: 'bg-surface-container text-on-surface',
        customer: 'Bu Hani (Pemilik Rumah Kebon Jeruk)',
        customerType: 'Pelanggan Umum',
        phone: '+62 856-1122-8790 (WhatsApp)',
        address: 'Ambil Sendiri di Toko',
        items: [
          { name: 'Cat Tembok Catylac Putih 5kg', detail: '2 Galon × Rp 142.000', total: 'Rp 284.000' },
          { name: 'Pipa PVC Wavin AW 3/4"', detail: '3 Batang × Rp 33.333', total: 'Rp 100.000' }
        ],
        subtotal: 'Rp 384.000',
        shipping: 'Rp 0',
        discount: 'Rp 0',
        grandtotal: 'Rp 384.000',
        payLabel: 'Bayar Non-Tunai (QRIS)',
        paidAmount: 'Rp 384.000',
        changeLabel: 'Kembalian',
        changeAmount: 'Rp 0'
      },
      'NOTA-016': {
        id: 'NOTA-016',
        datetime: '27 Mei 2024, 11:45 WIB',
        kasir: 'Kasir Hendra',
        status: 'Tempo (Jatuh Tempo: 10 Jun)',
        statusClass: 'bg-secondary-fixed text-secondary',
        customer: 'Mandor Asep (CV Karya Mandiri)',
        customerType: 'Akun Tempo Proyek',
        phone: '+62 813-4455-9012 (WhatsApp)',
        address: 'Cluster Anggrek Residence Blok D-05',
        items: [
          { name: 'Semen Gresik 50kg', detail: '30 Sak × Rp 64.000', total: 'Rp 1.920.000' },
          { name: 'Triplek Meranti 12mm', detail: '40 Lembar × Rp 65.000', total: 'Rp 2.600.000' },
          { name: 'Ongkos Kirim Truk Colt Diesel', detail: '1 Ritase Proyek', total: 'Rp 150.000' }
        ],
        subtotal: 'Rp 4.670.000',
        shipping: 'Sudah Termasuk (Rp 150.000)',
        discount: 'Rp 0',
        grandtotal: 'Rp 4.670.000',
        payLabel: 'Uang Muka (DP)',
        paidAmount: 'Rp 0 (Belum Dibayar)',
        changeLabel: 'Sisa Tagihan Tempo',
        changeAmount: 'Rp 4.670.000'
      },
      'NOTA-015': {
        id: 'NOTA-015',
        datetime: '27 Mei 2024, 10:20 WIB',
        kasir: 'Kasir Hendra',
        status: 'Lunas - Transfer BCA',
        statusClass: 'bg-surface-container text-on-surface',
        customer: 'Pak Slamet (Bengkel Las Kurnia)',
        customerType: 'Langganan Bengkel',
        phone: '+62 818-0909-5432 (WhatsApp)',
        address: 'Jl. Raya Industri Km 4.5',
        items: [
          { name: 'Paku Kayu Campur 5cm', detail: '50 Kg × Rp 22.000', total: 'Rp 1.100.000' },
          { name: 'Seng Gelombang BJLS 0.30', detail: '20 Lembar × Rp 58.000', total: 'Rp 1.160.000' }
        ],
        subtotal: 'Rp 2.260.000',
        shipping: 'Rp 0 (Diambil Pickup Sendiri)',
        discount: 'Rp 0',
        grandtotal: 'Rp 2.260.000',
        payLabel: 'Transfer Bank BCA',
        paidAmount: 'Rp 2.260.000',
        changeLabel: 'Kembalian',
        changeAmount: 'Rp 0'
      }
    };

    // Scroll Lock Manager to prevent background stuttering
    let activeModalsCount = 0;
    function lockScroll() {
      activeModalsCount++;
      document.body.style.overflow = 'hidden';
      document.documentElement.style.overflow = 'hidden';
    }
    function unlockScroll() {
      activeModalsCount = Math.max(0, activeModalsCount - 1);
      if (activeModalsCount === 0) {
        document.body.style.overflow = '';
        document.documentElement.style.overflow = '';
      }
    }

    const modal = document.getElementById('modal-detail-nota');
    const modalBackdrop = document.getElementById('modal-backdrop');
    const modalSheet = document.getElementById('modal-sheet-panel');
    const btnCloseModal = document.getElementById('btn-close-modal');
    const btnCloseModalX = document.getElementById('btn-close-modal-x');

    function openModalWithNota(notaId) {
      const data = notaDataMap[notaId] || notaDataMap['NOTA-018'];

      // Populate Modal Fields
      document.getElementById('modal-nota-id').textContent = data.id;
      const statusBadge = document.getElementById('modal-status-badge');
      if (statusBadge) {
        statusBadge.textContent = data.status;
        statusBadge.className = 'text-[11px] font-bold px-2.5 py-0.5 rounded-full ' + data.statusClass;
      }
      document.getElementById('modal-nota-datetime').innerHTML = '<span class="material-symbols-outlined text-[14px]">schedule</span>' + data.datetime;
      document.getElementById('modal-nota-kasir').textContent = data.kasir;
      document.getElementById('modal-customer-name').textContent = data.customer;
      document.getElementById('modal-customer-type').textContent = data.customerType;
      document.getElementById('modal-customer-phone').textContent = data.phone;
      document.getElementById('modal-customer-address').textContent = data.address;
      document.getElementById('modal-item-count').textContent = data.items.length + ' Macam Barang';

      // Render Itemized Rows
      const container = document.getElementById('modal-items-container');
      container.innerHTML = '';
      data.items.forEach(it => {
        const itemRow = document.createElement('div');
        itemRow.className = 'py-2.5 flex items-start justify-between gap-space-sm';
        itemRow.innerHTML = `
          <div class="flex flex-col">
            <span class="font-title-md text-[14px] font-bold text-on-surface">${it.name}</span>
            <span class="text-[12px] text-on-surface-variant">${it.detail}</span>
          </div>
          <span class="font-tabular-numeric text-tabular-numeric font-bold text-on-surface whitespace-nowrap">${it.total}</span>
        `;
        container.appendChild(itemRow);
      });

      document.getElementById('modal-subtotal').textContent = data.subtotal;
      document.getElementById('modal-shipping').textContent = data.shipping;
      document.getElementById('modal-discount').textContent = data.discount;
      document.getElementById('modal-grandtotal').textContent = data.grandtotal;
      document.getElementById('modal-pay-label').textContent = data.payLabel;
      document.getElementById('modal-paid-amount').textContent = data.paidAmount;
      const changeRow = document.getElementById('modal-change-row');
      if (changeRow && changeRow.children[0]) {
        changeRow.children[0].textContent = data.changeLabel;
      }
      document.getElementById('modal-change-amount').textContent = data.changeAmount;

      // Show Modal with Animation
      modal.classList.remove('opacity-0', 'pointer-events-none');
      modalSheet.classList.remove('translate-y-8');
      lockScroll();
    }

    function closeModal() {
      modal.classList.add('opacity-0', 'pointer-events-none');
      modalSheet.classList.add('translate-y-8');
      unlockScroll();
    }

    // Attach card click handlers (both card body and detail button)
    transactionItems.forEach(card => {
      card.addEventListener('click', (e) => {
        // If clicking print button specifically, trigger print
        if (e.target.closest('.btn-print-card')) {
          e.stopPropagation();
          const notaId = card.getAttribute('data-nota') || 'NOTA-018';
          alert('Mencetak nota kasir ' + notaId + '...');
          return;
        }
        const notaId = card.getAttribute('data-nota') || 'NOTA-018';
        openModalWithNota(notaId);
      });
    });

    if (modalBackdrop) modalBackdrop.addEventListener('click', closeModal);
    if (btnCloseModal) btnCloseModal.addEventListener('click', closeModal);
    if (btnCloseModalX) btnCloseModalX.addEventListener('click', closeModal);

    const reprintModalBtn = document.getElementById('btn-reprint-modal');
    if (reprintModalBtn) {
      reprintModalBtn.addEventListener('click', () => {
        const curNota = document.getElementById('modal-nota-id').textContent;
        const orgHtml = reprintModalBtn.innerHTML;
        reprintModalBtn.innerHTML = '<span class="material-symbols-outlined text-[18px] animate-spin">refresh</span> Mencetak...';
        setTimeout(() => {
          reprintModalBtn.innerHTML = orgHtml;
          alert('Berhasil mencetak ulang ' + curNota + ' ke Printer POS Kasir.');
        }, 700);
      });
    }

    const waBtn = document.getElementById('btn-whatsapp-share');
    if (waBtn) {
      waBtn.addEventListener('click', () => {
        const curNota = document.getElementById('modal-nota-id').textContent;
        alert('Tautan nota digital ' + curNota + ' siap dibagikan ke WhatsApp Pelanggan!');
      });
    }

    // Rekap Shift Modal handlers
    const modalRekap = document.getElementById('modal-rekap-shift');
    const modalRekapSheet = document.getElementById('modal-rekap-sheet-panel');
    const modalRekapBackdrop = document.getElementById('modal-rekap-backdrop');
    const btnCloseRekapX = document.getElementById('btn-close-rekap-x');
    const btnCloseRekapModal = document.getElementById('btn-close-rekap-modal');
    const btnRekapShift = document.getElementById('btn-rekap-shift');

    function openRekapModal() {
      if (modalRekap && modalRekapSheet) {
        modalRekap.classList.remove('opacity-0', 'pointer-events-none');
        modalRekapSheet.classList.remove('translate-y-8');
        lockScroll();
      }
    }

    function closeRekapModal() {
      if (modalRekap && modalRekapSheet) {
        modalRekap.classList.add('opacity-0', 'pointer-events-none');
        modalRekapSheet.classList.add('translate-y-8');
        unlockScroll();
      }
    }

    if (btnRekapShift) btnRekapShift.addEventListener('click', openRekapModal);
    if (modalRekapBackdrop) modalRekapBackdrop.addEventListener('click', closeRekapModal);
    if (btnCloseRekapX) btnCloseRekapX.addEventListener('click', closeRekapModal);
    if (btnCloseRekapModal) btnCloseRekapModal.addEventListener('click', closeRekapModal);

    const btnPrintZ = document.getElementById('btn-print-zreport-modal');
    if (btnPrintZ) {
      btnPrintZ.addEventListener('click', () => {
        const orgHtml = btnPrintZ.innerHTML;
        btnPrintZ.innerHTML = '<span class="material-symbols-outlined text-[18px] animate-spin">refresh</span> Mencetak...';
        setTimeout(() => {
          btnPrintZ.innerHTML = orgHtml;
          alert('Z-Report Kasir Shift Pagi (27 Mei 2024) berhasil dicetak ke Printer POS-01.');
        }, 800);
      });
    }

    const exportBtn = document.getElementById('btn-export-excel');
    if (exportBtn) {
      exportBtn.addEventListener('click', () => {
        alert('Mengunduh Laporan Riwayat Nota Penjualan 27 Mei 2024 (.xlsx)...');
      });
    }

    // Keyboard ESC to close any open modal cleanly
    window.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        closeModal();
        closeRekapModal();
      }
    });
  })();

document.addEventListener('DOMContentLoaded',function(){var modalSettle=document.getElementById('modal-confirm-settle');var sheetSettle=document.getElementById('modal-settle-sheet-panel');var backdropSettle=document.getElementById('modal-settle-backdrop');var btnCloseX=document.getElementById('btn-close-settle-x');var btnCancel=document.getElementById('btn-cancel-settle');var btnConfirm=document.getElementById('btn-confirm-settle-action');var toast=document.getElementById('toast-settle');var toastText=document.getElementById('toast-settle-text');var selectedNotaId=null;var selectedBtn=null;function openSettleModal(notaId,nominal,triggerBtn){selectedNotaId=notaId;selectedBtn=triggerBtn;var targetNotaSpan=document.getElementById('settle-target-nota');var targetNominalSpan=document.getElementById('settle-target-nominal');if(targetNotaSpan)targetNotaSpan.textContent=notaId;if(targetNominalSpan)targetNominalSpan.textContent=nominal;if(modalSettle&&sheetSettle){modalSettle.classList.remove('opacity-0','pointer-events-none');sheetSettle.classList.remove('translate-y-8');}}function closeSettleModal(){if(modalSettle&&sheetSettle){modalSettle.classList.add('opacity-0','pointer-events-none');sheetSettle.classList.add('translate-y-8');}selectedNotaId=null;selectedBtn=null;}function showToast(msg){if(toast&&toastText){toastText.textContent=msg;toast.classList.remove('opacity-0','pointer-events-none','translate-y-4');setTimeout(function(){toast.classList.add('opacity-0','pointer-events-none','translate-y-4');},2600);}}document.body.addEventListener('click',function(e){var settleBtn=e.target.closest('.btn-settle-nota');if(settleBtn){e.stopPropagation();var notaId=settleBtn.getAttribute('data-nota')||'NOTA-016';var nominal=settleBtn.getAttribute('data-nominal')||'Rp 4.670.000';openSettleModal(notaId,nominal,settleBtn);}});if(backdropSettle)backdropSettle.addEventListener('click',closeSettleModal);if(btnCloseX)btnCloseX.addEventListener('click',closeSettleModal);if(btnCancel)btnCancel.addEventListener('click',closeSettleModal);if(btnConfirm){btnConfirm.addEventListener('click',function(){if(!selectedNotaId)return;var card=document.querySelector('article[data-nota="'+selectedNotaId+'"]');if(card){card.setAttribute('data-type','lunas tunai');var badge=card.querySelector('span.bg-secondary-fixed');if(badge){badge.className='text-[11px] font-bold text-on-surface px-2 py-0.5 rounded bg-surface-container';badge.textContent='Lunas';}var priceEl=card.querySelector('.font-title-md.text-title-md.font-bold.text-secondary');if(priceEl){priceEl.className='font-title-md text-title-md font-bold text-on-surface';priceEl.innerHTML='Rp 4.670.000 <span class="text-[11px] font-semibold text-emerald-600 ml-1">(Lunas)</span>';}var printBtn=card.querySelector('.btn-print-card.bg-secondary');if(printBtn){printBtn.className='btn-print-card h-8 px-space-sm rounded-lg bg-primary text-on-primary hover:bg-primary-container text-label-md font-semibold flex items-center gap-1 shadow-sm transition-colors active:scale-95';}if(selectedBtn)selectedBtn.remove();var activeFilterBtn=document.querySelector('.filter-pill.bg-primary-container');var activeFilter=activeFilterBtn?activeFilterBtn.getAttribute('data-filter'):'all';if(activeFilter==='tempo'){card.style.display='none';}var lunasPill=document.querySelector('button[data-filter="lunas"] span.font-semibold');if(lunasPill){var currLunas=parseInt(lunasPill.textContent)||25;lunasPill.textContent=(currLunas+1).toString();}var dpPill=document.querySelector('button[data-filter="tempo"] span.text-secondary');if(dpPill){var currDp=parseInt(dpPill.textContent)||3;var newDp=Math.max(0,currDp-1);dpPill.textContent=newDp.toString();}}var notaDone=selectedNotaId;closeSettleModal();showToast('Status '+notaDone+' berhasil diubah menjadi LUNAS');});}});