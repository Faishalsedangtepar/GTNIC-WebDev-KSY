// resources/js/pembayaran.js
// Halaman Checkout Kasir -> tersambung ke NotaController (POST /kasir/nota, GET /kasir/nota/{id})

var DP_MINIMAL = 0.30;        // DP = 30% dari total harga barang; harus sama dengan NotaController::DP_MINIMAL
var BIAYA_PROTEKSI = 5000;    // harus sama dengan NotaController::BIAYA_PROTEKSI

// key UI -> nilai yang diterima backend
var ARMADA_API = { truk: 'truk', pickup: 'pickup', self: 'ambil_sendiri' };

var armadaConfig = {
  truk: {
    title: 'Armada Toko - Truk Engkel (Rp 110.000)',
    summaryTitle: 'Biaya Armada Toko (Truk Engkel)',
    cost: 110000,
    costLabel: 'Rp 110.000',
    badgeClass: 'bg-sky-50 text-sky-800 border-sky-200',
    estimasi: 'Estimasi kirim hari ini: <strong>Pukul 14.00 - 16.00 WIB</strong>',
    beban: 'Total Beban: ~3.2 Ton'
  },
  pickup: {
    title: 'Pick-up L300 Cepat (Rp 65.000)',
    summaryTitle: 'Biaya Armada Pick-up L300',
    cost: 65000,
    costLabel: 'Rp 65.000',
    badgeClass: 'bg-blue-50 text-blue-800 border-blue-200',
    estimasi: 'Estimasi kirim express: <strong>Pukul 12.00 - 13.30 WIB</strong>',
    beban: 'Total Beban: ~1.2 Ton (Kargo Cepat)'
  },
  self: {
    title: 'Ambil Sendiri di Toko / Gudang (Gratis)',
    summaryTitle: 'Biaya Pengiriman (Ambil Sendiri)',
    cost: 0,
    costLabel: 'Rp 0 (Gratis)',
    badgeClass: 'bg-emerald-50 text-emerald-800 border-emerald-200',
    estimasi: 'Pengambilan mandiri: <strong>Siap diambil di Gudang Barat</strong>',
    beban: 'Armada Pembeli Sendiri'
  }
};

// ---------------------------------------------------------------- state
var selectedArmadaKey = 'self';
var currentConfirmedArmada = 'self';
var currentPaymentType = 'dp';
var baseMaterial = 2972000;      // di-override dari data-subtotal di blade
var currentGrandTotal = 0;
var dpAmount = 1000000;
var isSubmitting = false;
var sudahSelesai = false;
var refreshKembalian = function () {};

// ---------------------------------------------------------------- helpers
function $(id) { return document.getElementById(id); }

function getCleanNumeric(val) {
  var cleaned = String(val).replace(/[^0-9]/g, '');
  return cleaned ? parseInt(cleaned, 10) : 0;
}

function formatRupiahNum(num) {
  num = Math.max(0, Math.round(Number(num) || 0));
  return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function ribuan(num) {
  return num ? num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '';
}

function esc(s) {
  return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
    return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
  });
}

function tagihanSekarang() {
  return currentPaymentType === 'dp' ? dpAmount : currentGrandTotal;
}

// DP = 30% dari total harga barang (tanpa ongkir & proteksi), sama dengan NotaController
function dpError() {
  if (currentPaymentType !== 'dp') return '';
  if (dpAmount <= 0 || dpAmount >= currentGrandTotal) return 'DP harus kurang dari total. Pilih Lunas untuk bayar penuh.';
  return '';
}

// ---------------------------------------------------------------- armada
function openArmadaModal() {
  selectedArmadaKey = currentConfirmedArmada;
  updateModalRadioHighlight(selectedArmadaKey);
  var modal = $('armada-modal');
  if (modal) modal.classList.remove('hidden');
}

function closeArmadaModal() {
  var modal = $('armada-modal');
  if (modal) modal.classList.add('hidden');
}

