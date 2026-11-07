// Logika halaman Kasir. Produk diambil dari endpoint /kasir/produk (DB), bukan array hardcode.
// Tombol di HTML memanggil fungsi global (lihat Object.assign di paling bawah).

/* =========================================================
   KONFIG ENDPOINT
   ========================================================= */
const PRODUK_URL = '/kasir/produk';
const KERANJANG_URL = '/kasir/keranjang';
const PEMBAYARAN_URL = '/kasir/pembayaran';

/* =========================================================
   DATA PRODUK (diisi dari endpoint)
   Bentuk tiap produk setelah normalizeProduct():
   id (numerik dari DB), kategoriId (string, dipakai filter chip), name, category,
   unit, pu, price, stock, sku, + gaya kartu (icon, iconBg, grad, ic, tt, st, title, sub, badge)
   ========================================================= */
let products = [];

const customProduct = {
  id:'ongkir-custom', name:'Ongkir Truk Engkel / Pickup', category:'Item Custom / Ongkir', cat:'all',
  unit:'rit', pu:'rit', price:70000, stock:999, icon:'local_shipping', iconBg:'bg-amber-100 text-amber-800'
};

// Isi nota = cermin keranjang di server (tabel keranjang_item), bukan data lokal.
// Bentuk item: { id (id baris keranjang), produkId, name, unit, price, qty, subtotal, custom, icon, iconBg }
let cartItems = [];
let cartTotals = { jumlah_item: 0, total: 0 };
let pendingSync = 0;                 // jumlah request ke server yang belum selesai
let syncQueue = Promise.resolve();   // request dijalankan berurutan

let activeProduct = null;
let currentQty = 1;
let activeCategory = 'all';

/* ---------------- Gaya kartu per kategori ----------------
   DB tidak menyimpan warna/ikon, jadi dipilih dari nama kategori. */
const CATEGORY_STYLES = [
  { match:['semen','pasir'], icon:'inventory_2', iconBg:'bg-slate-100 text-slate-700', grad:'from-slate-200 via-stone-300 to-slate-400', ic:'text-slate-700', tt:'text-slate-800', st:'text-slate-600' },
  { match:['besi','baja','seng','atap'], icon:'reorder', iconBg:'bg-zinc-700 text-white', grad:'from-zinc-300 via-slate-400 to-zinc-600', ic:'text-zinc-100', tt:'text-zinc-100', st:'text-zinc-200' },
  { match:['cat','thinner'], icon:'format_paint', iconBg:'bg-blue-100 text-blue-800', grad:'from-amber-50 via-sky-100 to-blue-300', ic:'text-blue-800', tt:'text-blue-900', st:'text-blue-700' },
  { match:['pipa','fitting'], icon:'plumbing', iconBg:'bg-sky-100 text-sky-700', grad:'from-slate-100 via-slate-300 to-slate-400', ic:'text-sky-700', tt:'text-slate-800', st:'text-slate-600' },
  { match:['alat','paku','kayu','triplek'], icon:'handyman', iconBg:'bg-amber-100 text-amber-900', grad:'from-amber-100 via-orange-100 to-amber-300', ic:'text-amber-900', tt:'text-amber-900', st:'text-amber-800' }
];
const DEFAULT_STYLE = { icon:'inventory_2', iconBg:'bg-slate-100 text-slate-700', grad:'from-slate-100 via-zinc-200 to-slate-300', ic:'text-slate-700', tt:'text-slate-800', st:'text-slate-600' };

function styleFor(categoryName) {
  const key = String(categoryName || '').toLowerCase();
  return CATEGORY_STYLES.find(s => s.match.some(m => key.includes(m))) || DEFAULT_STYLE;
}

/* ---------------- Helpers ---------------- */
function formatRupiah(num) { return 'Rp ' + Number(num).toLocaleString('id-ID'); }

