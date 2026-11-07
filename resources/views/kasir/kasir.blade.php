<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>Aura Material - Kasir</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

  @vite(['resources/css/app.css', 'resources/js/kasir.js'])

  <style>
  @layer base {
    html, body { width: 100%; margin: 0; padding: 0; min-height: 100%; }
    body { overscroll-behavior-y: contain; -webkit-overflow-scrolling: touch; }
    .pb-safe { padding-bottom: env(safe-area-inset-bottom, 0px); }
    .pt-safe { padding-top: env(safe-area-inset-top, 0px); }
  }
  ::-webkit-scrollbar { display: none; }
</style>
</head>
<body class="bg-surface font-body-md text-on-surface flex flex-col min-h-screen antialiased selection:bg-amber-100 selection:text-amber-900">

<!-- ================= HEADER ================= -->
@include('partials.kasir-navbar')



<!-- ================= MAIN ================= -->
<main class="flex-1 flex flex-col relative w-full bg-surface pt-16">

  <!-- Area katalog: di layar lebar diberi ruang kanan untuk sidebar nota -->
  <div class="flex flex-col w-full pb-36 md:pb-6 md:pr-80 lg:pr-96">

    <!-- Search + Riwayat + Kategori -->
    <div class="px-4 pt-3 pb-2 flex flex-col gap-2.5 bg-surface sticky top-16 z-30 bg-surface/95 backdrop-blur-md">
      <div class="flex items-center gap-2">
        <div class="relative flex-1 flex items-center bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant/50">
          <span class="material-symbols-outlined text-[20px] text-on-surface-variant pl-3 pr-2 select-none">search</span>
          <input class="w-full h-11 bg-transparent pr-4 text-sm text-on-surface placeholder:text-outline focus:outline-none font-medium" id="product-search" placeholder="Cari nama material, kode SKU, besi..." type="search"/>
        </div>
        <a aria-label="Riwayat Nota Transaksi Harian" href="{{ route('kasir.riwayat') }}"
           class="min-h-[44px] px-3.5 h-11 flex items-center justify-center gap-1.5 rounded-xl bg-surface-container-lowest border border-outline-variant/50 shadow-sm text-on-surface hover:text-secondary-container active:scale-95 transition-all">
          <span class="material-symbols-outlined text-[20px] text-amber-600">receipt_long</span>
          <span class="text-xs font-bold whitespace-nowrap">Riwayat</span>
        </a>
      </div>

      <div class="flex items-center gap-2 overflow-x-auto -mx-4 px-4 py-1 touch-pan-x">
        <button class="cat-chip whitespace-nowrap min-h-[38px] px-4 rounded-full text-xs font-bold bg-primary-container text-white shadow-sm transition-all flex items-center gap-1.5" data-category="all" type="button">
          <span class="material-symbols-outlined text-[16px]">domain</span>Semua
        </button>
        <button class="cat-chip whitespace-nowrap min-h-[38px] px-3.5 rounded-full text-xs font-semibold bg-surface-container-lowest text-on-surface-variant hover:text-on-surface shadow-sm border border-outline-variant/40 transition-all flex items-center gap-1.5" data-category="semen" type="button">
          <span class="material-symbols-outlined text-[16px]">foundation</span>Semen &amp; Pasir
        </button>
        <button class="cat-chip whitespace-nowrap min-h-[38px] px-3.5 rounded-full text-xs font-semibold bg-surface-container-lowest text-on-surface-variant hover:text-on-surface shadow-sm border border-outline-variant/40 transition-all flex items-center gap-1.5" data-category="besi" type="button">
          <span class="material-symbols-outlined text-[16px]">reorder</span>Besi &amp; Baja
        </button>
        <button class="cat-chip whitespace-nowrap min-h-[38px] px-3.5 rounded-full text-xs font-semibold bg-surface-container-lowest text-on-surface-variant hover:text-on-surface shadow-sm border border-outline-variant/40 transition-all flex items-center gap-1.5" data-category="cat" type="button">
          <span class="material-symbols-outlined text-[16px]">format_paint</span>Cat &amp; Thinner
        </button>
        <button class="cat-chip whitespace-nowrap min-h-[38px] px-3.5 rounded-full text-xs font-semibold bg-surface-container-lowest text-on-surface-variant hover:text-on-surface shadow-sm border border-outline-variant/40 transition-all flex items-center gap-1.5" data-category="pipa" type="button">
          <span class="material-symbols-outlined text-[16px]">plumbing</span>Pipa &amp; Fitting
        </button>
        <button class="cat-chip whitespace-nowrap min-h-[38px] px-3.5 rounded-full text-xs font-semibold bg-surface-container-lowest text-on-surface-variant hover:text-on-surface shadow-sm border border-outline-variant/40 transition-all flex items-center gap-1.5" data-category="alat" type="button">
          <span class="material-symbols-outlined text-[16px]">handyman</span>Alat Tukang
        </button>
      </div>
    </div>

    <!-- Grid katalog (dirender dari array `products` di script) -->
    <div class="px-4 grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-2 sm:gap-2.5 md:gap-3 mt-1 pb-36 md:pb-6 w-full" id="products-catalog-container"></div>
    <div class="hidden px-4 py-12 text-center text-xs text-on-surface-variant" id="catalog-empty">
      <span class="material-symbols-outlined text-[32px] text-outline block mb-1">search_off</span>
      Tidak ada material yang cocok. Coba kata kunci atau kategori lain.
    </div>

    <!-- Toast -->
    <div class="fixed top-20 left-1/2 -translate-x-1/2 z-50 bg-inverse-surface text-inverse-on-surface px-4 py-2.5 rounded-full text-xs font-semibold flex items-center gap-2 shadow-xl transition-all duration-300 border border-slate-700 max-w-[90vw] opacity-0 pointer-events-none translate-y-2" id="quick-toast">
      <span class="material-symbols-outlined text-[18px] text-amber-400">check_circle</span>
      <span class="truncate" id="toast-message">Item berhasil ditambahkan ke nota</span>
    </div>
  </div>

  <!-- ===================================================== -->
  <!-- PANEL NOTA                                            -->
  <!-- Mobile: bottom bar + drawer                           -->
  <!-- md+  : sidebar tetap di kanan, selalu terbuka         -->
  <!-- ===================================================== -->
  <aside id="cart-panel"
         class="fixed left-0 w-full z-40 px-4 pointer-events-none bottom-0 pb-safe
                md:left-auto md:right-0 md:top-16 md:bottom-0 md:w-80 lg:w-96 md:px-0 md:pb-0
                md:bg-surface-container-lowest md:border-l md:border-outline-variant/40 md:pointer-events-auto">
    <div class="w-full max-w-lg mx-auto pointer-events-auto md:max-w-none md:h-full md:flex md:flex-col">

      <!-- Drawer / daftar item -->
      <div id="cart-drawer"
           class="bg-surface-container-lowest rounded-t-2xl shadow-2xl border border-surface-container-high overflow-hidden mb-2 transition-all duration-300 hidden
                  md:!flex md:flex-col md:flex-1 md:min-h-0 md:rounded-none md:shadow-none md:border-0 md:mb-0">
        <div class="px-4 py-3 bg-surface-container-low flex items-center justify-between border-b border-surface-container cursor-pointer select-none md:cursor-default md:pointer-events-none md:py-4" onclick="toggleCartDrawer()">
          <div class="flex items-center gap-2">
            <span class="font-bold text-sm text-on-surface">Daftar Muatan Nota</span>
            <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-900 text-[11px] font-bold border border-amber-300" id="cart-drawer-count">0 Item Material</span>
          </div>
          <button aria-label="Tutup Rincian" class="md:hidden min-h-[36px] min-w-[36px] h-9 w-9 flex items-center justify-center rounded-full text-on-surface-variant active:bg-surface-container" onclick="event.stopPropagation(); toggleCartDrawer();" type="button">
            <span class="material-symbols-outlined text-[20px]" id="cart-drawer-chevron">expand_more</span>
          </button>
        </div>
        <div class="p-4 flex flex-col gap-3 max-h-60 overflow-y-auto overscroll-contain md:max-h-none md:flex-1 md:min-h-0" id="cart-items-container"></div>
      </div>

      <!-- Mobile: bar total + bayar -->
      <div class="md:hidden bg-surface-container-lowest rounded-2xl shadow-xl p-2.5 flex items-center justify-between gap-2 border border-outline-variant/60 mb-2">
        <button aria-label="Lihat Rincian Keranjang" class="flex items-center gap-3 text-left pl-2 flex-1 min-w-0" onclick="toggleCartDrawer()" type="button">
          <div class="relative min-w-[42px] h-11 w-11 rounded-xl bg-primary-container flex items-center justify-center text-amber-400 shadow-sm">
            <span class="material-symbols-outlined text-[22px]">shopping_cart_checkout</span>
            <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 bg-amber-500 text-slate-950 text-[10px] rounded-full flex items-center justify-center font-black" id="cart-badge-count">0</span>
          </div>
          <div class="flex flex-col min-w-0">
            <div class="flex items-center gap-1">
              <span class="text-xs text-on-surface-variant font-semibold" id="cart-badge-label">0 Item Terpilih</span>
              <span class="material-symbols-outlined text-[14px] text-on-surface-variant" id="cart-toggle-indicator">unfold_more</span>
            </div>
            <span class="text-base font-extrabold text-on-surface truncate tracking-tight" id="cart-total-display">Rp 0</span>
          </div>
        </button>
        <a aria-label="Proses Pembayaran Nota Toko Bangunan" href="{{ route('kasir.pembayaran') }}" onclick="return processPaymentModal()"
           class="min-h-[46px] h-11 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold flex items-center justify-center gap-2 active:scale-95 shadow-md transition-transform">
          <span class="material-symbols-outlined text-[20px]">payments</span>
          <span class="whitespace-nowrap">Bayar / Nota</span>
        </a>
      </div>

      <!-- Desktop: footer sidebar (subtotal, diskon, total, bayar) -->
      <div class="hidden md:flex md:flex-col gap-2 p-4 border-t border-outline-variant/40 bg-surface-container-lowest">
        <div class="flex items-center justify-between text-xs text-on-surface-variant">
          <span>Subtotal (<span id="d-count">0</span> item)</span>
          <span class="font-semibold text-on-surface" id="d-subtotal">Rp 0</span>
        </div>
        <div class="hidden items-center justify-between text-xs text-emerald-700" id="d-discount-row">
          <span class="flex items-center gap-1 font-bold">
            <span class="material-symbols-outlined text-[15px]">percent</span>Potongan Grosir
          </span>
          <span class="font-bold" id="d-discount">-Rp 0</span>
        </div>
        <div class="flex items-center justify-between pt-2 border-t border-surface-container">
          <span class="text-sm font-bold text-on-surface">Total</span>
          <span class="text-xl font-extrabold text-on-surface tracking-tight" id="d-total">Rp 0</span>
        </div>
        <a aria-label="Proses Pembayaran Nota Toko Bangunan" href="{{ route('kasir.pembayaran') }}" onclick="return processPaymentModal()"
           class="mt-1 min-h-[48px] rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold flex items-center justify-center gap-2 active:scale-[0.98] shadow-md transition-transform">
          <span class="material-symbols-outlined text-[20px]">payments</span>
          <span>Bayar / Nota</span>
        </a>
      </div>

    </div>
  </aside>
