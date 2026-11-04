// Logika halaman Pembayaran (diambil apa adanya dari desain)
var selectedArmadaKey = 'self';
var currentConfirmedArmada = 'self';
var armadaConfig = {
  'truk': {
    title: 'Armada Toko - Truk Engkel (Rp 110.000)',
    summaryTitle: 'Biaya Armada Toko (Truk Engkel)',
    cost: 110000,
    costLabel: 'Rp 110.000',
    badgeClass: 'bg-sky-50 text-sky-800 border-sky-200',
    estimasi: 'Estimasi kirim hari ini: <strong>Pukul 14.00 - 16.00 WIB</strong>',
    beban: 'Total Beban: ~3.2 Ton'
  },
  'pickup': {
    title: 'Pick-up L300 Cepat (Rp 65.000)',
    summaryTitle: 'Biaya Armada Pick-up L300',
    cost: 65000,
    costLabel: 'Rp 65.000',
    badgeClass: 'bg-blue-50 text-blue-800 border-blue-200',
    estimasi: 'Estimasi kirim express: <strong>Pukul 12.00 - 13.30 WIB</strong>',
    beban: 'Total Beban: ~1.2 Ton (Kargo Cepat)'
  },
  'self': {
    title: 'Ambil Sendiri di Toko / Gudang (Gratis)',
    summaryTitle: 'Biaya Pengiriman (Ambil Sendiri)',
    cost: 0,
    costLabel: 'Rp 0 (Gratis)',
    badgeClass: 'bg-emerald-50 text-emerald-800 border-emerald-200',
    estimasi: 'Pengambilan mandiri: <strong>Siap diambil di Gudang Barat</strong>',
    beban: 'Armada Pembeli Sendiri'
  }
};

function openArmadaModal() {
  selectedArmadaKey = currentConfirmedArmada;
  updateModalRadioHighlight(selectedArmadaKey);
  var modal = document.getElementById('armada-modal');
  if (modal) modal.classList.remove('hidden');
}

function closeArmadaModal() {
  var modal = document.getElementById('armada-modal');
  if (modal) modal.classList.add('hidden');
}

function selectArmadaOption(key) {
  selectedArmadaKey = key;
  updateModalRadioHighlight(key);
}

function updateModalRadioHighlight(activeKey) {
  ['truk', 'pickup', 'self'].forEach(function(k) {
    var card = document.getElementById('armada-card-' + k);
    var radio = document.getElementById('radio-' + k);
    if (k === activeKey) {
      if (radio) radio.checked = true;
      if (card) {
        card.className = 'block p-4 rounded-xl border-2 border-slate-900 bg-slate-50/80 cursor-pointer transition-all shadow-xs';
      }
    } else {
      if (radio) radio.checked = false;
      if (card) {
        card.className = 'block p-4 rounded-xl border border-slate-200 hover:border-slate-400 bg-white cursor-pointer transition-all';
      }
    }
  });
}