function escapeHtml(value) {
  return String(value ?? '').replace(/[&<>"']/g, ch => (
    { '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#39;' }[ch]
  ));
}

// Harga dihitung server (v_keranjang & NotaController), jadi tanpa potongan grosir di browser
function calcLine(price, qty) {
  const gross = price * qty;
  return { gross, discount: 0, net: gross };
}

function releasePageScrollLock() {
  document.body.style.overflow = '';
  document.documentElement.style.overflow = '';
  document.body.classList.remove('overflow-hidden');
  document.documentElement.classList.remove('overflow-hidden');
}

/* ---------------- Ambil & normalisasi data dari endpoint ----------------
   Kalau nama field di JSON /kasir/produk berbeda, cukup sesuaikan fungsi ini. */
function normalizeProduct(raw) {
  const name = String(raw.nama ?? raw.nama_produk ?? raw.name ?? '');
  const category = String(raw.kategori ?? raw.nama_kategori ?? raw.kategori_nama ?? raw.category ?? 'Lainnya');
  const unit = String(raw.satuan ?? raw.nama_satuan ?? raw.satuan_nama ?? raw.unit ?? 'pcs').toLowerCase();
  const stock = Number(raw.stok ?? raw.stock ?? 0);
  const style = styleFor(category);
  return {
    id: raw.id,
    kategoriId: String(raw.kategori_id ?? ''),
    name, category, unit, pu: unit,
    price: Number(raw.harga ?? raw.harga_jual ?? raw.price ?? 0),
    stock,
    stokStatus: raw.stok_status ?? (stock <= Number(raw.stok_minimum ?? 10) ? 'kritis' : 'aman'),
    sku: raw.sku ?? '',
    ...style,
    title: name.toUpperCase().slice(0, 22),
    sub: raw.sku ? String(raw.sku) : '',
    badge: stock > 50 ? 'Stok Aman' : 'Tersedia'
  };
}