</main>

<!-- ================================================================ -->
<!-- MODAL KUANTITAS MATERIAL                                         -->
<!-- Mobile: bottom sheet slide-up | md+: pop-up di tengah (zoom-fade) -->
<!-- ================================================================ -->
<div aria-modal="true" class="fixed inset-0 z-50 flex flex-col justify-end md:justify-center md:items-center transition-all duration-300 opacity-0 pointer-events-none" id="quantity-modal" role="dialog">
  <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity duration-300" id="quantity-backdrop" onclick="closeQuantityModal()"></div>

  <div class="relative z-10 w-full max-w-lg mx-auto bg-surface-container-lowest rounded-t-3xl shadow-2xl border-t border-surface-container flex flex-col max-h-[90vh] overflow-hidden transform transition-all duration-300 ease-out pb-safe translate-y-full
              md:max-w-2xl lg:max-w-3xl md:rounded-3xl md:border md:border-surface-container md:max-h-[85vh] md:translate-y-0 md:scale-95 md:opacity-0" id="quantity-sheet">

    <!-- Grabber (hanya mobile) -->
    <div class="md:hidden w-full flex justify-center pt-3 pb-1 cursor-grab active:cursor-grabbing" onclick="closeQuantityModal()">
      <div class="w-12 h-1.5 rounded-full bg-outline-variant/60"></div>
    </div>

    <!-- Header -->
    <div class="px-5 pt-1 pb-3 md:px-6 md:pt-5 md:pb-4 flex items-start justify-between gap-3 border-b border-surface-container">
      <div class="flex items-center gap-3 min-w-0">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-slate-100 text-slate-700 shadow-sm shrink-0" id="modal-icon-container">
          <span class="material-symbols-outlined text-[26px]" id="modal-icon">inventory_2</span>
        </div>
        <div class="flex flex-col min-w-0">
          <div class="flex items-center gap-2">
            <span class="text-[10px] font-bold text-amber-700 uppercase tracking-wider" id="modal-category">Kategori</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-emerald-100 text-emerald-800 font-bold border border-emerald-300" id="modal-stock-badge">Stok</span>
          </div>
          <h2 class="font-extrabold text-base text-on-surface truncate leading-tight mt-0.5" id="modal-product-title">Produk</h2>
          <span class="text-xs text-on-surface-variant font-medium" id="modal-unit-price">Rp 0</span>
        </div>
      </div>
      <button aria-label="Tutup Modal" class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-on-surface-variant hover:text-on-surface active:bg-surface-container-high transition-colors shrink-0" onclick="closeQuantityModal()" type="button">
        <span class="material-symbols-outlined text-[18px]">close</span>
      </button>
    </div>

    <!-- Body: 1 kolom di mobile, 2 kolom di md+ -->
    <div class="p-5 md:p-6 flex flex-col gap-4 md:grid md:grid-cols-2 md:gap-6 md:items-start overflow-y-auto overscroll-contain max-h-[calc(90vh-180px)] md:max-h-[calc(85vh-180px)]">

      <!-- Kolom kiri: input jumlah -->
      <div class="bg-surface rounded-2xl p-4 md:p-6 md:row-span-2 border border-outline-variant/40 flex flex-col items-center gap-3">
        <div class="flex items-center justify-between w-full">
          <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Jumlah Pesanan Material</span>
          <span class="text-xs font-semibold px-2 py-0.5 rounded bg-surface-container text-on-surface font-mono" id="modal-unit-label">Satuan: -</span>
        </div>
        <div class="flex items-center justify-center gap-3 w-full my-1">
          <button aria-label="Kurangi Kuantitas" class="min-w-[50px] min-h-[50px] rounded-2xl bg-surface-container-lowest border border-outline-variant/50 shadow-sm text-on-surface flex items-center justify-center hover:bg-surface-container active:scale-90 transition-all" onclick="changeQuantityStep(-1)" type="button">
            <span class="material-symbols-outlined text-[24px]">remove</span>
          </button>
          <div class="flex-1 max-w-[170px] md:max-w-[200px] relative">
            <input class="w-full h-14 md:h-16 bg-surface-container-lowest border-2 border-primary-container rounded-2xl text-center text-3xl md:text-4xl font-extrabold text-on-surface tracking-tight focus:outline-none focus:ring-2 focus:ring-amber-500 shadow-inner" id="modal-qty-input" max="999" min="1" oninput="onQuantityInput(this.value)" type="number" value="1"/>
          </div>
          <button aria-label="Tambah Kuantitas" class="min-w-[50px] min-h-[50px] rounded-2xl bg-primary-container text-white shadow-sm flex items-center justify-center hover:bg-slate-800 active:scale-90 transition-all" onclick="changeQuantityStep(1)" type="button">
            <span class="material-symbols-outlined text-[24px]">add</span>
          </button>
        </div>
        <div class="w-full flex items-center justify-between gap-1.5 pt-1">
          <span class="text-[11px] font-semibold text-on-surface-variant mr-1">Cepat:</span>
          <button class="flex-1 py-1.5 px-2 rounded-xl bg-surface-container-lowest border border-outline-variant/40 text-xs font-bold text-on-surface hover:border-amber-500 active:bg-amber-50 active:scale-95 transition-all text-center shadow-sm" onclick="addQuantityPreset(1)" type="button">+1</button>
          <button class="flex-1 py-1.5 px-2 rounded-xl bg-surface-container-lowest border border-outline-variant/40 text-xs font-bold text-on-surface hover:border-amber-500 active:bg-amber-50 active:scale-95 transition-all text-center shadow-sm" onclick="addQuantityPreset(5)" type="button">+5</button>
          <button class="flex-1 py-1.5 px-2 rounded-xl bg-surface-container-lowest border border-outline-variant/40 text-xs font-bold text-on-surface hover:border-amber-500 active:bg-amber-50 active:scale-95 transition-all text-center shadow-sm" onclick="addQuantityPreset(10)" type="button">+10</button>
          <button class="flex-1 py-1.5 px-2 rounded-xl bg-surface-container-lowest border border-outline-variant/40 text-xs font-bold text-on-surface hover:border-amber-500 active:bg-amber-50 active:scale-95 transition-all text-center shadow-sm" onclick="addQuantityPreset(25)" type="button">+25</button>
        </div>
      </div>

      <!-- Kolom kanan atas: kalkulasi -->
      <div class="bg-surface-container-low rounded-2xl p-3.5 border border-surface-container-high flex flex-col gap-2">
        <div class="flex items-center justify-between text-xs text-on-surface-variant">
          <span>Kalkulasi Kuantitas (<span id="summary-qty-formula">1</span> × <span id="summary-unit-price">Rp 0</span>)</span>
          <span class="font-semibold text-on-surface" id="summary-normal-price">Rp 0</span>
        </div>
        <div class="items-center justify-between text-xs text-emerald-700 bg-emerald-50 px-2 py-1 rounded-lg border border-emerald-200 hidden" id="summary-discount-row">
          <div class="flex items-center gap-1 font-bold">
            <span class="material-symbols-outlined text-[15px]">percent</span>
            <span>Potongan Grosir (≥20 item, 3%)</span>
          </div>
          <span class="font-bold" id="summary-discount-amount">-Rp 0</span>
        </div>
        <div class="flex items-center justify-between pt-2 border-t border-surface-container font-bold">
          <span class="text-xs text-on-surface">Subtotal Material</span>
          <span class="text-base text-amber-700 tracking-tight" id="modal-subtotal-price">Rp 0</span>
        </div>
      </div>

      <!-- Kolom kanan bawah: catatan -->
      <div class="flex flex-col gap-1.5">
        <label class="2text-xs font-bold text-on-surface flex items-center gap-1" for="item-notes">
          <span class="material-symbols-outlined text-[15px] text-on-surface-variant">edit_note</span>
          Catatan Item / Proyek (Opsional)
        </label>
        <input class="w-full h-10 px-3 rounded-xl bg-surface-container-lowest border border-outline-variant/60 text-xs text-on-surface placeholder:text-outline focus:outline-none focus:border-amber-600 font-medium" id="item-notes" placeholder="Contoh: Titip truk siang, proyek Pak Joko Buaran..." type="text"/>
      </div>
    </div>

    <!-- Footer -->
    <div class="p-4 md:px-6 md:py-5 bg-surface-container-lowest border-t border-surface-container flex items-center gap-2.5">
      <button class="min-h-[46px] px-4 rounded-xl bg-surface-container hover:bg-surface-container-high text-on-surface text-xs font-bold transition-all active:scale-95" onclick="closeQuantityModal()" type="button">Batal</button>
      <button class="flex-1 min-h-[46px] px-4 rounded-xl bg-primary-container hover:bg-slate-800 text-white text-sm font-bold flex items-center justify-center gap-2 shadow-md active:scale-95 transition-all" id="modal-confirm-btn" onclick="submitQuantityModal()" type="button">
        <span class="material-symbols-outlined text-[19px] text-amber-400">add_shopping_cart</span>
        <span id="modal-confirm-label">Tambahkan ke Nota</span>
      </button>
    </div>
  </div>
</div>


</body>
</html>