function selectArmadaOption(key) {
  selectedArmadaKey = key;
  updateModalRadioHighlight(key);
}

function updateModalRadioHighlight(activeKey) {
  ['truk', 'pickup', 'self'].forEach(function (k) {
    var card = $('armada-card-' + k);
    var radio = $('radio-' + k);
    if (radio) radio.checked = (k === activeKey);
    if (card) {
      card.className = k === activeKey
        ? 'block p-4 rounded-xl border-2 border-slate-900 bg-slate-50/80 cursor-pointer transition-all shadow-xs'
        : 'block p-4 rounded-xl border border-slate-200 hover:border-slate-400 bg-white cursor-pointer transition-all';
    }
  });
}

function applyArmadaSelection() {
  currentConfirmedArmada = selectedArmadaKey;
  var cfg = armadaConfig[currentConfirmedArmada];

  var badge = $('armada-badge-label');
  if (badge) {
    badge.textContent = cfg.title;
    badge.className = 'text-xs font-semibold px-2 py-0.5 rounded border ' + cfg.badgeClass;
  }
  if ($('armada-estimasi-info')) $('armada-estimasi-info').innerHTML = cfg.estimasi;
  if ($('armada-beban-info')) $('armada-beban-info').textContent = cfg.beban;
  if ($('summary-armada-title')) $('summary-armada-title').textContent = cfg.summaryTitle;
  if ($('summary-armada-cost')) $('summary-armada-cost').textContent = cfg.costLabel;

  recalculateDpValues();
  closeArmadaModal();
}

// ---------------------------------------------------------------- total & DP
function hitungTotal() {
  var cb = $('proteksi-check');
  var proteksi = cb && cb.checked ? BIAYA_PROTEKSI : 0;
  currentGrandTotal = baseMaterial + armadaConfig[currentConfirmedArmada].cost + proteksi;
  if ($('summary-proteksi-val')) $('summary-proteksi-val').textContent = formatRupiahNum(proteksi);
}

function recalculateDpValues() {
  hitungTotal();
  dpAmount = Math.ceil(baseMaterial * DP_MINIMAL);   // 30% dari total harga barang

  var sisa = Math.max(0, currentGrandTotal - dpAmount);

  if ($('dp-display-amount')) $('dp-display-amount').textContent = formatRupiahNum(dpAmount);
  if ($('dp-sisa-pelunasan')) $('dp-sisa-pelunasan').textContent = formatRupiahNum(sisa);
  if ($('summary-dp-val')) $('summary-dp-val').textContent = '- ' + formatRupiahNum(dpAmount);
  if ($('summary-sisa-val')) $('summary-sisa-val').textContent = formatRupiahNum(sisa);

  var hint = $('dp-hint');
  if (hint) {
    var err = dpError();
    hint.textContent = err || 'Sisa dilunasi saat barang tiba di lokasi.';
    hint.className = 'text-[11px] ' + (err ? 'text-rose-600 font-semibold' : 'text-slate-500');
  }

  if (currentPaymentType === 'dp') {
    $('summary-total-tagihan').textContent = formatRupiahNum(dpAmount);
    $('summary-label-total').textContent = 'Tagihan DP Dibayar Sekarang';
    $('summary-badge-status').textContent = 'Uang Muka (DP)';
    if (!sudahSelesai) $('btn-submit-kasir-text').textContent = 'Bayar DP & Cetak Nota DO';
  } else {
    $('summary-total-tagihan').textContent = formatRupiahNum(currentGrandTotal);
    $('summary-label-total').textContent = 'Total Tagihan Final';
    $('summary-badge-status').textContent = 'Lunas';
    if (!sudahSelesai) $('btn-submit-kasir-text').textContent = 'Selesaikan & Cetak Nota Kasir';
  }

  refreshKembalian();
}