async function loadCatalog() {
  const container = document.getElementById('products-catalog-container');
  const empty = document.getElementById('catalog-empty');
  container.innerHTML = '<div class="col-span-full py-12 text-center text-xs text-on-surface-variant">Memuat produk...</div>';
  try {
    const res = await fetch(PRODUK_URL, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
    if (!res.ok) throw new Error('HTTP ' + res.status);
    const data = await res.json();
    const list = Array.isArray(data) ? data : (data.data ?? data.produk ?? []);
    products = list.map(normalizeProduct);
    renderCategoryChips();
    renderCatalog();
    applyCatalogFilter();
  } catch (err) {
    console.error('Gagal memuat produk:', err);
    container.innerHTML = '';
    empty.classList.remove('hidden');
    empty.innerHTML = '<span class="material-symbols-outlined text-[32px] text-outline block mb-1">error</span>Gagal memuat produk. Muat ulang halaman.';
  }
}

/* ---------------- Chip kategori (dibuat dari data produk) ---------------- */
const chipBar = document.getElementById('category-chips') || document.querySelector('.cat-chip')?.parentElement;

function renderCategoryChips() {
  if (!chipBar) return;
  chipBar.querySelectorAll('.cat-chip:not([data-category="all"])').forEach(b => b.remove());
  const seen = new Map();
  products.forEach(p => { if (p.kategoriId && !seen.has(p.kategoriId)) seen.set(p.kategoriId, p.category); });
  [...seen.entries()].sort((a, b) => Number(a[0]) - Number(b[0])).forEach(([id, label]) => {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.dataset.category = id;
    btn.className = 'cat-chip whitespace-nowrap min-h-[38px] px-3.5 rounded-full text-xs font-semibold bg-surface-container-lowest text-on-surface-variant hover:text-on-surface shadow-sm border border-outline-variant/40 transition-all flex items-center gap-1.5';
    btn.innerHTML = `<span class="material-symbols-outlined text-[16px]">${styleFor(label).icon}</span>${escapeHtml(label)}`;
    chipBar.appendChild(btn);
  });
}

if (chipBar) {
  chipBar.addEventListener('click', (e) => {
    const button = e.target.closest('.cat-chip');
    if (!button) return;
    chipBar.querySelectorAll('.cat-chip').forEach(btn => {
      btn.classList.remove('bg-primary-container', 'text-white', 'font-bold');
      btn.classList.add('bg-surface-container-lowest', 'text-on-surface-variant', 'font-semibold');
    });
    button.classList.remove('bg-surface-container-lowest', 'text-on-surface-variant', 'font-semibold');
    button.classList.add('bg-primary-container', 'text-white', 'font-bold');
    activeCategory = button.dataset.category;
    applyCatalogFilter();
  });
}

/* ---------------- Katalog ---------------- */
function renderCatalog() {
  const container = document.getElementById('products-catalog-container');
  const cards = products.map(p => {
    const critical = p.stokStatus === 'kritis';
    const badgeCls = critical
      ? 'bg-red-100 text-red-700 border-red-300'
      : 'bg-emerald-100 text-emerald-800 border-emerald-300';
    const dotCls = critical ? 'bg-red-600 animate-ping' : 'bg-emerald-600';
    const badgeText = critical ? 'Kritis' : p.badge;
    return `
    <div class="product-item bg-surface-container-lowest rounded-xl p-2 sm:p-2.5 flex flex-col justify-between shadow-sm border border-outline-variant/40 relative overflow-hidden transition-transform active:scale-[0.98] cursor-pointer"
         data-id="${p.id}" data-category="${escapeHtml(p.kategoriId)}" data-search="${escapeHtml((p.name + ' ' + p.category + ' ' + p.sku).toLowerCase())}">
      <div class="relative w-full h-20 sm:h-24 md:h-28 rounded-lg overflow-hidden bg-slate-100 mb-1.5 flex items-center justify-center">
        <div class="w-full h-full bg-gradient-to-br ${p.grad} flex flex-col items-center justify-center p-1.5 text-center">
          <span class="material-symbols-outlined text-[26px] sm:text-[32px] ${p.ic}">${p.icon}</span>
          <span class="text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider ${p.tt} mt-0.5">${escapeHtml(p.title)}</span>
          <span class="text-[8px] ${p.st} hidden sm:block">${escapeHtml(p.sub)}</span>
        </div>
        <span class="absolute top-1.5 left-1.5 px-1.5 rounded-full text-[9px] ${badgeCls} font-bold flex items-center gap-1 shadow-sm border">
          <span class="w-1.5 h-1.5 rounded-full ${dotCls}"></span>${badgeText}
        </span>
      </div>
      <div class="flex flex-col flex-1">
        <span class="text-[9px] font-bold text-amber-700 uppercase tracking-wider">${escapeHtml(p.category)}</span>
        <h3 class="text-xs sm:text-sm font-semibold line-clamp-2 text-on-surface leading-snug mt-0.5">${escapeHtml(p.name)}</h3>
        <span class="text-[11px] text-on-surface-variant mt-0.5">Stok: <b class="${critical ? 'text-error font-bold' : 'text-on-surface'}">${p.stock} ${escapeHtml(p.unit)}</b></span>
      </div>
      <div class="flex items-center justify-between mt-2 pt-1 border-t border-surface-container">
        <div>
          <span class="text-[9px] text-on-surface-variant block leading-tight">Harga / ${escapeHtml(p.pu)}</span>
          <span class="font-bold text-xs sm:text-sm text-on-surface">${formatRupiah(p.price)}</span>
        </div>
        <button aria-label="Tambah ${escapeHtml(p.name)}" class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-primary-container text-white flex items-center justify-center active:bg-secondary-container shadow-sm" type="button">
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
  const product = id === customProduct.id ? customProduct : products.find(p => String(p.id) === id);
  if (product) openQuantityModal(product);
});

/* ---------------- Filter kategori + pencarian ---------------- */
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

document.getElementById('product-search').addEventListener('input', applyCatalogFilter);

/* ---------------- API keranjang (server) ---------------- */
function csrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.content ?? '';
}

async function api(method, url, body) {
  const res = await fetch(url, {
    method,
    credentials: 'same-origin',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': csrfToken()
    },
    body: body ? JSON.stringify(body) : undefined
  });
  let json = {};
  try { json = await res.json(); } catch (_) {}
  if (!res.ok) {
    let msg = json.message || ('Terjadi kesalahan (' + res.status + ')');
    if (res.status === 419 || res.status === 401) msg = 'Sesi habis. Muat ulang halaman lalu coba lagi.';
    if (res.status === 422 && json.errors) msg = Object.values(json.errors).flat()[0] || msg;
    throw new Error(msg);
  }
  return json;
}

// Terapkan payload keranjang dari server { items, jumlah_item, total } ke tampilan
function applyCart(payload) {
  cartTotals = { jumlah_item: Number(payload.jumlah_item) || 0, total: Number(payload.total) || 0 };
  cartItems = (payload.items || []).map(i => {
    const p = i.produk_id ? products.find(x => x.id === i.produk_id) : null;
    const style = i.custom
      ? { icon: 'local_shipping', iconBg: 'bg-amber-100 text-amber-800' }
      : { icon: p?.icon || 'inventory_2', iconBg: p?.iconBg || 'bg-slate-100 text-slate-700' };
    return {
      id: i.id,
      produkId: i.produk_id,
      name: i.nama,
      unit: String(i.satuan || (i.custom ? 'item' : 'pcs')).toLowerCase(),
      price: Number(i.harga),
      qty: Number(i.qty),
      subtotal: Number(i.subtotal),
      custom: !!i.custom,
      ...style
    };
  });
  renderCartDrawer();
}

async function loadCart() {
  try {
    applyCart(await api('GET', KERANJANG_URL));
  } catch (err) {
    showToast(err.message);
  }
}

/* ---------------- Sinkron ke server (optimistik) ----------------
   Tampilan diperbarui langsung; request dikirim berurutan di belakang layar.
   Setelah semua request selesai, tampilan disamakan dengan data server
   (kalau ada yang gagal, keranjang diambil ulang dari server). */
function recalcTotals() {
  cartTotals = {
    jumlah_item: cartItems.reduce((a, it) => a + it.qty, 0),
    total: cartItems.reduce((a, it) => a + it.subtotal, 0)
  };
}

function enqueueSync(task) {
  pendingSync++;
  syncQueue = syncQueue.then(async () => {
    let payload = null;
    try {
      payload = await task();
    } catch (err) {
      showToast(err.message);
    }
    pendingSync--;
    if (pendingSync === 0) {
      if (payload) applyCart(payload); else await loadCart();
    }
  });
}

function isTempItem(item) {
  return String(item.id).startsWith('tmp-');
}

/* ---------------- Nota (cart) ---------------- */
function renderCartDrawer() {
  const container = document.getElementById('cart-items-container');
  if (!container) return;

  cartItems = cartItems.filter(it => it.qty > 0);

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
    container.innerHTML = cartItems.map(item => `
      <div class="cart-line-item flex flex-col gap-2 pb-3 border-b border-surface-container last:border-b-0 last:pb-0" data-id="${item.id}">
        <div class="flex items-start gap-2.5">
          <div class="w-9 h-9 rounded-lg ${item.iconBg} flex items-center justify-center shrink-0">
            <span class="material-symbols-outlined text-[20px]">${item.icon}</span>
          </div>
          <div class="flex flex-col min-w-0 flex-1">
            <span class="text-xs font-semibold text-on-surface leading-snug">${escapeHtml(item.name)}</span>
            <span class="text-[11px] text-on-surface-variant">${formatRupiah(item.price)} / ${escapeHtml(item.unit)}</span>
          </div>
          <button aria-label="Hapus ${escapeHtml(item.name)}" class="w-7 h-7 flex items-center justify-center rounded-lg text-outline hover:text-error active:bg-surface-container transition-colors shrink-0" onclick="removeCartItemById('${item.id}')" type="button">
            <span class="material-symbols-outlined text-[18px] text-red-500">delete</span>
          </button>
        </div>
        <div class="flex items-center justify-between gap-2 pl-[46px]">
          <div class="flex items-center gap-1.5 bg-surface rounded-lg p-0.5 border border-outline-variant/40">
            <button aria-label="Kurang ${escapeHtml(item.name)}" class="w-7 h-7 flex items-center justify-center rounded bg-surface-container-lowest hover:bg-surface-container active:scale-90 transition-transform text-on-surface" onclick="changeCartItemQty('${item.id}', -1)" type="button">
              <span class="material-symbols-outlined text-[15px]">remove</span>
            </button>
            <span class="text-xs font-bold w-6 text-center text-on-surface font-mono">${item.qty}</span>
            <button aria-label="Tambah ${escapeHtml(item.name)}" class="w-7 h-7 flex items-center justify-center rounded bg-primary-container text-white active:scale-90 transition-transform" onclick="changeCartItemQty('${item.id}', 1)" type="button">
              <span class="material-symbols-outlined text-[15px]">add</span>
            </button>
          </div>
          <div class="text-right">
            <span class="text-xs font-bold text-on-surface block">${formatRupiah(item.subtotal)}</span>
          </div>
        </div>
      </div>`).join('');
  }
  updateCartSummaryDisplays();
}

function updateCartSummaryDisplays() {
  const totalCount = cartTotals.jumlah_item;
  const net = cartTotals.total;

  // Mobile
  document.getElementById('cart-badge-count').textContent = totalCount;
  document.getElementById('cart-badge-label').textContent = `${totalCount} Item Terpilih`;
  document.getElementById('cart-total-display').textContent = formatRupiah(net);
  // Drawer / sidebar header
  document.getElementById('cart-drawer-count').textContent = `${totalCount} Item Material`;
  // Desktop footer
  document.getElementById('d-count').textContent = totalCount;
  document.getElementById('d-subtotal').textContent = formatRupiah(net);
  document.getElementById('d-total').textContent = formatRupiah(net);
  const dRow = document.getElementById('d-discount-row');
  dRow.classList.add('hidden');
  dRow.classList.remove('flex');
}

// id dari atribut onclick selalu string, sedangkan id produk dari DB numerik -> bandingkan sebagai string
function changeCartItemQty(id, delta) {
  const item = cartItems.find(it => String(it.id) === String(id));
  if (!item) return;
  if (isTempItem(item)) { showToast('Item sedang disimpan, coba lagi sebentar'); return; }

  const newQty = item.qty + delta;
  if (newQty <= 0) { removeCartItemById(id); return; }

  const p = item.produkId ? products.find(x => String(x.id) === String(item.produkId)) : null;
  if (p && newQty > p.stock) { showToast(`Stok tidak cukup. Sisa stok: ${p.stock}`); return; }

  item.qty = newQty;
  item.subtotal = item.price * newQty;
  recalcTotals();
  renderCartDrawer();
  showToast(`Kuantitas ${item.name} diubah (${newQty} ${item.unit})`);

  enqueueSync(() => api('PATCH', `${KERANJANG_URL}/${item.id}`, { qty: newQty }));
}

function removeCartItemById(id) {
  const item = cartItems.find(it => String(it.id) === String(id));
  if (!item) return;
  if (isTempItem(item)) { showToast('Item sedang disimpan, coba lagi sebentar'); return; }

  cartItems = cartItems.filter(it => String(it.id) !== String(id));
  recalcTotals();
  renderCartDrawer();
  showToast(`${item.name} dihapus dari daftar muatan nota`);

  enqueueSync(() => api('DELETE', `${KERANJANG_URL}/${id}`));
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
  if (val === '' || isNaN(parsed)) {
    // Kolom dikosongkan / sedang mengetik: tanpa peringatan, tapi belum boleh ditambahkan
    currentQty = 0;
  } else if (parsed < 1) {
    // Nilai 0 atau minus: tutup modal lalu tampilkan peringatan (toast tampak jelas setelah modal tertutup)
    currentQty = 0;
    closeQuantityModal();
    showToast('Angka tidak boleh kurang dari nol (minimal 1)');
    return;
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
  // Penjaga terakhir: jumlah di kolom harus angka bulat >= 1 sebelum masuk nota
  if (!Number.isInteger(currentQty) || currentQty < 1) {
    showToast('Jumlah minimal 1. Angka tidak boleh kurang dari nol');
    return;
  }
  const product = activeProduct;
  const qty = currentQty;
  const isCustom = product.id === customProduct.id;

  if (!isCustom) {
    const sudah = cartItems
      .filter(it => String(it.produkId) === String(product.id))
      .reduce((a, it) => a + it.qty, 0);
    if (sudah + qty > product.stock) {
      showToast(`Stok tidak cukup. Sisa stok: ${product.stock}` + (sudah ? ` (sudah ${sudah} di nota)` : ''));
      return;
    }
  }

  // Tampilan langsung berubah, simpan ke server di belakang layar
  const existing = isCustom ? null : cartItems.find(it => String(it.produkId) === String(product.id));
  if (existing) {
    existing.qty += qty;
    existing.subtotal = existing.price * existing.qty;
  } else {
    cartItems.push({
      id: 'tmp-' + Date.now() + '-' + Math.round(Math.random() * 1e6),
      produkId: isCustom ? null : product.id,
      name: product.name,
      unit: product.unit,
      price: product.price,
      qty,
      subtotal: product.price * qty,
      custom: isCustom,
      icon: product.icon || 'inventory_2',
      iconBg: product.iconBg || 'bg-slate-100 text-slate-700'
    });
  }
  recalcTotals();
  renderCartDrawer();
  closeQuantityModal();
  showToast(`Item berhasil ditambahkan ke nota (+${qty} ${product.unit} ${product.name})`);

  enqueueSync(() => isCustom
    ? api('POST', KERANJANG_URL + '/custom', { nama_item: product.name, harga: product.price, qty })
    : api('POST', KERANJANG_URL, { produk_id: product.id, qty }));
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
  // Masih ada penyimpanan yang berjalan: tunggu selesai, baru buka halaman pembayaran
  if (pendingSync > 0) {
    showToast('Menyimpan nota, mohon tunggu sebentar...');
    syncQueue.then(() => { if (cartTotals.jumlah_item > 0) window.location.href = PEMBAYARAN_URL; });
    return false;
  }
  const totalCount = cartTotals.jumlah_item;
  if (totalCount === 0) {
    showToast('Keranjang nota masih kosong! Tambahkan material terlebih dahulu.');
    return false;
  }
  showToast(`Membuka Terminal Kasir untuk ${totalCount} material nota...`);
  return true;
}

/* ---------------- Init ---------------- */
renderCartDrawer();
loadCatalog().then(loadCart);   // katalog dulu supaya ikon produk bisa dicocokkan
// Kembali dari halaman pembayaran lewat tombol back: ambil ulang keranjang dari server
window.addEventListener('pageshow', (e) => { if (e.persisted) loadCart(); });

// Fungsi yang dipanggil dari atribut onclick/oninput harus global
Object.assign(window, { addQuantityPreset, changeCartItemQty, changeQuantityStep, closeQuantityModal, onQuantityInput, processPaymentModal, removeCartItemById, submitQuantityModal, toggleCartDrawer });