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

  @vite(['resources/css/kasir.css', 'resources/js/kasir.js'])
</head>
<body>

  <!-- HEADER -->
  <header class="topbar">
    <div class="brand">
      <div class="brand-logo"><span class="material-symbols-outlined">construction</span></div>
      <span class="brand-name">Aura Material</span>
    </div>
    <div class="topbar-right">
      <button class="user-pill" type="button">
        <span class="dot"></span><span>Kasir Hendra</span>
      </button>
      <button class="avatar" type="button" aria-label="Profil">
        <img alt="Hendra Pratama" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAnbnWs_y2AKUny2UOq8hCe8jbScIhphtQhkKe2EsEsW59q1UlGyOOlSQ1LUcxEzlmx62NANblElcUl4SGkxyt_ojVJZ8-frBGnFIyq20qkL9Ib_ZlCIVsFYg17ShTrpnYbwqYDgG8SpG1bYgh-LFvrNXXwB3u6Ha-RHyG0jkWxGltdDmhFnKuKVgXvSL2eop0I2ZVp3xI4DF4wBT8aVwu_mSc0ErYmQh0ymzuN9hF32rrgtOCxA5cKAw">
      </button>
    </div>
  </header>

  <main class="layout">

    <!-- KATALOG -->
    <section class="catalog">
      <div class="toolbar">
        <div class="search-row">
          <div class="search">
            <span class="material-symbols-outlined">search</span>
            <input id="product-search" type="search" placeholder="Cari nama material, kode SKU, besi...">
          </div>
          <a class="btn-history" href="#">
            <span class="material-symbols-outlined">receipt_long</span><span>Riwayat</span>
          </a>
        </div>
        <div class="chips">
          <button class="chip active" data-category="all" type="button"><span class="material-symbols-outlined">domain</span>Semua</button>
          <button class="chip" data-category="semen" type="button"><span class="material-symbols-outlined">foundation</span>Semen &amp; Pasir</button>
          <button class="chip" data-category="besi" type="button"><span class="material-symbols-outlined">reorder</span>Besi &amp; Baja</button>
          <button class="chip" data-category="cat" type="button"><span class="material-symbols-outlined">format_paint</span>Cat &amp; Thinner</button>
          <button class="chip" data-category="pipa" type="button"><span class="material-symbols-outlined">plumbing</span>Pipa &amp; Fitting</button>
          <button class="chip" data-category="alat" type="button"><span class="material-symbols-outlined">handyman</span>Alat Tukang</button>
        </div>
      </div>

      <div class="grid" id="products-catalog-container"></div>
      <div class="empty hidden" id="catalog-empty">
        <span class="material-symbols-outlined">search_off</span>
        Tidak ada material yang cocok. Coba kata kunci atau kategori lain.
      </div>
    </section>

    <!-- PANEL NOTA -->
    <aside class="cart">
      <div class="cart-inner">

        <div class="cart-drawer collapsed" id="cart-drawer">
          <div class="cart-head" onclick="toggleCartDrawer()">
            <div class="cart-title">
              <strong>Daftar Muatan Nota</strong>
              <span class="count-pill" id="cart-drawer-count">0 Item Material</span>
            </div>
            <button class="close-mobile" type="button" onclick="event.stopPropagation(); toggleCartDrawer();" aria-label="Tutup">
              <span class="material-symbols-outlined">expand_more</span>
            </button>
          </div>
          <div class="cart-items" id="cart-items-container"></div>
        </div>

        <!-- Mobile: bar total -->
        <div class="mobile-bar">
          <button class="mobile-summary" type="button" onclick="toggleCartDrawer()">
            <div class="cart-icon">
              <span class="material-symbols-outlined">shopping_cart_checkout</span>
              <span class="badge" id="cart-badge-count">0</span>
            </div>
            <div class="mobile-text">
              <span class="small" id="cart-badge-label">0 Item Terpilih</span>
              <span class="total-sm" id="cart-total-display">Rp 0</span>
            </div>
          </button>
          <a class="btn-pay" href="#" onclick="return processPayment()">
            <span class="material-symbols-outlined">payments</span><span>Bayar / Nota</span>
          </a>
        </div>

        <!-- Desktop: footer sidebar -->
        <div class="desk-foot">
          <div class="row muted">
            <span>Subtotal (<span id="d-count">0</span> item)</span>
            <span class="strong" id="d-subtotal">Rp 0</span>
          </div>
          <div class="row green hidden" id="d-discount-row">
            <span>Potongan Grosir</span>
            <span class="strong" id="d-discount">-Rp 0</span>
          </div>
          <div class="row total-row">
            <span class="total-label">Total</span>
            <span class="total-lg" id="d-total">Rp 0</span>
          </div>
          <a class="btn-pay big" href="#" onclick="return processPayment()">
            <span class="material-symbols-outlined">payments</span><span>Bayar / Nota</span>
          </a>
        </div>

      </div>
    </aside>
  </main>

  <!-- TOAST -->
  <div class="toast" id="quick-toast">
    <span class="material-symbols-outlined">check_circle</span>
    <span id="toast-message"></span>
  </div>

  <!-- MODAL KUANTITAS -->
  <div class="modal" id="quantity-modal" role="dialog" aria-modal="true">
    <div class="backdrop" onclick="closeQuantityModal()"></div>
    <div class="sheet" id="quantity-sheet">

      <div class="grabber" onclick="closeQuantityModal()"><span></span></div>

      <div class="sheet-head">
        <div class="sheet-prod">
          <div class="prod-icon" id="modal-icon-container">
            <span class="material-symbols-outlined" id="modal-icon">inventory_2</span>
          </div>
          <div class="prod-text">
            <div class="prod-meta">
              <span class="cat-label" id="modal-category">Kategori</span>
              <span class="stock-pill" id="modal-stock-badge">Stok</span>
            </div>
            <h2 id="modal-product-title">Produk</h2>
            <span class="muted-sm" id="modal-unit-price">Rp 0</span>
          </div>
        </div>
        <button class="close-btn" type="button" onclick="closeQuantityModal()" aria-label="Tutup">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>

      <div class="sheet-body">
        <div class="qty-box">
          <div class="qty-top">
            <span class="qty-label">Jumlah Pesanan Material</span>
            <span class="unit-tag" id="modal-unit-label">Satuan: -</span>
          </div>
          <div class="qty-control">
            <button class="step" type="button" onclick="changeQuantityStep(-1)" aria-label="Kurangi"><span class="material-symbols-outlined">remove</span></button>
            <input id="modal-qty-input" type="number" min="1" max="999" value="1" oninput="onQuantityInput(this.value)">
            <button class="step dark" type="button" onclick="changeQuantityStep(1)" aria-label="Tambah"><span class="material-symbols-outlined">add</span></button>
          </div>
          <div class="presets">
            <span>Cepat:</span>
            <button type="button" onclick="addQuantityPreset(1)">+1</button>
            <button type="button" onclick="addQuantityPreset(5)">+5</button>
            <button type="button" onclick="addQuantityPreset(10)">+10</button>
            <button type="button" onclick="addQuantityPreset(25)">+25</button>
          </div>
        </div>

        <div class="side-col">
          <div class="calc">
            <div class="row muted">
              <span>Kalkulasi (<span id="summary-qty-formula">1</span> × <span id="summary-unit-price">Rp 0</span>)</span>
              <span class="strong" id="summary-normal-price">Rp 0</span>
            </div>
            <div class="row discount hidden" id="summary-discount-row">
              <span>Potongan Grosir (≥20 item, 3%)</span>
              <span class="strong" id="summary-discount-amount">-Rp 0</span>
            </div>
            <div class="row calc-total">
              <span>Subtotal Material</span>
              <span id="modal-subtotal-price">Rp 0</span>
            </div>
          </div>
          <div class="note-field">
            <label for="item-notes"><span class="material-symbols-outlined">edit_note</span>Catatan Item / Proyek (Opsional)</label>
            <input id="item-notes" type="text" placeholder="Contoh: Titip truk siang, proyek Pak Joko Buaran...">
          </div>
        </div>
      </div>

      <div class="sheet-foot">
        <button class="btn-cancel" type="button" onclick="closeQuantityModal()">Batal</button>
        <button class="btn-confirm" type="button" onclick="submitQuantityModal()">
          <span class="material-symbols-outlined">add_shopping_cart</span>
          <span id="modal-confirm-label">Tambahkan ke Nota</span>
        </button>
      </div>
    </div>
  </div>

</body>
</html>