function formatRupiah(num) {
  return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function applyArmadaSelection() {
  currentConfirmedArmada = selectedArmadaKey;
  var cfg = armadaConfig[currentConfirmedArmada];

  var badge = document.getElementById('armada-badge-label');
  if (badge) {
    badge.textContent = cfg.title;
    badge.className = 'text-xs font-semibold px-2 py-0.5 rounded border ' + cfg.badgeClass;
  }

  var estimasi = document.getElementById('armada-estimasi-info');
  if (estimasi) {
    estimasi.innerHTML = cfg.estimasi;
  }

  var beban = document.getElementById('armada-beban-info');
  if (beban) {
    beban.textContent = cfg.beban;
  }

  var summaryTitle = document.getElementById('summary-armada-title');
  if (summaryTitle) {
    summaryTitle.textContent = cfg.summaryTitle;
  }

  var summaryCost = document.getElementById('summary-armada-cost');
  if (summaryCost) {
    summaryCost.textContent = cfg.costLabel;
  }

  var baseMaterial = 2972000;
  var proteksi = 5000;
  var newTotal = baseMaterial + cfg.cost + proteksi;
  var totalTagihan = document.getElementById('summary-total-tagihan');
  if (totalTagihan) {
    totalTagihan.textContent = formatRupiah(newTotal);
  }

  closeArmadaModal();
}

var currentPaymentType = 'dp';
var currentGrandTotal = 2977000;
var dpAmount = 1000000;

function getCleanNumeric(val) {
  var cleaned = String(val).replace(/[^0-9]/g, '');
  return cleaned ? parseInt(cleaned, 10) : 0;
}

function formatRupiahNum(num) {
  return 'Rp ' + Math.max(0, num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function recalculateDpValues() {
  var sisa = Math.max(0, currentGrandTotal - dpAmount);
  var dpInput = document.getElementById('nominal-dp-input');
  if (dpInput && document.activeElement !== dpInput) {
    dpInput.value = dpAmount ? dpAmount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '';
  }
  var displayAmount = document.getElementById('dp-display-amount');
  if (displayAmount) displayAmount.textContent = formatRupiahNum(dpAmount);

  var displaySisa = document.getElementById('dp-sisa-pelunasan');
  if (displaySisa) displaySisa.textContent = formatRupiahNum(sisa);

  var summaryDpVal = document.getElementById('summary-dp-val');
  if (summaryDpVal) summaryDpVal.textContent = '- ' + formatRupiahNum(dpAmount);

  var summarySisaVal = document.getElementById('summary-sisa-val');
  if (summarySisaVal) summarySisaVal.textContent = formatRupiahNum(sisa);

  var summaryTotal = document.getElementById('summary-total-tagihan');
  var summaryLabelTotal = document.getElementById('summary-label-total');
  var summaryBadgeStatus = document.getElementById('summary-badge-status');
  var btnSubmitText = document.getElementById('btn-submit-kasir-text');

  if (currentPaymentType === 'dp') {
    if (summaryTotal) summaryTotal.textContent = formatRupiahNum(dpAmount);
    if (summaryLabelTotal) summaryLabelTotal.textContent = 'Tagihan DP Dibayar Sekarang';
    if (summaryBadgeStatus) summaryBadgeStatus.textContent = 'Uang Muka (DP)';
    if (btnSubmitText) btnSubmitText.textContent = 'Bayar DP & Cetak Nota DO';
  } else {
    if (summaryTotal) summaryTotal.textContent = formatRupiahNum(currentGrandTotal);
    if (summaryLabelTotal) summaryLabelTotal.textContent = 'Total Tagihan Final';
    if (summaryBadgeStatus) summaryBadgeStatus.textContent = 'Termasuk PPN';
    if (btnSubmitText) btnSubmitText.textContent = 'Selesaikan & Cetak Nota Kasir';
  }
}

function onDpInput(rawVal) {
  var num = getCleanNumeric(rawVal);
  dpAmount = num;
  var dpInput = document.getElementById('nominal-dp-input');
  if (dpInput) {
    dpInput.value = num ? num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '';
  }
  recalculateDpValues();
}

function setDpPercent(percent) {
  dpAmount = Math.round(currentGrandTotal * percent);
  var dpInput = document.getElementById('nominal-dp-input');
  if (dpInput) {
    dpInput.value = dpAmount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  }
  recalculateDpValues();
}

function selectPaymentMethod(type) {
  currentPaymentType = type;
  var lunasCard = document.getElementById('payment-opt-lunas');
  var dpCard = document.getElementById('payment-opt-dp');
  var lunasRadio = document.getElementById('radio-lunas');
  var dpRadio = document.getElementById('radio-dp');
  var dpPanel = document.getElementById('dp-input-panel');
  var summaryDpRow = document.getElementById('summary-dp-row');
  var summarySisaRow = document.getElementById('summary-sisa-row');

  if (type === 'lunas') {
    if (lunasRadio) lunasRadio.checked = true;
    if (dpRadio) dpRadio.checked = false;
    if (lunasCard) {
      lunasCard.className = 'payment-option flex items-center justify-between p-3.5 rounded-xl border-2 border-slate-900 bg-slate-50/80 cursor-pointer transition-all shadow-xs';
    }
    if (dpCard) {
      dpCard.className = 'payment-option flex items-center justify-between p-3.5 rounded-xl border border-slate-200 hover:border-slate-300 bg-white cursor-pointer transition-all';
    }
    if (dpPanel) dpPanel.classList.add('hidden');
    if (summaryDpRow) summaryDpRow.classList.add('hidden');
    if (summarySisaRow) summarySisaRow.classList.add('hidden');
  } else if (type === 'dp') {
    if (dpRadio) dpRadio.checked = true;
    if (lunasRadio) lunasRadio.checked = false;
    if (dpCard) {
      dpCard.className = 'payment-option flex items-center justify-between p-3.5 rounded-xl border-2 border-slate-900 bg-slate-50/80 cursor-pointer transition-all shadow-xs';
    }
    if (lunasCard) {
      lunasCard.className = 'payment-option flex items-center justify-between p-3.5 rounded-xl border border-slate-200 hover:border-slate-300 bg-white cursor-pointer transition-all';
    }
    if (dpPanel) dpPanel.classList.remove('hidden');
    if (summaryDpRow) summaryDpRow.classList.remove('hidden');
    if (summarySisaRow) summarySisaRow.classList.remove('hidden');
  }
  recalculateDpValues();
}

// Fungsi yang dipanggil dari atribut onclick di Blade harus dibuat global
Object.assign(window, { applyArmadaSelection, closeArmadaModal, openArmadaModal, selectArmadaOption, selectPaymentMethod });

// Sync total with armada change if user updates shipping
var originalApplyArmada = window.applyArmadaSelection;
if (typeof originalApplyArmada === 'function') {
  window.applyArmadaSelection = function() {
    originalApplyArmada();
    var cfg = armadaConfig[currentConfirmedArmada];
    var baseMaterial = 2972000;
    var proteksi = 5000;
    currentGrandTotal = baseMaterial + cfg.cost + proteksi;
    recalculateDpValues();
  };
}