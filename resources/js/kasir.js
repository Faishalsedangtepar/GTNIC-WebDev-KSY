// Logika halaman Kasir (diambil dari desain; tombol di HTML memanggil fungsi global)
/* =========================================================
     DATA PRODUK
     cat   : kunci filter chip (semen | besi | cat | pipa | alat | all)
     pu    : kata satuan pada label "Harga / ..."
     ic/tt/st : warna ikon / judul / sub-judul pada kartu
     ========================================================= */
  const products = [
    { id:'semen-tr', name:'Semen Tiga Roda 50kg', category:'Semen & Pasir', cat:'semen', unit:'zak', pu:'zak', price:65000, stock:120,
      icon:'inventory_2', iconBg:'bg-slate-100 text-slate-700', grad:'from-slate-200 via-stone-300 to-slate-400',
      ic:'text-slate-700', tt:'text-slate-800', st:'text-slate-600', title:'TIGA RODA 50KG', sub:'Portland Composite', badge:'Stok Aman' },
    { id:'besi-10mm', name:'Besi Beton Polos 10mm SNI', category:'Besi & Baja', cat:'besi', unit:'btg', pu:'batang', price:72000, stock:45,
      icon:'reorder', iconBg:'bg-zinc-700 text-white', grad:'from-zinc-300 via-slate-400 to-zinc-600',
      ic:'text-zinc-100', tt:'text-zinc-100', st:'text-zinc-200', title:'BESI BETON 10MM', sub:'SNI 12m', badge:'Tersedia' },
    { id:'dulux-white', name:'Cat Tembok Dulux Catylac 5kg Putih', category:'Cat & Thinner', cat:'cat', unit:'pail', pu:'pail', price:135000, stock:18,
      icon:'format_paint', iconBg:'bg-blue-100 text-blue-800', grad:'from-amber-50 via-sky-100 to-blue-300',
      ic:'text-blue-800', tt:'text-blue-900', st:'text-blue-700', title:'DULUX CATYLAC', sub:'5kg Brilliant White', badge:'Tersedia' },
    { id:'pipa-wavin', name:'Pipa PVC Wavin AW 3/4 inch 4m', category:'Pipa & Fitting', cat:'pipa', unit:'btg', pu:'batang', price:38000, stock:85,
      icon:'plumbing', iconBg:'bg-sky-100 text-sky-700', grad:'from-slate-100 via-slate-300 to-slate-400',
      ic:'text-sky-700', tt:'text-slate-800', st:'text-slate-600', title:'PIPA WAVIN AW', sub:'3/4 Inch 4m', badge:'Stok: 85' },
    { id:'seng-bjls', name:'Seng Gelombang BJLS 0.20', category:'Atap & Seng', cat:'besi', unit:'lbr', pu:'lembar', price:58000, stock:8,
      icon:'roofing', iconBg:'bg-zinc-200 text-zinc-800', grad:'from-stone-200 via-zinc-300 to-stone-400',
      ic:'text-zinc-700', tt:'text-zinc-800', st:'text-zinc-600', title:'SENG BJLS 0.20', sub:'Galvalum 1.8m', badge:'Tersedia' },
    { id:'pasir-bangka', name:'Pasir Bangka Super', category:'Semen & Pasir', cat:'semen', unit:'m3', pu:'m3', price:320000, stock:15,
      icon:'landslide', iconBg:'bg-amber-100 text-amber-800', grad:'from-amber-50 via-stone-200 to-amber-200',
      ic:'text-amber-800', tt:'text-amber-900', st:'text-amber-700', title:'PASIR BANGKA', sub:'Kualitas Halus Super', badge:'Tersedia' },
    { id:'hebel-75', name:'Bata Ringan Hebel 7.5cm', category:'Semen & Pasir', cat:'semen', unit:'kubik', pu:'kubik', price:650000, stock:30,
      icon:'crop_square', iconBg:'bg-slate-200 text-slate-800', grad:'from-slate-100 via-zinc-200 to-slate-300',
      ic:'text-slate-700', tt:'text-slate-800', st:'text-slate-600', title:'HEBEL 7.5CM', sub:'Bata Ringan Presisi', badge:'Tersedia' },
    { id:'catylac-alkali', name:'Catylac Dasar Alkali Sealer', category:'Cat & Thinner', cat:'cat', unit:'pail', pu:'pail', price:185000, stock:12,
      icon:'format_paint', iconBg:'bg-blue-100 text-blue-900', grad:'from-blue-50 via-sky-100 to-indigo-200',
      ic:'text-blue-900', tt:'text-blue-900', st:'text-blue-700', title:'ALKALI SEALER', sub:'Cat Dasar Tembok', badge:'Tersedia' },
    { id:'triplek-meranti-9mm', name:'Triplek Meranti 9mm', category:'Kayu & Triplek', cat:'alat', unit:'lbr', pu:'lbr', price:115000, stock:40,
      icon:'layers', iconBg:'bg-amber-100 text-amber-900', grad:'from-amber-100 via-orange-100 to-amber-300',
      ic:'text-amber-900', tt:'text-amber-900', st:'text-amber-800', title:'TRIPLEK 9MM', sub:'Meranti 122x244', badge:'Tersedia' },
    { id:'semen-gresik', name:'Semen Gresik 50kg', category:'Semen & Pasir', cat:'semen', unit:'zak', pu:'zak', price:64000, stock:80,
      icon:'inventory_2', iconBg:'bg-slate-100 text-slate-800', grad:'from-red-100 via-slate-200 to-stone-300',
      ic:'text-red-700', tt:'text-slate-800', st:'text-slate-600', title:'GRESIK 50KG', sub:'Semen PCC', badge:'Stok Aman' },
    { id:'paku-kayu', name:'Paku Kayu 5cm Campur', category:'Alat Tukang', cat:'alat', unit:'kg', pu:'kg', price:22000, stock:50,
      icon:'hardware', iconBg:'bg-zinc-200 text-zinc-700', grad:'from-zinc-200 via-stone-300 to-zinc-400',
      ic:'text-zinc-700', tt:'text-zinc-800', st:'text-zinc-600', title:'PAKU KAYU 5CM', sub:'Baja Galvanis', badge:'Tersedia' }
  ];

  const customProduct = {
    id:'ongkir-custom', name:'Ongkir Truk Engkel / Pickup', category:'Item Custom / Ongkir', cat:'all',
    unit:'rit', pu:'rit', price:70000, stock:999, icon:'local_shipping', iconBg:'bg-amber-100 text-amber-800'
  };

  // Nota awal (7 item, Rp 802.000)
  let cartItems = [
    { id:'semen-tr', name:'Semen Tiga Roda 50kg', category:'Semen & Pasir', unit:'zak', price:65000, qty:2, icon:'inventory_2', iconBg:'bg-slate-100 text-slate-700' },
    { id:'besi-10mm', name:'Besi Beton Polos 10mm SNI', category:'Besi & Baja', unit:'btg', price:72000, qty:2, icon:'reorder', iconBg:'bg-zinc-700 text-white' },
    { id:'dulux-white', name:'Cat Tembok Dulux Catylac 5kg', category:'Cat & Thinner', unit:'pail', price:135000, qty:1, icon:'format_paint', iconBg:'bg-blue-100 text-blue-800' },
    { id:'pipa-wavin', name:'Pipa PVC Wavin AW 3/4 inch', category:'Pipa & Fitting', unit:'btg', price:38000, qty:1, icon:'plumbing', iconBg:'bg-sky-100 text-sky-700' },
    { id:'pasir-bangka-rit', name:'Pasir Bangka Super + Rit Kirim', category:'Semen & Pasir', unit:'rit', price:355000, qty:1, icon:'landslide', iconBg:'bg-amber-100 text-amber-800' }
  ];

  let activeProduct = products[0];
  let currentQty = 1;

  /* ---------------- Helpers ---------------- */
  function formatRupiah(num) { return 'Rp ' + Number(num).toLocaleString('id-ID'); }

  // Potongan grosir 3% per baris bila qty >= 20
  function calcLine(price, qty) {
    const gross = price * qty;
    const discount = qty >= 20 ? Math.round(gross * 0.03) : 0;
    return { gross, discount, net: gross - discount };
  }

  function releasePageScrollLock() {
    document.body.style.overflow = '';
    document.documentElement.style.overflow = '';
    document.body.classList.remove('overflow-hidden');
    document.documentElement.classList.remove('overflow-hidden');
  }

  /* ---------------- Katalog ---------------- */
  function renderCatalog() {
    const container = document.getElementById('products-catalog-container');
    const cards = products.map(p => {
      const critical = p.stock <= 10;
      const badgeCls = critical
        ? 'bg-red-100 text-red-700 border-red-300'
        : 'bg-emerald-100 text-emerald-800 border-emerald-300';
      const dotCls = critical ? 'bg-red-600 animate-ping' : 'bg-emerald-600';
      const badgeText = critical ? 'Kritis' : p.badge;
      return `
      <div class="product-item bg-surface-container-lowest rounded-xl p-2 sm:p-2.5 flex flex-col justify-between shadow-sm border border-outline-variant/40 relative overflow-hidden transition-transform active:scale-[0.98] cursor-pointer"
           data-id="${p.id}" data-category="${p.cat}" data-search="${(p.name + ' ' + p.category + ' ' + p.sub).toLowerCase()}">
        <div class="relative w-full h-20 sm:h-24 md:h-28 rounded-lg overflow-hidden bg-slate-100 mb-1.5 flex items-center justify-center">
          <div class="w-full h-full bg-gradient-to-br ${p.grad} flex flex-col items-center justify-center p-1.5 text-center">
            <span class="material-symbols-outlined text-[26px] sm:text-[32px] ${p.ic}">${p.icon}</span>
            <span class="text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider ${p.tt} mt-0.5">${p.title}</span>
            <span class="text-[8px] ${p.st} hidden sm:block">${p.sub}</span>
          </div>
          <span class="absolute top-1.5 left-1.5 px-1.5 rounded-full text-[9px] ${badgeCls} font-bold flex items-center gap-1 shadow-sm border">
            <span class="w-1.5 h-1.5 rounded-full ${dotCls}"></span>${badgeText}
          </span>
        </div>
        <div class="flex flex-col flex-1">
          <span class="text-[9px] font-bold text-amber-700 uppercase tracking-wider">${p.category}</span>
          <h3 class="text-xs sm:text-sm font-semibold line-clamp-2 text-on-surface leading-snug mt-0.5">${p.name}</h3>
          <span class="text-[11px] text-on-surface-variant mt-0.5">Stok: <b class="${critical ? 'text-error font-bold' : 'text-on-surface'}">${p.stock} ${p.unit}</b></span>
        </div>
        <div class="flex items-center justify-between mt-2 pt-1 border-t border-surface-container">
          <div>
            <span class="text-[9px] text-on-surface-variant block leading-tight">Harga / ${p.pu}</span>
            <span class="font-bold text-xs sm:text-sm text-on-surface">${formatRupiah(p.price)}</span>
          </div>
          <button aria-label="Tambah ${p.name}" class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-primary-container text-white flex items-center justify-center active:bg-secondary-container shadow-sm" type="button">
            <span class="material-symbols-outlined text-[16px] sm:text-[18px]">add</span>
          </button>
        </div>
      </div>`;
    });

    cards.push(`
      <div class="product-item bg-surface-container-low rounded-xl p-2 sm:p-2.5 flex flex-col items-center justify-between text-center shadow-sm border border-dashed border-outline cursor-pointer active:scale-[0.98] transition-transform min-h-[170px]"
           data-id="${customProduct.id}" data-category="all" data-search="item custom ongkir truk engkel pickup kuli muat">
        <div class="w-full flex flex-col items-center pt-1">
          <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center mb-1.5 shadow-sm border border-amber-300">
            <span class="material-symbols-outlined text-[20px] sm:text-[22px]">local_shipping</span>
          </div>
          <h3 class="text-xs sm:text-sm font-semibold text-on-surface line-clamp-1">Item Custom / Ongkir</h3>
          <p class="text-[10px] text-on-surface-variant mt-0.5 line-clamp-2 leading-tight">Ongkir Truk Engkel, Pickup, atau Kuli Muat</p>
        </div>
        <span class="my-1 text-[10px] font-bold text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">+ Tambah Biaya</span>
      </div>`);

    container.innerHTML = cards.join('');
  }

  // Klik kartu / tombol + membuka modal kuantitas
  document.getElementById('products-catalog-container').addEventListener('click', (e) => {
    const card = e.target.closest('.product-item');
    if (!card) return;
    const id = card.dataset.id;
    const product = id === customProduct.id ? customProduct : products.find(p => p.id === id);
    if (product) openQuantityModal(product);
  });

  /* ---------------- Filter kategori + pencarian ---------------- */
  let activeCategory = 'all';

  function applyCatalogFilter() {
    const q = document.getElementById('product-search').value.trim().toLowerCase();
    let visible = 0;
    document.querySelectorAll('#products-catalog-container .product-item').forEach(card => {
      const cardCat = card.dataset.category;
      const catOk = activeCategory === 'all' || cardCat === 'all' || cardCat === activeCategory;
      const searchOk = !q || card.dataset.search.includes(q);
      const show = catOk && searchOk;
      card.classList.toggle('hidden', !show);
      if (show) visible++;
    });
    document.getElementById('catalog-empty').classList.toggle('hidden', visible > 0);
  }

  document.querySelectorAll('.cat-chip').forEach(button => {
    button.addEventListener('click', () => {
      document.querySelectorAll('.cat-chip').forEach(btn => {
        btn.classList.remove('bg-primary-container', 'text-white', 'font-bold');
        btn.classList.add('bg-surface-container-lowest', 'text-on-surface-variant', 'font-semibold');
      });
      button.classList.remove('bg-surface-container-lowest', 'text-on-surface-variant', 'font-semibold');
      button.classList.add('bg-primary-container', 'text-white', 'font-bold');
      activeCategory = button.dataset.category;
      applyCatalogFilter();
    });
  });

  document.getElementById('product-search').addEventListener('input', applyCatalogFilter);

  /* ---------------- Nota (cart) ---------------- */
  function renderCartDrawer() {
    const container = document.getElementById('cart-items-container');
    if (!container) return;

    if (cartItems.length === 0) {
      container.innerHTML = `
        <div class="py-8 text-center text-xs text-on-surface-variant flex flex-col items-center gap-2">
          <div class="w-12 h-12 rounded-full bg-surface-container flex items-center justify-center text-outline">
            <span class="material-symbols-outlined text-[28px]">remove_shopping_cart</span>
          </div>
          <span class="font-semibold text-on-surface">Belum ada material dalam nota</span>
          <span class="text-[11px] text-outline">Pilih produk dari katalog untuk memulai pesanan.</span>
        </div>`;
    } else {
      container.innerHTML = cartItems.map(item => {
        const line = calcLine(item.price, item.qty);
        return `
        <div class="cart-line-item flex flex-col gap-2 pb-3 border-b border-surface-container last:border-b-0 last:pb-0" data-id="${item.id}">
          <div class="flex items-start gap-2.5">
            <div class="w-9 h-9 rounded-lg ${item.iconBg || 'bg-slate-100 text-slate-700'} flex items-center justify-center shrink-0">
              <span class="material-symbols-outlined text-[20px]">${item.icon || 'inventory_2'}</span>
            </div>
            <div class="flex flex-col min-w-0 flex-1">
              <span class="text-xs font-semibold text-on-surface leading-snug">${item.name}</span>
              <span class="text-[11px] text-on-surface-variant">${formatRupiah(item.price)} / ${item.unit}</span>
              ${item.note ? `<span class="text-[10px] text-amber-700 italic truncate">${item.note}</span>` : ''}
            </div>
            <button aria-label="Hapus ${item.name}" class="w-7 h-7 flex items-center justify-center rounded-lg text-outline hover:text-error active:bg-surface-container transition-colors shrink-0" onclick="removeCartItemById('${item.id}')" type="button">
              <span class="material-symbols-outlined text-[18px] text-red-500">delete</span>
            </button>
          </div>
          <div class="flex items-center justify-between gap-2 pl-[46px]">
            <div class="flex items-center gap-1.5 bg-surface rounded-lg p-0.5 border border-outline-variant/40">
              <button aria-label="Kurang ${item.name}" class="w-7 h-7 flex items-center justify-center rounded bg-surface-container-lowest hover:bg-surface-container active:scale-90 transition-transform text-on-surface" onclick="changeCartItemQty('${item.id}', -1)" type="button">
                <span class="material-symbols-outlined text-[15px]">remove</span>
              </button>
              <span class="text-xs font-bold w-6 text-center text-on-surface font-mono">${item.qty}</span>
              <button aria-label="Tambah ${item.name}" class="w-7 h-7 flex items-center justify-center rounded bg-primary-container text-white active:scale-90 transition-transform" onclick="changeCartItemQty('${item.id}', 1)" type="button">
                <span class="material-symbols-outlined text-[15px]">add</span>
              </button>
            </div>
            <div class="text-right">
              ${line.discount ? `<span class="block text-[10px] text-emerald-700 font-bold">Grosir -3%</span>` : ''}
              <span class="text-xs font-bold text-on-surface block">${formatRupiah(line.net)}</span>
            </div>
          </div>
        </div>`;
      }).join('');
    }
    updateCartSummaryDisplays();
  }

  function updateCartSummaryDisplays() {
    let totalCount = 0, gross = 0, discount = 0;
    cartItems.forEach(item => {
      const line = calcLine(item.price, item.qty);
      totalCount += item.qty;
      gross += line.gross;
      discount += line.discount;
    });
    const net = gross - discount;

    // Mobile
    document.getElementById('cart-badge-count').textContent = totalCount;
    document.getElementById('cart-badge-label').textContent = `${totalCount} Item Terpilih`;
    document.getElementById('cart-total-display').textContent = formatRupiah(net);
    // Drawer / sidebar header
    document.getElementById('cart-drawer-count').textContent = `${totalCount} Item Material`;
    // Desktop footer
    document.getElementById('d-count').textContent = totalCount;
    document.getElementById('d-subtotal').textContent = formatRupiah(gross);
    document.getElementById('d-total').textContent = formatRupiah(net);
    const dRow = document.getElementById('d-discount-row');
    dRow.classList.toggle('hidden', discount === 0);
    dRow.classList.toggle('flex', discount > 0);
    document.getElementById('d-discount').textContent = `-${formatRupiah(discount)}`;
  }

  function changeCartItemQty(id, delta) {
    const item = cartItems.find(it => it.id === id);
    if (!item) return;
    const newQty = item.qty + delta;
    if (newQty <= 0) { removeCartItemById(id); return; }
    item.qty = newQty;
    renderCartDrawer();
    showToast(`Kuantitas ${item.name} diubah (${newQty} ${item.unit})`);
  }

  function removeCartItemById(id) {
    const item = cartItems.find(it => it.id === id);
    const itemName = item ? item.name : 'Material';
    cartItems = cartItems.filter(it => it.id !== id);
    renderCartDrawer();
    showToast(`${itemName} dihapus dari daftar muatan nota`);
  }

  // Hanya berpengaruh di mobile; di md+ drawer selalu tampil lewat md:!flex
  function toggleCartDrawer() {
    const drawer = document.getElementById('cart-drawer');
    const chevron = document.getElementById('cart-drawer-chevron');
    const indicator = document.getElementById('cart-toggle-indicator');
    if (!drawer) return;
    const isHidden = drawer.classList.contains('hidden');
    drawer.classList.toggle('hidden', !isHidden);
    if (chevron) chevron.textContent = 'expand_more';
    if (indicator) indicator.textContent = isHidden ? 'expand_less' : 'unfold_more';
  }

  /* ---------------- Modal kuantitas ---------------- */
  function openQuantityModal(product) {
    activeProduct = product;
    currentQty = 1;

    document.getElementById('modal-product-title').textContent = product.name;
    document.getElementById('modal-category').textContent = product.category;
    document.getElementById('modal-stock-badge').textContent = `Stok: ${product.stock} ${product.unit}`;
    document.getElementById('modal-unit-price').textContent = `${formatRupiah(product.price)} / ${product.unit}`;
    document.getElementById('modal-unit-label').textContent = `Satuan: ${product.unit}`;
    document.getElementById('modal-icon').textContent = product.icon || 'inventory_2';
    document.getElementById('modal-icon-container').className =
      `w-12 h-12 rounded-xl flex items-center justify-center ${product.iconBg || 'bg-slate-100 text-slate-700'} shadow-sm shrink-0`;

    document.getElementById('modal-qty-input').value = currentQty;
    document.getElementById('item-notes').value = '';
    updateModalCalculation();

    const modal = document.getElementById('quantity-modal');
    const sheet = document.getElementById('quantity-sheet');
    modal.classList.remove('opacity-0', 'pointer-events-none');
    modal.classList.add('opacity-100');
    // Mobile: slide-up | md+: zoom-fade
    sheet.classList.remove('translate-y-full', 'md:scale-95', 'md:opacity-0');
    sheet.classList.add('translate-y-0', 'md:scale-100', 'md:opacity-100');
    document.body.style.overflow = 'hidden';
  }

  function closeQuantityModal() {
    const modal = document.getElementById('quantity-modal');
    const sheet = document.getElementById('quantity-sheet');
    if (sheet) {
      sheet.classList.remove('translate-y-0', 'md:scale-100', 'md:opacity-100');
      sheet.classList.add('translate-y-full', 'md:scale-95', 'md:opacity-0');
    }
    setTimeout(() => {
      if (modal) {
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0', 'pointer-events-none');
      }
      releasePageScrollLock();
    }, 200);
  }

  function changeQuantityStep(delta) {
    currentQty = Math.max(1, Math.min(activeProduct.stock || 999, currentQty + delta));
    document.getElementById('modal-qty-input').value = currentQty;
    updateModalCalculation();
  }

  function onQuantityInput(val) {
    const parsed = parseInt(val, 10);
    const maxStock = activeProduct.stock || 999;
    if (isNaN(parsed) || parsed < 1) {
      currentQty = 1;
    } else if (parsed > maxStock) {
      currentQty = maxStock;
      showToast(`Maksimal stok tersedia ${maxStock} ${activeProduct.unit}`);
      document.getElementById('modal-qty-input').value = currentQty;
    } else {
      currentQty = parsed;
    }
    updateModalCalculation();
  }

  function addQuantityPreset(preset) {
    const maxStock = activeProduct.stock || 999;
    currentQty = Math.min(maxStock, currentQty + preset);
    document.getElementById('modal-qty-input').value = currentQty;
    updateModalCalculation();
  }

  function updateModalCalculation() {
    const { gross, discount, net } = calcLine(activeProduct.price, currentQty);
    const discountRow = document.getElementById('summary-discount-row');
    discountRow.classList.toggle('hidden', discount === 0);
    discountRow.classList.toggle('flex', discount > 0);
    document.getElementById('summary-discount-amount').textContent = `-${formatRupiah(discount)}`;

    document.getElementById('summary-qty-formula').textContent = `${currentQty} ${activeProduct.unit}`;
    document.getElementById('summary-unit-price').textContent = formatRupiah(activeProduct.price);
    document.getElementById('summary-normal-price').textContent = formatRupiah(gross);
    document.getElementById('modal-subtotal-price').textContent = formatRupiah(net);
    document.getElementById('modal-confirm-label').textContent = `Tambahkan ke Nota • ${formatRupiah(net)}`;
  }

  function submitQuantityModal() {
    const note = document.getElementById('item-notes').value.trim();
    const existing = cartItems.find(it => it.id === activeProduct.id);
    if (existing) {
      existing.qty += currentQty;
      if (note) existing.note = note;
    } else {
      cartItems.push({
        id: activeProduct.id,
        name: activeProduct.name,
        category: activeProduct.category,
        unit: activeProduct.unit,
        price: activeProduct.price,
        qty: currentQty,
        icon: activeProduct.icon || 'inventory_2',
        iconBg: activeProduct.iconBg || 'bg-slate-100 text-slate-700',
        note: note
      });
    }
    renderCartDrawer();
    closeQuantityModal();
    showToast(`Item berhasil ditambahkan ke nota (+${currentQty} ${activeProduct.unit} ${activeProduct.name})`);
  }

  /* ---------------- Toast & pembayaran ---------------- */
  let toastTimer = null;
  function showToast(msg) {
    const toast = document.getElementById('quick-toast');
    const toastMsg = document.getElementById('toast-message');
    if (!toast || !toastMsg) return;
    toastMsg.textContent = msg;
    toast.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-2');
    toast.classList.add('opacity-100', 'translate-y-0');
    if (toastTimer) clearTimeout(toastTimer);
    toastTimer = setTimeout(() => {
      toast.classList.remove('opacity-100', 'translate-y-0');
      toast.classList.add('opacity-0', 'pointer-events-none', 'translate-y-2');
    }, 2500);
  }

  // Dipakai di onclick tombol Bayar. Return false = batalkan navigasi link.
  function processPaymentModal() {
    const totalCount = cartItems.reduce((acc, it) => acc + it.qty, 0);
    if (totalCount === 0) {
      showToast('Keranjang nota masih kosong! Tambahkan material terlebih dahulu.');
      return false;
    }
    showToast(`Membuka Terminal Kasir untuk ${totalCount} material nota...`);
    return true;
  }

  /* ---------------- Init ---------------- */
  renderCatalog();
  renderCartDrawer();

// Fungsi yang dipanggil dari atribut onclick/oninput harus global
Object.assign(window, { addQuantityPreset, changeCartItemQty, changeQuantityStep, closeQuantityModal, onQuantityInput, processPaymentModal, removeCartItemById, submitQuantityModal, toggleCartDrawer });