<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>Checkout Kasir &amp; Pengiriman Proyek - Aura Material</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/app.css', 'resources/js/pembayaran.js'])

  <style>
    body { background-color: #f8fafc; color: #1e293b; -webkit-font-smoothing: antialiased; }
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
  </style>
</head>
<body class="min-h-screen flex flex-col font-sans">
<!-- BEGIN: TopHeader -->
@include('partials.kasir-navbar')
<!-- END: TopHeader -->
<!-- BEGIN: MainContent -->
<main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
<!-- Title Page -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
<div>
<a href="#kasir-pos" onclick="history.back(); return false;" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-white border border-slate-300 hover:border-slate-400 text-sm font-semibold text-slate-800 hover:text-slate-900 hover:bg-slate-50 shadow-xs transition-all mb-3 group"><svg class="w-5 h-5 text-slate-600 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg><span class="">Kembali ke Kasir / Pesanan</span></a><h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Checkout Pembayaran Kasir</h1>
<p class="text-sm text-slate-500 mt-1">Selesaikan pemesanan, verifikasi armada logistik, dan cetak nota kasir resmi.</p>
</div>
<div class="text-xs font-semibold text-slate-500 bg-white border border-slate-200 px-3 py-1.5 rounded-lg shadow-2xs self-start sm:self-auto">
        No. Dokumen: <span class="text-slate-800 font-mono font-bold">INV-20231104-098</span>
</div>
</div>
<!-- Main Grid Layout -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
<!-- BEGIN: LeftColumn (Alamat & Pesanan Material) -->
<section class="lg:col-span-8 space-y-6" data-purpose="orders-section">
<!-- CARD 1: Alamat Pengiriman Proyek -->
<article class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs hover:border-slate-300 transition-all" data-purpose="shipping-address-card"><div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
  <div class="flex items-center gap-2">
    <div class="w-6 h-6 rounded-md bg-orange-50 text-[#ea580c] flex items-center justify-center border border-orange-100 shrink-0">
      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
        <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
      </svg>
    </div>
    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-700">Data Pembeli &amp; Alamat Pengiriman</h2>
  </div>
  <span class="text-[11px] font-semibold px-2 py-0.5 rounded bg-amber-50 text-amber-800 border border-amber-200/60">Wajib Diisi untuk Pengiriman</span>
</div>
<div class="space-y-3.5">
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
    <div>
      <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Pembeli / Mandor</label>
      <input class="w-full text-xs font-medium text-slate-800 bg-slate-50/80 border border-slate-200 rounded-lg px-3 py-2 focus:bg-white focus:ring-slate-900 focus:border-slate-900 transition-all" placeholder="Contoh: Pak Joko (Proyek Citra Mandiri)" type="text" value="Pak Joko • Proyek Citra Mandiri">
    </div>
    <div>
      <label class="block text-xs font-bold text-slate-700 mb-1.5">No. WhatsApp / HP (Aktif)</label>
      <input class="w-full text-xs font-medium text-slate-800 bg-slate-50/80 border border-slate-200 rounded-lg px-3 py-2 focus:bg-white focus:ring-slate-900 focus:border-slate-900 transition-all" placeholder="08xxxxxxxxxx" type="text" value="0812-9876-5432">
    </div>
  </div>
  <div>
    <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Pengiriman / Lokasi Proyek</label>
    <textarea class="w-full text-xs font-medium text-slate-800 bg-slate-50/80 border border-slate-200 rounded-lg px-3 py-2 focus:bg-white focus:ring-slate-900 focus:border-slate-900 transition-all leading-relaxed resize-none" placeholder="Masukkan alamat lengkap atau patokan lokasi..." rows="2">Jl. Menur No 8 RT 32 RW 09 Baciro, Gondokusuman, Kota Yogyakarta, D.I. Yogyakarta 55225</textarea>
  </div>
  <div>
    <label class="block text-xs font-bold text-slate-700 mb-1.5">Catatan Khusus Lokasi (Opsional)</label>
    <div class="relative flex items-center">
      <div class="absolute left-3 text-amber-600 flex items-center pointer-events-none">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
        </svg>
      </div>
      <input class="w-full text-xs font-medium text-slate-800 bg-slate-50/80 border border-slate-200 rounded-lg pl-8 pr-3 py-2 focus:bg-white focus:ring-slate-900 focus:border-slate-900 transition-all" placeholder="Patokan gang, portal jam masuk, dll." type="text" value="Masuk gang depan Gudang Barat, truk muatan bisa putar balik.">
    </div>
  </div>
</div></article>
<!-- CARD 2: Pesanan 1 (Material Curah & Besi - Muatan Truk Engkel) -->
<article class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-5" data-purpose="unified-pos-materials">
  <!-- Header: Unified List -->
  <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
    <div class="flex items-center gap-2.5">
      <div class="w-6 h-6 rounded-md bg-orange-50 text-[#ea580c] flex items-center justify-center border border-orange-100 shrink-0">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
        </svg>
      </div>
      <h2 class="text-xs font-bold uppercase tracking-wider text-slate-800">Daftar Material Terpilih (Kasir POS)</h2>
    </div>
    <span class="text-[11px] font-semibold px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200">5 Jenis Material</span>
  </div>

  <!-- Daftar Item Material Terpadu -->
  <div class="space-y-3.5 divide-y divide-slate-100 max-h-[290px] overflow-y-auto pr-1.5 [overscroll-behavior:contain] [scrollbar-width:thin]">
    <!-- Item 1: Pasir Bangka Super -->
    <div class="flex items-start gap-4 pt-1">
      <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 shrink-0 flex items-center justify-center text-amber-700 shadow-2xs">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
        </svg>
      </div>
      <div class="flex-1 flex justify-between gap-4">
        <div>
          <h3 class="text-sm font-bold text-slate-800 leading-snug">Pasir Bangka Super Ayak Kasar</h3>
          <p class="text-xs text-slate-500 mt-0.5">Kualitas grade A • Volume dump ± 4.2 m³</p>
        </div>
        <div class="text-right">
          <span class="text-xs font-semibold text-slate-500">1 Rit × Rp 1.450.000</span>
          <div class="text-sm font-bold text-slate-900">Rp 1.450.000</div>
        </div>
      </div>
    </div>

    <!-- Item 2: Besi Beton SNI -->
    <div class="flex items-start gap-4 pt-3.5">
      <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 shrink-0 flex items-center justify-center text-slate-600 shadow-2xs">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path d="M4 6h16M4 10h16M4 14h16M4 18h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
        </svg>
      </div>
      <div class="flex-1 flex justify-between gap-4">
        <div>
          <h3 class="text-sm font-bold text-slate-800 leading-snug">Besi Beton Polos 10mm SNI (12m)</h3>
          <p class="text-xs text-slate-500 mt-0.5">Toleransi 0.2mm • Sertifikasi SNI</p>
        </div>
        <div class="text-right">
          <span class="text-xs font-semibold text-slate-500">10 Btg × Rp 78.000</span>
          <div class="text-sm font-bold text-slate-900">Rp 780.000</div>
        </div>
      </div>
    </div>

    <!-- Item 3: Cat Dulux Catylac -->
    <div class="flex items-start gap-4 pt-3.5">
      <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 shrink-0 flex items-center justify-center text-indigo-700 shadow-2xs">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
        </svg>
      </div>
      <div class="flex-1 flex justify-between gap-4">
        <div>
          <h3 class="text-sm font-bold text-slate-800 leading-snug">Cat Interior Dulux Catylac 5kg</h3>
          <p class="text-xs text-slate-500 mt-0.5">Warna: 1501 Brilliant White • Cepat kering</p>
        </div>
        <div class="text-right">
          <span class="text-xs font-semibold text-slate-500">2 Pail × Rp 130.000</span>
          <div class="text-sm font-bold text-slate-900">Rp 260.000</div>
        </div>
      </div>
    </div>

    <!-- Item 4: Semen Portland 50kg -->
    <div class="flex items-start gap-4 pt-3.5">
      <div class="w-12 h-12 rounded-xl bg-orange-50 border border-orange-100 shrink-0 flex items-center justify-center text-orange-700 shadow-2xs">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
        </svg>
      </div>
      <div class="flex-1 flex justify-between gap-4">
        <div>
          <h3 class="text-sm font-bold text-slate-800 leading-snug">Semen Tiga Roda 50kg (PCC)</h3>
          <p class="text-xs text-slate-500 mt-0.5">Semen plester &amp; acian presisi standar SNI</p>
        </div>
        <div class="text-right">
          <span class="text-xs font-semibold text-slate-500">5 Zak × Rp 68.000</span>
          <div class="text-sm font-bold text-slate-900">Rp 340.000</div>
        </div>
      </div>
    </div>

    <!-- Item 5: Pipa PVC Wavin AW -->
    <div class="flex items-start gap-4 pt-3.5">
      <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 shrink-0 flex items-center justify-center text-blue-700 shadow-2xs">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
        </svg>
      </div>
      <div class="flex-1 flex justify-between gap-4">
        <div>
          <h3 class="text-sm font-bold text-slate-800 leading-snug">Pipa PVC Wavin / Rucika AW 3/4" (4m)</h3>
          <p class="text-xs text-slate-500 mt-0.5">Tekanan 10 Bar • Air bersih &amp; instalasi pompa</p>
        </div>
        <div class="text-right">
          <span class="text-xs font-semibold text-slate-500">4 Btg × Rp 35.500</span>
          <div class="text-sm font-bold text-slate-900">Rp 142.000</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Proteksi Muatan Curah -->
  <label class="flex items-center gap-2.5 text-xs text-slate-700 bg-slate-50/80 p-3 rounded-xl border border-slate-200/60 cursor-pointer hover:bg-slate-100/70 transition-all select-none shrink-0">
    <input checked="" class="w-4 h-4 text-[#ea580c] rounded border-slate-300 focus:ring-orange-500 accent-[#ea580c]" type="checkbox">
    <div class="flex items-center gap-1.5 font-medium">
      <svg class="w-4 h-4 text-[#ea580c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
      </svg>
      <span class="">Proteksi Terpal &amp; Asuransi Tumpahan Muatan Curah</span>
      <span class="text-slate-500">(Rp 5.000)</span>
    </div>
  </label>

  <!-- Opsi Armada Gabungan / Logistik Toko -->
  <div class="border border-slate-200 rounded-xl p-3.5 bg-white hover:border-slate-400 transition-colors shrink-0">
    <div class="flex items-center justify-between mb-2">
      <div class="flex items-center gap-2 flex-wrap">
        <span class="text-xs font-bold text-slate-900">Armada Logistik Pengiriman:</span>
        <span id="armada-badge-label" class="text-xs font-semibold px-2 py-0.5 rounded border bg-emerald-50 text-emerald-800 border-emerald-200">Ambil Sendiri di Toko / Gudang (Gratis)</span>
      </div>
      <button type="button" onclick="openArmadaModal()" class="text-xs font-bold text-[#0284c7] hover:text-sky-800 cursor-pointer hover:underline bg-transparent border-0 p-0">Ganti</button>
    </div>
    <div class="flex items-center justify-between text-xs text-slate-500">
      <div class="flex items-center gap-1.5">
        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
        </svg>
        <span id="armada-estimasi-info" class="">Pengambilan mandiri: <strong>Siap diambil di Gudang Barat</strong></span>
      </div>
      <span id="armada-beban-info" class="text-slate-400">Armada Pembeli Sendiri</span>
    </div>
  </div>

  <!-- Kasih Catatan Driver -->
  <div class="flex items-center justify-between text-xs text-slate-500 border-t border-dashed border-slate-200 pt-3 shrink-0">
    <button class="flex items-center gap-1.5 hover:text-emerald-700 font-medium transition-colors" type="button">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
      </svg>
      <span class="">Kasih Catatan untuk Sopir / Buruh Bongkar Muatan</span>
    </button>
    <span class="text-slate-400">0/200</span>
  </div>
</article>
<!-- CARD 3: Pesanan 2 (Material Finishing & Cat - Pick-up L300) -->

</section>
<!-- END: LeftColumn -->
<!-- BEGIN: RightColumn (Sidebar Pembayaran & Ringkasan) -->
<aside class="lg:col-span-4 space-y-5" data-purpose="payment-and-summary-sidebar">
<!-- CARD: Metode Pembayaran Kasir -->
<article class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs" data-purpose="payment-methods-card"><div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
  <h2 class="text-sm font-bold text-slate-900">Metode Pembayaran</h2>
  <button class="text-xs font-bold text-[#0284c7] hover:text-sky-800 transition-colors" type="button">Lihat Semua</button>
</div>
<!-- Radio List Metode Pembayaran -->
<div class="space-y-3">
  <!-- Pilihan 1: Lunas -->
  <label id="payment-opt-lunas" onclick="selectPaymentMethod('lunas')" class="payment-option flex items-center justify-between p-3.5 rounded-xl border border-slate-200 hover:border-slate-300 bg-white cursor-pointer transition-all">
    <div class="flex items-center gap-3">
      <div>
        <div class="flex items-center gap-2">
          <span class="text-xs font-bold text-slate-800">Lunas (Bayar Penuh / Cash / Transfer Langsung)</span>
        </div>
        <div class="text-[11px] text-slate-500 mt-0.5">Pembayaran penuh di muka • Nota lunas langsung tercetak</div>
      </div>
    </div>
    <input id="radio-lunas" class="w-4 h-4 text-slate-900 focus:ring-slate-900 border-slate-300 accent-slate-900 cursor-pointer" name="payment_method" type="radio" value="lunas">
  </label>

  <!-- Pilihan 2: DP (Down Payment) -->
  <label id="payment-opt-dp" onclick="selectPaymentMethod('dp')" class="payment-option flex items-center justify-between p-3.5 rounded-xl border-2 border-slate-900 bg-slate-50/80 cursor-pointer transition-all shadow-xs">
    <div class="flex items-center gap-3">
      <div>
        <div class="flex items-center gap-2">
          <span class="text-xs font-bold text-slate-800">DP (Down Payment)</span>
          <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-amber-100 text-amber-800">Uang Muka</span>
        </div>
        <div class="text-[11px] text-slate-500 mt-0.5">Uang muka proyek • Pelunasan saat barang tiba di lokasi</div>
      </div>
    </div>
    <input id="radio-dp" class="w-4 h-4 text-slate-900 focus:ring-slate-900 border-slate-300 accent-slate-900 cursor-pointer" name="payment_method" type="radio" value="dp" checked="checked">
  </label>

  <!-- DP Details Sub-panel (Appears when DP is selected) -->
  <div id="dp-input-panel" class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-3 transition-all"><div class="flex items-center justify-between pb-2 border-b border-slate-200/80"><div class="flex items-center gap-1.5"><span class="text-xs font-bold text-slate-800">DP Pembayaran Langsung</span></div><span class="text-[11px] font-medium text-slate-500">Ketentuan Tetap 30%</span></div></div>
</div></article>
<!-- CARD: Ringkasan Transaksi & Total Akhir -->
<article class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-4" data-purpose="transaction-summary-card"><h2 class="text-sm font-bold text-slate-900 pb-2 border-b border-slate-100">
  Cek Ringkasan Transaksi Kasir
</h2>
<div class="space-y-2.5 text-xs">
  <div class="flex justify-between text-slate-600">
    <span class="">Total Harga Material (7 Item)</span>
    <span class="font-medium text-slate-800">Rp 2.972.000</span>
  </div>
  <div class="flex justify-between text-slate-600">
    <span id="summary-armada-title" class="">Biaya Pengiriman (Ambil Sendiri)</span>
    <span id="summary-armada-cost" class="font-medium text-slate-800">Rp 0 (Gratis)</span>
  </div>
  <div class="flex justify-between text-slate-600">
    <span class="">Proteksi &amp; Terpal Muatan Curah</span>
    <span class="font-medium text-slate-800">Rp 5.000</span>
  </div>
  <div id="summary-dp-row" class="flex justify-between text-emerald-700 font-semibold bg-emerald-50 px-2 py-1 rounded">
    <span class="">Uang Muka (DP Kasir)</span>
    <span id="summary-dp-val" class="font-bold">- Rp 1.000.000</span>
  </div>
  <div id="summary-sisa-row" class="flex justify-between text-amber-800 font-bold bg-amber-50 px-2 py-1 rounded border border-amber-200/60">
    <span class="">Sisa Pelunasan di Lokasi</span>
    <span id="summary-sisa-val" class="font-mono">Rp 1.977.000</span>
  </div>
</div>
<!-- Total Tagihan -->
<div class="pt-3 border-t border-slate-100 flex items-baseline justify-between">
  <div>
    <span id="summary-label-total" class="text-xs font-medium text-slate-500 block">Tagihan DP Dibayar Sekarang</span>
    <span id="summary-total-tagihan" class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Rp 1.000.000</span>
  </div>
  <span id="summary-badge-status" class="text-[10px] font-bold uppercase tracking-wider text-slate-800 bg-slate-100 px-2 py-0.5 rounded border border-slate-300">Uang Muka (DP)</span>
</div>

<!-- Input Uang Pelanggan -->
<div id="cash-panel" class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
  <div class="flex items-center justify-between pb-2 border-b border-slate-200/80">
    <span class="text-xs font-bold text-slate-800">Uang Diterima dari Pelanggan</span>
    <span class="text-[11px] font-medium text-slate-500">Tunai</span>
  </div>

  <div class="relative">
    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-500">Rp</span>
    <input id="uang-diterima" name="uang_diterima" type="text" inputmode="numeric" autocomplete="off"
      placeholder="0" data-raw="0"
      class="w-full text-right font-mono text-base font-bold text-slate-900 bg-white border border-slate-300 rounded-lg pl-9 pr-3 py-2.5 focus:ring-slate-900 focus:border-slate-900 transition-all">
  </div>

  <div id="kembalian-row" class="flex items-center justify-between px-3 py-2 rounded-lg bg-white border border-slate-200 text-slate-600">
    <span id="kembalian-label" class="text-xs font-semibold">Kembalian</span>
    <span id="kembalian-val" class="text-sm font-black font-mono">Rp 0</span>
  </div>
</div>

<!-- Tombol Aksi Kasir -->
<button id="btn-submit-kasir" class="w-full bg-[#ea580c] hover:bg-[#c2410c] text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-orange-500/25 transition-all flex items-center justify-center gap-2 group active:scale-[0.99]" type="button">
  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
  </svg>
  <span id="btn-submit-kasir-text" class="text-sm tracking-wide">Bayar DP &amp; Cetak Nota DO</span>
</button>
<button onclick="history.back()" type="button" class="w-full bg-white hover:bg-rose-50 text-slate-700 hover:text-rose-600 font-bold py-2.5 px-4 rounded-xl border border-slate-200 hover:border-slate-300 transition-all flex items-center justify-center gap-2 text-xs active:scale-[0.99]">
  <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
  <span class="">Batalkan Transaksi</span>
</button>
<!-- Disclaimer / Legal Info -->
<p class="text-[11px] text-center text-slate-400 leading-normal">
  Dengan menyelesaikan transaksi, nota resmi otomatis tercatat pada pembukuan kasir &amp; inventaris gudang Aura Material.
</p></article>
</aside>
<!-- END: RightColumn -->
</div>
</main>
<!-- END: MainContent -->
<!-- BEGIN: Footer -->
<footer class="mt-auto border-t border-slate-200 bg-white py-4">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-2">
<div class="">
        © 2024 <strong>Aura Material</strong> — Platform Manajemen Kasir &amp; Logistik Bahan Bangunan.
      </div>
<div class="flex items-center gap-4">



</div>
</div>
</footer>
<!-- END: Footer -->






<div id="armada-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs transition-opacity hidden" role="dialog" aria-modal="true">
  <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full overflow-hidden transition-all transform">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/80">
      <div class="flex items-center gap-2.5">
        <div class="w-8 h-8 rounded-lg bg-orange-50 text-[#ea580c] border border-orange-100 flex items-center justify-center shrink-0">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
          </svg>
        </div>
        <div>
          <h3 class="text-sm font-bold text-slate-900">Pilih Armada Logistik Pengiriman</h3>
          <p class="text-[11px] text-slate-500">Sesuaikan armada dengan volume material &amp; lokasi proyek</p>
        </div>
      </div>
      <button type="button" onclick="closeArmadaModal()" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 flex items-center justify-center transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>
    <div class="p-6 space-y-3.5 max-h-[60vh] overflow-y-auto">
      <label id="armada-card-truk" onclick="selectArmadaOption('truk')" class="block p-4 rounded-xl border border-slate-200 hover:border-slate-400 bg-white cursor-pointer transition-all">
        <div class="flex items-start justify-between gap-3">
          <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-700 border border-amber-200/60 flex items-center justify-center shrink-0 mt-0.5">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 17a2 2 0 11-4 0m4 0a2 2 0 10-4 0m-8 0a2 2 0 11-4 0m4 0a2 2 0 10-4 0m14-8V6a2 2 0 00-2-2H4a2 2 0 00-2 2v9a2 2 0 002 2h1m11-9h4l3 4v5h-2m-5-9v9"></path>
              </svg>
            </div>
            <div>
              <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-900">Truk Engkel Toko</span>
                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-amber-100 text-amber-800">Rekomendasi</span>
              </div>
              <p class="text-xs text-slate-600 mt-1">Kapasitas s/d 4 Ton / 100 Zak Semen • Estimasi tiba hari ini</p>
              <div class="flex items-center gap-3 mt-2 text-[11px] text-slate-500 font-medium">
                <span class="">Pukul 14.00 - 16.00 WIB</span>
                <span class="">•</span>
                <span class="">Muatan Curah &amp; Besi</span>
              </div>
            </div>
          </div>
          <div class="text-right shrink-0">
            <div class="text-xs font-bold text-slate-900">Rp 110.000</div>
            <input type="radio" name="armada_radio" id="radio-truk" checked="" class="mt-1.5 w-4 h-4 text-[#ea580c] border-slate-300 focus:ring-orange-500 accent-[#ea580c]">
          </div>
        </div>
      </label>
      <label id="armada-card-pickup" onclick="selectArmadaOption('pickup')" class="block p-4 rounded-xl border border-slate-200 hover:border-slate-400 bg-white cursor-pointer transition-all">
        <div class="flex items-start justify-between gap-3">
          <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-700 border border-blue-100 flex items-center justify-center shrink-0 mt-0.5">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a2 2 0 014 0m0 0a2 2 0 004 0m-4 0V8l4 4v4h-4m-12 0a2 2 0 104 0m-4 0a2 2 0 014 0"></path>
              </svg>
            </div>
            <div>
              <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-900">Pick-up L300</span>
                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-blue-100 text-blue-800">Cepat</span>
              </div>
              <p class="text-xs text-slate-600 mt-1">Muatan ringan &amp; cepat, kapasitas s/d 1.5 Ton • Estimasi 1-2 jam</p>
              <div class="flex items-center gap-3 mt-2 text-[11px] text-slate-500 font-medium">
                <span class="">Pukul 12.00 - 13.30 WIB</span>
                <span class="">•</span>
                <span class="">Cat, Sanitasi &amp; Pipa</span>
              </div>
            </div>
          </div>
          <div class="text-right shrink-0">
            <div class="text-xs font-bold text-slate-900">Rp 65.000</div>
            <input type="radio" name="armada_radio" id="radio-pickup" class="mt-1.5 w-4 h-4 text-[#ea580c] border-slate-300 focus:ring-orange-500 accent-[#ea580c]">
          </div>
        </div>
      </label>
      <label id="armada-card-self" onclick="selectArmadaOption('self')" class="block p-4 rounded-xl border-2 border-slate-900 bg-slate-50/80 cursor-pointer transition-all shadow-xs">
        <div class="flex items-start justify-between gap-3">
          <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
              </svg>
            </div>
            <div>
              <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-900">Ambil Sendiri di Toko / Gudang</span>
                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800">Bebas Ongkir</span>
              </div>
              <p class="text-xs text-slate-600 mt-1">Pembeli bawa kendaraan sendiri / ambil di gudang barat</p>
              <div class="flex items-center gap-3 mt-2 text-[11px] text-slate-500 font-medium">
                <span class="">Siap Diambil Kapan Saja</span>
                <span class="">•</span>
                <span class="">Lokasi Gudang Barat</span>
              </div>
            </div>
          </div>
          <div class="text-right shrink-0">
            <div class="text-xs font-bold text-emerald-600">Rp 0 (Gratis)</div>
            <input type="radio" name="armada_radio" id="radio-self" class="mt-1.5 w-4 h-4 text-[#ea580c] border-slate-300 focus:ring-orange-500 accent-[#ea580c]">
          </div>
        </div>
      </label>
    </div>
    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-3">
      <button type="button" onclick="closeArmadaModal()" class="text-xs font-bold text-slate-600 hover:text-slate-900 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 transition-all">Batal</button>
      <button type="button" onclick="applyArmadaSelection()" class="text-xs font-bold text-white px-5 py-2.5 rounded-xl bg-[#ea580c] hover:bg-[#c2410c] shadow-md shadow-orange-500/20 transition-all flex items-center gap-1.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span class="">Terapkan Pilihan Armada</span>
      </button>
    </div>
  </div>
</div>

</body>
</html>