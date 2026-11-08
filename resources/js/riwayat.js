// Halaman Riwayat Nota kasir, tersambung ke NotaController:
//   GET  /kasir/nota?dari=&sampai=&status=&q=&page=   daftar nota
//   GET  /kasir/nota/ringkasan?dari=&sampai=&q=       Total Omzet + badge tab
//   GET  /kasir/nota/{id}                             Rincian
//   GET  /kasir/nota/{id}/cetak                       Cetak (tab baru)
//   POST /kasir/nota/{id}/lunasi                      Lunaskan
//   GET  /kasir/z-report?dari=&sampai=                Rekap Z-Report
//   GET  /kasir/z-report/cetak?dari=&sampai=          Cetak Z-Report
//   GET  /kasir/nota/export?dari=&sampai=&status=&q=  Export .xlsx
(function () {
  const BASE     = document.body.dataset.base;      // mis. http://host/kasir
  const KASIR    = document.body.dataset.kasir;     // username kasir login
  const HARI_INI = document.body.dataset.tanggal;   // Y-m-d zona Asia/Jakarta (dari server)
  const CSRF     = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
  const $        = (id) => document.getElementById(id);

  const state = { tanggal: HARI_INI, filter: 'all', q: '', page: 1, notaAktif: null, settle: null, reqList: 0 };

  // ------------------------------------------------------------ util
  const rp  = (n) => 'Rp ' + Math.round(Number(n) || 0).toLocaleString('id-ID');
  const esc = (v) => String(v == null ? '' : v).replace(/[&<>"']/g, (c) => (
    { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]
  ));
  const qty = (n) => String(Number(n)).replace('.', ',');

  // "2026-11-07 14:35:12.123456+07" (format PostgreSQL) -> Date
  function parseTanggal(s) {
    const m = String(s).match(/^(\d{4}-\d{2}-\d{2})[ T](\d{2}:\d{2}:\d{2})(?:\.\d+)?(Z|[+-]\d{2}(?::?\d{2})?)?$/);
    if (!m) return new Date(s);
    let tz = m[3] || '';
    if (/^[+-]\d{2}$/.test(tz)) tz += ':00';
    else if (/^[+-]\d{4}$/.test(tz)) tz = tz.slice(0, 3) + ':' + tz.slice(3);
    return new Date(m[1] + 'T' + m[2] + tz);
  }
  const jam = (s) => parseTanggal(s)
    .toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', hour12: false, timeZone: 'Asia/Jakarta' })
    .replace('.', ':');
  // 6/11/2026
  const tglPendek = (s) => parseTanggal(s)
    .toLocaleDateString('id-ID', { day: 'numeric', month: 'numeric', year: 'numeric', timeZone: 'Asia/Jakarta' });
  const waktuLengkap = (s) => parseTanggal(s)
    .toLocaleString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: false, timeZone: 'Asia/Jakarta' })
    .replace('.', ':').replace(' pukul ', ', ') + ' WIB';

  function labelTanggal(ymd) {
    const [y, m, d] = ymd.split('-').map(Number);
    return new Date(y, m - 1, d).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
  }

  function qs(obj) {
    const p = new URLSearchParams();
    Object.entries(obj).forEach(([k, v]) => { if (v !== '' && v != null) p.set(k, v); });
    const s = p.toString();
    return s ? '?' + s : '';
  }

  async function api(path, opts) {
    const res = await fetch(BASE + path, Object.assign({
      credentials: 'same-origin',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': CSRF,
      },
    }, opts));

    let json = null;
    try { json = await res.json(); } catch (e) { /* bukan JSON */ }

    if (!res.ok) {
      let pesan = (json && json.message) || 'Terjadi kesalahan (' + res.status + ').';
      if (json && json.errors) pesan = Object.values(json.errors).flat()[0] || pesan;
      if (res.status === 419 || res.status === 401) pesan = 'Sesi habis. Muat ulang halaman lalu login kembali.';
      throw new Error(pesan);
    }
    return json;
  }

  // ------------------------------------------------------------ scroll lock & modal
  let modalAktif = 0;
  function lockScroll() {
    modalAktif++;
    document.body.style.overflow = 'hidden';
    document.documentElement.style.overflow = 'hidden';
  }
  function unlockScroll() {
    modalAktif = Math.max(0, modalAktif - 1);
    if (modalAktif === 0) {
      document.body.style.overflow = '';
      document.documentElement.style.overflow = '';
    }
  }
  function bukaModal(modal, sheet) {
    if (!modal || modal.dataset.open === '1') return;
    modal.dataset.open = '1';
    modal.classList.remove('opacity-0', 'pointer-events-none');
    sheet.classList.remove('translate-y-8');
    lockScroll();
  }
  function tutupModal(modal, sheet) {
    if (!modal || modal.dataset.open !== '1') return;
    modal.dataset.open = '0';
    modal.classList.add('opacity-0', 'pointer-events-none');
    sheet.classList.add('translate-y-8');
    unlockScroll();
  }

  // ------------------------------------------------------------ toast
  let toastTimer = null;
  function toast(pesan) {
    const el = $('toast-settle');
    if (!el) return;
    $('toast-settle-text').textContent = pesan;
    el.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-4');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => el.classList.add('opacity-0', 'pointer-events-none', 'translate-y-4'), 3000);
  }

  // ------------------------------------------------------------ parameter umum
  const statusApi = () => (state.filter === 'all' ? '' : state.filter);   // all | lunas | tempo (tempo = DP)
  const rentangApi = () => ({ dari: state.tanggal, sampai: state.tanggal });

  // ------------------------------------------------------------ ringkasan (omzet + badge)
  async function muatRingkasan() {
    try {
      const r = await api('/nota/ringkasan' + qs(Object.assign(rentangApi(), { q: state.q })));
      $('ringkasan-omzet').textContent = rp(r.total_omzet);
      $('ringkasan-lunas').textContent = r.lunas;
      $('ringkasan-dp').textContent    = r.dp;
      $('count-all').textContent       = r.semua;
      $('count-lunas').textContent     = r.lunas;
      $('count-dp').textContent        = r.dp;
    } catch (e) {
      toast(e.message);
    }
  }

  // ------------------------------------------------------------ daftar nota
  function kartu(n) {
    const lunas = n.status_bayar === 'lunas';
    const nama  = n.nama_pembeli ? esc(n.nama_pembeli) : 'Pembeli umum';
    const badge = n.status_bayar === 'belum_dibayar' ? 'Belum Bayar' : (lunas ? 'Lunas' : 'DP');

    const badgeCls = lunas
      ? 'text-[11px] font-bold text-on-surface px-2 py-0.5 rounded bg-surface-container'
      : 'text-[11px] font-bold px-2 py-0.5 rounded bg-secondary-fixed text-secondary';
    const hargaCls   = lunas ? 'text-on-surface' : 'text-secondary';
    const cardBorder = lunas ? 'border-outline-variant/30' : 'border-secondary/30';
    const ikon = lunas
      ? '<span class="material-symbols-outlined text-[16px] text-on-surface-variant">person</span>'
      : '<span class="material-symbols-outlined text-[16px] text-secondary">engineering</span>';
    const cetakCls = lunas
      ? 'bg-primary text-on-primary hover:bg-primary-container'
      : 'bg-secondary text-on-secondary hover:bg-secondary-container';

    const tombolLunas = n.sisa > 0 ? `
      <button type="button" data-id="${n.id}" data-nomor="${esc(n.nomor)}" data-sisa="${n.sisa}"
        class="btn-settle-nota h-7 px-2.5 rounded-lg bg-secondary text-on-secondary hover:bg-secondary-container text-label-md font-bold flex items-center gap-1 shadow-sm active:scale-95 transition-all">
        <span class="material-symbols-outlined text-[15px]">check_circle</span>
        Lunaskan
      </button>` : '';

    return `
      <article data-id="${n.id}"
        class="bg-surface-container-lowest rounded-xl p-3 shadow-sm border ${cardBorder} flex flex-col gap-2 transaction-item cursor-pointer hover:shadow-md transition-shadow active:scale-[0.99]">
        <div class="flex items-center justify-between flex-wrap gap-y-1 border-b border-outline-variant/20 pb-1.5">
          <div class="flex items-center gap-2 flex-wrap">
            <span class="font-tabular-numeric text-tabular-numeric font-extrabold text-on-surface">${esc(n.nomor)}</span>
            <span class="text-on-surface-variant text-[11px] bg-surface-container px-1.5 py-0.5 rounded font-medium">${esc(tglPendek(n.tanggal))}, ${esc(jam(n.tanggal))} WIB</span>
          </div>
          <div class="flex items-center gap-1.5 ml-auto">
            <span class="${badgeCls}">${badge}</span>
            <span class="font-tabular-numeric text-title-md font-extrabold ${hargaCls}">${rp(n.total)}</span>
          </div>
        </div>
        <div class="flex flex-col gap-0.5">
          <div class="flex items-center gap-1 text-[13px] font-semibold text-on-surface">${ikon}${nama}</div>
          <p class="text-body-md text-on-surface-variant text-[12px] truncate pl-5">${esc(n.item_ringkas)}</p>
        </div>
        <div class="flex items-center ${n.sisa > 0 ? 'justify-between' : 'justify-end'} pt-1 border-t border-outline-variant/20">
          ${tombolLunas}
          <div class="flex items-center gap-1.5 ml-auto">
            <button type="button" data-id="${n.id}"
              class="btn-detail-nota h-7 px-2.5 rounded-lg bg-surface-container text-on-surface hover:bg-surface-container-high text-label-md font-semibold flex items-center gap-1 transition-colors active:scale-95">
              <span class="material-symbols-outlined text-[15px]">visibility</span>
              Rincian
            </button>
            <button type="button" data-id="${n.id}"
              class="btn-print-card h-7 px-2.5 rounded-lg ${cetakCls} text-label-md font-semibold flex items-center gap-1 shadow-sm transition-colors active:scale-95">
              <span class="material-symbols-outlined text-[15px]">print</span>
              Cetak
            </button>
          </div>
        </div>
      </article>`;
  }

  async function muatDaftar(tambah) {
    const wadah = $('transaction-list');
    const id = ++state.reqList;
    if (!tambah) {
      state.page = 1;
      wadah.innerHTML = '<p class="text-center text-on-surface-variant text-[13px] py-8" id="list-status">Memuat nota...</p>';
    }

    try {
      const json = await api('/nota' + qs(Object.assign(rentangApi(), { status: statusApi(), q: state.q, page: state.page })));
      if (id !== state.reqList) return;   // ada permintaan yang lebih baru

      const lama = $('btn-more'); if (lama) lama.remove();
      const info = $('list-status'); if (info) info.remove();

      if (!tambah && json.data.length === 0) {
        wadah.innerHTML = '<p class="text-center text-on-surface-variant text-[13px] py-8" id="list-status">Tidak ada nota.</p>';
        return;
      }

      wadah.insertAdjacentHTML('beforeend', json.data.map(kartu).join(''));

      if (json.current_page < json.last_page) {
        wadah.insertAdjacentHTML('beforeend',
          '<button type="button" id="btn-more" class="h-10 rounded-xl bg-surface-container text-on-surface text-label-md font-semibold hover:bg-surface-container-high active:scale-95">Muat lebih banyak</button>');
      }
    } catch (e) {
      if (id !== state.reqList) return;
      wadah.innerHTML = '<p class="text-center text-error text-[13px] py-8" id="list-status">' + esc(e.message) + '</p>';
    }
  }

  function muatSemua() {
    muatRingkasan();
    muatDaftar(false);
  }

  // ------------------------------------------------------------ filter tab & pencarian
  const CLS_AKTIF = 'filter-pill bg-primary-container text-on-primary shadow-sm px-space-md py-1 rounded-full font-label-md text-label-md whitespace-nowrap flex items-center gap-1.5 cursor-pointer';
  const CLS_BIASA = 'filter-pill bg-surface-container text-on-surface hover:bg-surface-container-high px-space-md py-1 rounded-full font-label-md text-label-md whitespace-nowrap transition-colors flex items-center gap-1.5 cursor-pointer';

  const pills = document.querySelectorAll('.filter-pill');
  pills.forEach((btn) => {
    btn.addEventListener('click', () => {
      pills.forEach((b) => { b.className = CLS_BIASA; });
      btn.className = CLS_AKTIF;
      state.filter = btn.getAttribute('data-filter') || 'all';
      muatDaftar(false);
    });
  });

  const cari = $('nota-search-input');
  let cariTimer = null;
  if (cari) {
    cari.addEventListener('input', () => {
      clearTimeout(cariTimer);
      cariTimer = setTimeout(() => { state.q = cari.value.trim(); muatSemua(); }, 300);
    });
  }
  const hapusCari = $('clear-search-btn');
  if (hapusCari) {
    hapusCari.addEventListener('click', () => {
      if (cari) { cari.value = ''; cari.focus(); }
      state.q = '';
      muatSemua();
    });
  }

  // ------------------------------------------------------------ kalender
  const inputTanggal = $('input-tanggal');
  const btnKalender  = $('btn-kalender');
  if (btnKalender && inputTanggal) {
    btnKalender.addEventListener('click', () => {
      inputTanggal.value = state.tanggal;
      inputTanggal.max = HARI_INI;
      if (typeof inputTanggal.showPicker === 'function') inputTanggal.showPicker();
      else inputTanggal.focus();
    });
    inputTanggal.addEventListener('change', () => {
      state.tanggal = inputTanggal.value || HARI_INI;
      const hariIni = state.tanggal === HARI_INI;
      const judul = $('page-title');
      if (judul) judul.textContent = hariIni ? 'Riwayat Nota Penjualan Hari Ini' : 'Riwayat Nota ' + labelTanggal(state.tanggal);
      $('chip-hari').textContent  = hariIni ? 'Hari Ini' : labelTanggal(state.tanggal);
      $('ringkasan-tanggal').textContent = labelTanggal(state.tanggal);
      muatSemua();
    });
  }

  // ------------------------------------------------------------ modal rincian
  const mDetail = $('modal-detail-nota');
  const sDetail = $('modal-sheet-panel');
  const tutupDetail = () => tutupModal(mDetail, sDetail);

  const ARMADA = { truk: 'Kirim Truk Toko', pickup: 'Kirim Pickup Toko', ambil_sendiri: 'Ambil Sendiri di Toko' };

  async function bukaDetail(id) {
    let n;
    try { n = await api('/nota/' + id); } catch (e) { toast(e.message); return; }
    state.notaAktif = n;

    $('modal-nota-id').textContent = n.nomor;

    const badge = $('modal-status-badge');
    const lunas = n.status_bayar === 'lunas';
    badge.textContent = lunas ? 'Lunas' : (n.status_bayar === 'dp' ? 'DP (Sisa ' + rp(n.sisa) + ')' : 'Belum Dibayar');
    badge.className = 'text-[11px] font-bold px-2.5 py-0.5 rounded-full ' +
      (lunas ? 'bg-primary-container text-on-primary' : 'bg-secondary-fixed text-secondary');

    $('modal-nota-datetime').innerHTML =
      '<span class="material-symbols-outlined text-[14px]">schedule</span>' + esc(waktuLengkap(n.tanggal));
    $('modal-nota-kasir').textContent = 'Kasir ' + KASIR;

    $('modal-customer-name').textContent = n.nama_pembeli || 'Pembeli umum';
    $('modal-customer-type').textContent = ARMADA[n.armada] || n.armada;
    $('modal-customer-phone').textContent = n.no_hp || '-';
    $('modal-customer-address').textContent = n.alamat_pengiriman
      ? n.alamat_pengiriman + (n.catatan_lokasi ? ' (' + n.catatan_lokasi + ')' : '')
      : 'Diambil sendiri di toko';

    $('modal-item-count').textContent = n.items.length + ' Macam Barang';
    $('modal-items-container').innerHTML = n.items.map((it) => `
      <div class="py-2.5 flex items-start justify-between gap-space-sm">
        <div class="flex flex-col">
          <span class="font-title-md text-[14px] font-bold text-on-surface">${esc(it.nama)}</span>
          <span class="text-[12px] text-on-surface-variant">${qty(it.qty)} ${esc(it.satuan)} × ${rp(it.harga)}</span>
        </div>
        <span class="font-tabular-numeric text-tabular-numeric font-bold text-on-surface whitespace-nowrap">${rp(it.subtotal)}</span>
      </div>`).join('');

    $('modal-subtotal').textContent   = rp(n.total - n.ongkir - n.biaya_proteksi);
    $('modal-shipping').textContent   = rp(n.ongkir);
    $('modal-discount').textContent   = rp(n.biaya_proteksi);   // baris "Proteksi Terpal"
    $('modal-grandtotal').textContent = rp(n.total);
    $('modal-pay-label').textContent  = 'Sudah Dibayar';
    $('modal-paid-amount').textContent = rp(n.dibayar);
    const barisSisa = $('modal-change-row');
    if (barisSisa && barisSisa.children[0]) barisSisa.children[0].textContent = 'Sisa Tagihan';
    $('modal-change-amount').textContent = rp(n.sisa);

    bukaModal(mDetail, sDetail);
  }

  [$('modal-backdrop'), $('btn-close-modal'), $('btn-close-modal-x')].forEach((el) => {
    if (el) el.addEventListener('click', tutupDetail);
  });

  const cetakNota = (id) => window.open(BASE + '/nota/' + id + '/cetak', '_blank');

  const btnUlang = $('btn-reprint-modal');
  if (btnUlang) btnUlang.addEventListener('click', () => { if (state.notaAktif) cetakNota(state.notaAktif.id); });

  const btnWa = $('btn-whatsapp-share');
  if (btnWa) {
    btnWa.addEventListener('click', () => {
      const n = state.notaAktif;
      if (!n) return;
      if (!n.no_hp) { toast('Nomor HP pembeli tidak tersedia'); return; }

      let nomor = String(n.no_hp).replace(/\D/g, '');
      if (nomor.startsWith('0')) nomor = '62' + nomor.slice(1);

      const teks = ['Nota ' + n.nomor + ' - Aura Material',
                    'Total   : ' + rp(n.total),
                    'Dibayar : ' + rp(n.dibayar),
                    'Sisa    : ' + rp(n.sisa)].join('\n');
      window.open('https://wa.me/' + nomor + '?text=' + encodeURIComponent(teks), '_blank');
    });
  }

  // ------------------------------------------------------------ pelunasan
  const mSettle = $('modal-confirm-settle');
  const sSettle = $('modal-settle-sheet-panel');
  const tutupSettle = () => { tutupModal(mSettle, sSettle); state.settle = null; };

  function bukaSettle(id, nomor, sisa) {
    state.settle = { id, nomor };
    $('settle-target-nota').textContent = nomor;
    $('settle-target-nominal').textContent = rp(sisa);
    bukaModal(mSettle, sSettle);
  }

  [$('modal-settle-backdrop'), $('btn-close-settle-x'), $('btn-cancel-settle')].forEach((el) => {
    if (el) el.addEventListener('click', tutupSettle);
  });

  const btnKonfirmasi = $('btn-confirm-settle-action');
  if (btnKonfirmasi) {
    btnKonfirmasi.addEventListener('click', async () => {
      if (!state.settle || btnKonfirmasi.disabled) return;
      const { id, nomor } = state.settle;

      btnKonfirmasi.disabled = true;
      try {
        await api('/nota/' + id + '/lunasi', { method: 'POST', body: '{}' });   // tanpa jumlah = lunasi seluruh sisa
        tutupSettle();
        toast('Nota ' + nomor + ' berhasil dilunasi');
        muatSemua();
      } catch (e) {
        tutupSettle();
        toast(e.message);
      } finally {
        btnKonfirmasi.disabled = false;
      }
    });
  }

  // ------------------------------------------------------------ klik pada daftar (delegasi)
  $('transaction-list').addEventListener('click', (e) => {
    if (e.target.closest('#btn-more')) { state.page++; muatDaftar(true); return; }

    const settle = e.target.closest('.btn-settle-nota');
    if (settle) {
      e.stopPropagation();
      bukaSettle(settle.dataset.id, settle.dataset.nomor, settle.dataset.sisa);
      return;
    }

    const cetak = e.target.closest('.btn-print-card');
    if (cetak) { e.stopPropagation(); cetakNota(cetak.dataset.id); return; }

    const kartuEl = e.target.closest('.transaction-item');
    if (kartuEl) bukaDetail(kartuEl.dataset.id);
  });

  // ------------------------------------------------------------ Z-Report
  const mRekap = $('modal-rekap-shift');
  const sRekap = $('modal-rekap-sheet-panel');
  const tutupRekap = () => tutupModal(mRekap, sRekap);

  async function bukaRekap() {
    let z;
    try { z = await api('/z-report' + qs(rentangApi())); } catch (e) { toast(e.message); return; }

    $('z-periode').textContent = z.periode_label;
    $('z-kasir').textContent   = 'Kasir ' + z.kasir;
    $('z-total').textContent   = rp(z.total_omzet);
    $('z-trx').textContent     = z.total_trx + ' pembayaran';
    $('z-nota').textContent    = z.jumlah_nota + ' Nota';
    $('z-lunas-trx').textContent   = '(' + z.lunas.trx + ' trx)';
    $('z-lunas-total').textContent = rp(z.lunas.total);
    $('z-dp-trx').textContent      = '(' + z.dp.trx + ' trx)';
    $('z-dp-total').textContent    = rp(z.dp.total);
    $('z-omzet').textContent       = rp(z.total_omzet);

    bukaModal(mRekap, sRekap);
  }

  const btnRekap = $('btn-rekap-shift');
  if (btnRekap) btnRekap.addEventListener('click', bukaRekap);
  [$('modal-rekap-backdrop'), $('btn-close-rekap-x'), $('btn-close-rekap-modal')].forEach((el) => {
    if (el) el.addEventListener('click', tutupRekap);
  });

  const btnCetakZ = $('btn-print-zreport-modal');
  if (btnCetakZ) {
    btnCetakZ.addEventListener('click', () => {
      window.open(BASE + '/z-report/cetak' + qs(rentangApi()), '_blank');
    });
  }

  // ------------------------------------------------------------ Export (.xlsx)
  // Mengikuti apa yang sedang tampil: tanggal, tab (Semua/Lunas/DP), dan kata pencarian.
  const btnExport = $('btn-export-excel');
  if (btnExport) {
    btnExport.addEventListener('click', () => {
      window.location.href = BASE + '/nota/export' + qs(Object.assign(rentangApi(), { status: statusApi(), q: state.q }));
    });
  }

  // ------------------------------------------------------------ ESC & mulai
  window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') { tutupDetail(); tutupRekap(); tutupSettle(); }
  });

  muatSemua();
})();