function selectPaymentMethod(type) {
  currentPaymentType = type;
  var on = 'payment-option flex items-center justify-between p-3.5 rounded-xl border-2 border-slate-900 bg-slate-50/80 cursor-pointer transition-all shadow-xs';
  var off = 'payment-option flex items-center justify-between p-3.5 rounded-xl border border-slate-200 hover:border-slate-300 bg-white cursor-pointer transition-all';
  var isDp = type === 'dp';

  if ($('radio-lunas')) $('radio-lunas').checked = !isDp;
  if ($('radio-dp')) $('radio-dp').checked = isDp;
  if ($('payment-opt-lunas')) $('payment-opt-lunas').className = isDp ? off : on;
  if ($('payment-opt-dp')) $('payment-opt-dp').className = isDp ? on : off;

  ['dp-input-panel', 'summary-dp-row', 'summary-sisa-row'].forEach(function (id) {
    if ($(id)) $(id).classList.toggle('hidden', !isDp);
  });

  recalculateDpValues();
}

// ---------------------------------------------------------------- alert
function showAlert(type, messages) {
  var box = $('checkout-alert');
  if (!box) { alert([].concat(messages).join('\n')); return; }
  var list = [].concat(messages);
  box.className = 'text-xs rounded-lg border px-3 py-2 space-y-1 ' + (type === 'ok'
    ? 'bg-emerald-50 border-emerald-200 text-emerald-800'
    : 'bg-rose-50 border-rose-200 text-rose-700');
  box.innerHTML = list.map(function (m) { return '<div>' + esc(m) + '</div>'; }).join('');
  box.classList.remove('hidden');
}

function clearAlert() {
  var box = $('checkout-alert');
  if (box) { box.classList.add('hidden'); box.innerHTML = ''; }
}

function pesanDariResponse(status, json) {
  if (status === 419 || status === 401) return ['Sesi login/keamanan habis. Muat ulang halaman lalu coba lagi.'];
  if (status === 422 && json && json.errors) {
    var out = [];
    Object.keys(json.errors).forEach(function (k) { out = out.concat(json.errors[k]); });
    return out;
  }
  return [(json && json.message) || ('Terjadi kesalahan di server (' + status + ').')];
}

// ---------------------------------------------------------------- submit
function setSubmitting(state) {
  isSubmitting = state;
  var btn = $('btn-submit-kasir');
  if (!btn) return;
  if (state) {
    btn.disabled = true;
    btn.classList.add('opacity-50', 'cursor-not-allowed');
    $('btn-submit-kasir-text').textContent = 'Memproses...';
  } else {
    recalculateDpValues();   // kembalikan label + status tombol
  }
}

function bacaForm() {
  var val = function (id) { var el = $(id); return el ? el.value.trim() : ''; };
  var uang = getCleanNumeric($('uang-diterima').value);
  var cb = $('proteksi-check');

  var payload = {
    armada: ARMADA_API[currentConfirmedArmada],
    proteksi: !!(cb && cb.checked),
    metode: currentPaymentType,
    uang_diterima: uang > 0 ? uang : null,
    nama_pembeli: val('buyer-nama'),
    no_hp: val('buyer-hp'),
    alamat_pengiriman: val('buyer-alamat'),
    catatan_lokasi: val('buyer-catatan-lokasi'),
    catatan_sopir: val('catatan-sopir')
  };
  return payload;
}

async function submitKasir() {
  if (isSubmitting) return;

  if (sudahSelesai) {                    // setelah sukses, tombol jadi "Transaksi Baru"
    var url = $('btn-submit-kasir').dataset.urlSelesai;
    if (url) window.location.href = url; else history.back();
    return;
  }

  clearAlert();
  var err = dpError();
  if (err) { showAlert('error', err); return; }

  var tokenEl = document.querySelector('meta[name="csrf-token"]');
  var storeUrl = $('btn-submit-kasir').dataset.urlStore;

  setSubmitting(true);
  try {
    var res = await fetch(storeUrl, {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': tokenEl ? tokenEl.content : ''
      },
      body: JSON.stringify(bacaForm())
    });
    var json = await res.json().catch(function () { return {}; });

    if (!res.ok) {
      showAlert('error', pesanDariResponse(res.status, json));
      setSubmitting(false);
      return;
    }
    onSukses(json);
  } catch (e) {
    showAlert('error', 'Tidak bisa terhubung ke server. Periksa koneksi lalu coba lagi.');
    setSubmitting(false);
  }
}

function onSukses(nota) {
  sudahSelesai = true;
  isSubmitting = false;

  var pesan = ['Nota ' + nota.nomor + ' tersimpan (' + String(nota.status_bayar).toUpperCase() + ').',
    'Dibayar: ' + formatRupiahNum(nota.dibayar) + ' dari total ' + formatRupiahNum(nota.total) + '.'];
  if (nota.sisa > 0) pesan.push('Sisa pelunasan: ' + formatRupiahNum(nota.sisa) + '.');
  pesan.push('Kembalian: ' + formatRupiahNum(nota.kembalian));
  showAlert('ok', pesan);

  // kunci semua input
  document.querySelectorAll('main input, main textarea').forEach(function (el) { el.disabled = true; });

  var btn = $('btn-submit-kasir');
  btn.disabled = false;
  btn.classList.remove('opacity-50', 'cursor-not-allowed');
  $('btn-submit-kasir-text').textContent = 'Selesai - Transaksi Baru';

  cetakNota(nota.id);
}

// ---------------------------------------------------------------- cetak nota
async function cetakNota(id) {
  try {
    var base = $('btn-submit-kasir').dataset.urlStore;
    var res = await fetch(base + '/' + id, {
      credentials: 'same-origin',
      headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    });
    if (!res.ok) throw new Error('gagal ambil nota');
    var n = await res.json();

    var rows = n.items.map(function (i) {
      return '<tr><td colspan="3">' + esc(i.nama) + '</td></tr>' +
        '<tr><td>' + i.qty + ' ' + esc(i.satuan) + ' x ' + formatRupiahNum(i.harga) + '</td><td></td>' +
        '<td class="r">' + formatRupiahNum(i.subtotal) + '</td></tr>';
    }).join('');

    var baris = function (label, v) {
      return '<tr><td colspan="2">' + label + '</td><td class="r">' + v + '</td></tr>';
    };

    var html = '<!doctype html><html><head><meta charset="utf-8"><title>' + esc(n.nomor) + '</title>' +
      '<style>body{font:12px/1.4 monospace;width:280px;margin:0 auto;padding:8px}' +
      'table{width:100%;border-collapse:collapse}.r{text-align:right}h1{font-size:14px;text-align:center;margin:0}' +
      'hr{border:0;border-top:1px dashed #000;margin:6px 0}p{margin:2px 0}</style></head><body>' +
      '<h1>AURA MATERIAL</h1><p style="text-align:center">Nota Kasir</p><hr>' +
      '<p>No: ' + esc(n.nomor) + '</p><p>Tgl: ' + esc(n.tanggal) + '</p>' +
      (n.nama_pembeli ? '<p>Pembeli: ' + esc(n.nama_pembeli) + '</p>' : '') +
      (n.no_hp ? '<p>HP: ' + esc(n.no_hp) + '</p>' : '') +
      (n.alamat_pengiriman ? '<p>Kirim ke: ' + esc(n.alamat_pengiriman) + '</p>' : '') +
      (n.catatan_lokasi ? '<p>Lokasi: ' + esc(n.catatan_lokasi) + '</p>' : '') +
      '<hr><table>' + rows + '</table><hr><table>' +
      baris('Ongkir (' + esc(n.armada) + ')', formatRupiahNum(n.ongkir)) +
      (n.biaya_proteksi > 0 ? baris('Proteksi terpal', formatRupiahNum(n.biaya_proteksi)) : '') +
      baris('<b>TOTAL</b>', '<b>' + formatRupiahNum(n.total) + '</b>') +
      baris('Dibayar', formatRupiahNum(n.dibayar)) +
      (n.sisa > 0 ? baris('<b>SISA</b>', '<b>' + formatRupiahNum(n.sisa) + '</b>') : '') +
      '</table><hr><p>Status: ' + esc(String(n.status_bayar).toUpperCase()) + '</p>' +
      (n.catatan_sopir ? '<p>Catatan sopir: ' + esc(n.catatan_sopir) + '</p>' : '') +
      '<p style="text-align:center;margin-top:8px">Terima kasih</p></body></html>';

    var f = document.createElement('iframe');
    f.style.cssText = 'position:fixed;right:0;bottom:0;width:0;height:0;border:0';
    document.body.appendChild(f);
    var d = f.contentWindow.document;
    d.open(); d.write(html); d.close();
    setTimeout(function () { f.contentWindow.focus(); f.contentWindow.print(); }, 250);
    setTimeout(function () { f.remove(); }, 60000);
  } catch (e) {
    showAlert('ok', ['Nota tersimpan, tetapi gagal membuka tampilan cetak. Buka lagi dari Riwayat Nota.']);
  }
}

// ---------------------------------------------------------------- uang diterima & kembalian
function initUangPelanggan() {
  var input = $('uang-diterima');
  var row = $('kembalian-row');
  var label = $('kembalian-label');
  var val = $('kembalian-val');
  var submit = $('btn-submit-kasir');
  if (!input || !row || !label || !val || !submit) return;

  var base = 'flex items-center justify-between px-3 py-2 rounded-lg border ';
  var styles = {
    netral: base + 'bg-white border-slate-200 text-slate-600',
    ok: base + 'bg-emerald-50 border-emerald-200 text-emerald-700',
    kurang: base + 'bg-rose-50 border-rose-200 text-rose-700'
  };

  refreshKembalian = function () {
    if (sudahSelesai) return;
    var total = tagihanSekarang();
    var uang = getCleanNumeric(input.value);

    input.value = uang ? uang.toLocaleString('id-ID') : '';
    input.dataset.raw = uang;

    if (uang === 0) {
      row.className = styles.netral;
      label.textContent = 'Kembalian';
      val.textContent = 'Rp 0';
    } else if (uang >= total) {
      row.className = styles.ok;
      label.textContent = 'Kembalian';
      val.textContent = formatRupiahNum(uang - total);
    } else {
      row.className = styles.kurang;
      label.textContent = 'Uang Kurang';
      val.textContent = formatRupiahNum(total - uang);
    }

    var bolehBayar = uang >= total && total > 0 && !dpError() && !isSubmitting;
    submit.disabled = !bolehBayar;
    submit.classList.toggle('opacity-50', !bolehBayar);
    submit.classList.toggle('cursor-not-allowed', !bolehBayar);
  };

  input.addEventListener('input', refreshKembalian);
}

// ---------------------------------------------------------------- init
function initPembayaran() {
  var card = document.querySelector('[data-purpose="transaction-summary-card"]');
  if (card && card.dataset.subtotal) baseMaterial = parseFloat(card.dataset.subtotal) || baseMaterial;

  initUangPelanggan();

  var cb = $('proteksi-check');
  if (cb) cb.addEventListener('change', recalculateDpValues);

  var catatan = $('catatan-sopir');
  if (catatan) {
    catatan.addEventListener('input', function () {
      if ($('catatan-sopir-count')) $('catatan-sopir-count').textContent = catatan.value.length + '/200';
    });
  }
  var toggle = $('btn-toggle-catatan-sopir');
  if (toggle && catatan) toggle.addEventListener('click', function () { catatan.classList.toggle('hidden'); catatan.focus(); });

  $('btn-submit-kasir').addEventListener('click', submitKasir);

  selectPaymentMethod('dp');   // render awal dari state, bukan angka hardcode di HTML
}

// dipanggil dari atribut onclick di Blade
Object.assign(window, { applyArmadaSelection, closeArmadaModal, openArmadaModal, selectArmadaOption, selectPaymentMethod });

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initPembayaran);
} else {
  initPembayaran();
}