<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>Riwayat Nota - Aura Material</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link 
    href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" 
    rel="stylesheet"
  >
  <link 
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" 
    rel="stylesheet"
  >

  @vite(['resources/css/app.css', 'resources/js/riwayat.js'])

  <style>
    body { 
      overscroll-behavior: y none; 
    }
    main > :first-child { 
      margin-top: 0 !important; 
    }
    main > :last-child { 
      margin-bottom: 0 !important; 
    }
    
    /* Custom Scrollbar Tipis untuk kenyamanan scroll vertical */
    .custom-scrollbar::-webkit-scrollbar {
      width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
      background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 9999px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }
  </style>
</head>

<body class="bg-surface font-body-md text-on-surface flex flex-col min-h-screen">
  
  <!-- Header Sticky (Tetap di Atas) -->
  @include('partials.kasir-navbar')

<!-- Baris judul halaman (milik halaman ini): menempel tepat di bawah navbar -->
<div class="fixed inset-x-0 z-30 top-[calc(4rem+env(safe-area-inset-top,0px))] bg-[#f8f9ff]/90 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
  <div class="h-16 px-gutter flex items-center justify-between">
      <div class="flex items-center gap-space-sm">
        <button 
          type="button" 
          aria-label="Kembali" 
          class="w-11 h-11 flex items-center justify-center text-on-surface hover:text-secondary active:scale-95 transition-all" 
          onclick="history.back()"
        >
          <span class="material-symbols-outlined text-[24px]">arrow_back</span>
        </button>
        <h1 class="font-title-lg text-title-lg text-on-surface truncate">Riwayat Nota Penjualan Hari Ini</h1>
      </div>

      <div class="flex items-center gap-space-xs">
        <button 
          type="button" 
          aria-label="Filter Kalender" 
          class="w-11 h-11 flex items-center justify-center text-on-surface-variant hover:text-on-surface active:scale-95 transition-all"
        >
          <span class="material-symbols-outlined text-[22px]">calendar_today</span>
        </button>
      </div>
    </div>
</div>

  <!-- Container Scrollable Utama (Bisa Di-scroll Mandiri ke Bawah) -->
  <main class="flex-1 w-full bg-surface pt-16 pb-32 overflow-y-auto custom-scrollbar" id="main-scroll-container">
    <div class="flex flex-col w-full pb-20">

      <!-- 1. Ringkasan Eksekutif -->
      <section class="px-gutter pt-2">
        <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-xl p-3 shadow-sm flex flex-col gap-2.5">
          <div class="flex items-center justify-between border-b border-outline-variant/20 pb-2">
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 rounded-lg bg-primary-container text-on-primary flex items-center justify-center font-bold text-[13px]">
                H
              </div>
              <div>
                <div class="flex items-center gap-1.5">
                  <span class="font-title-md text-[14px] font-bold text-on-surface">Kasir Hendra</span>
                  <span class="w-2 h-2 rounded-full bg-secondary-container"></span>
                </div>
                <span class="text-[11px] text-on-surface-variant">Shift Pagi • 27 Mei 2024</span>
              </div>
            </div>
            <div class="flex items-center gap-1 bg-surface-container px-2.5 py-1 rounded-full text-on-surface text-[11px] font-semibold">
              <span class="material-symbols-outlined text-[13px]">today</span>
              <span>Hari Ini</span>
            </div>
          </div>

          <div class="grid grid-cols-3 gap-2">
            <div class="flex flex-col">
              <span class="text-[11px] text-on-surface-variant font-medium">Total Omzet</span>
              <span class="font-tabular-numeric text-[15px] font-extrabold text-on-surface tracking-tight">Rp 8.450.000</span>
            </div>
            <div class="flex flex-col border-l border-outline-variant/20 pl-2">
              <span class="text-[11px] text-on-surface-variant font-medium">Nota Lunas</span>
              <div class="flex items-baseline gap-1">
                <span class="font-tabular-numeric text-[15px] font-bold text-emerald-600">26</span>
                <span class="text-[11px] text-on-surface-variant">trx</span>
              </div>
            </div>
            <div class="flex flex-col border-l border-outline-variant/20 pl-2">
              <span class="text-[11px] text-on-surface-variant font-medium">DP</span>
              <div class="flex items-baseline gap-1">
                <span class="font-tabular-numeric text-[15px] font-bold text-secondary">2</span>
                <span class="text-[11px] text-on-surface-variant">trx</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- 2. Pencarian & Filter Tabs -->
      <section class="px-gutter mt-2.5 flex flex-col gap-2">
        <div class="relative flex items-center">
          <span class="material-symbols-outlined absolute left-space-md text-[20px] text-on-surface-variant">search</span>
          <input 
            type="search" 
            id="nota-search-input" 
            class="w-full bg-surface-container-lowest text-on-surface font-body-md text-body-md pl-11 pr-10 py-2.5 rounded-xl border border-outline-variant/30 shadow-sm focus:outline-none focus:bg-surface-container-high transition-all placeholder:text-on-surface-variant/70" 
            placeholder="Cari nota, nama kontraktor, material (semen/besi)..."
          >
          <button 
            type="button" 
            id="clear-search-btn" 
            aria-label="Bersihkan pencarian" 
            class="absolute right-space-sm w-8 h-8 rounded-full flex items-center justify-center text-on-surface-variant hover:text-on-surface active:scale-95"
          >
            <span class="material-symbols-outlined text-[18px]">close</span>
          </button>
        </div>

        <div class="flex items-center gap-space-xs overflow-x-auto py-0.5 custom-scrollbar" id="filter-pills-container">
          <button 
            type="button" 
            data-filter="all" 
            class="filter-pill bg-surface-container text-on-surface hover:bg-surface-container-high px-space-md py-1 rounded-full font-label-md text-label-md whitespace-nowrap transition-colors flex items-center gap-1.5 cursor-pointer"
          >
            <span>Semua</span>
            <span class="text-[11px] opacity-75 font-semibold">28</span>
          </button>
          <button 
            type="button" 
            data-filter="lunas" 
            class="filter-pill bg-primary-container text-on-primary px-space-md py-1 rounded-full font-label-md text-label-md whitespace-nowrap shadow-sm flex items-center gap-1.5 cursor-pointer"
          >
            <span>Lunas</span>
            <span class="text-[11px] text-on-surface-variant font-semibold">26</span>
          </button>
          <button 
            type="button" 
            data-filter="tempo" 
            class="filter-pill bg-surface-container text-on-surface hover:bg-surface-container-high px-space-md py-1 rounded-full font-label-md text-label-md whitespace-nowrap transition-colors flex items-center gap-1.5 cursor-pointer"
          >
            <span class="w-1.5 h-1.5 rounded-full bg-secondary-container"></span>
            <span>DP</span>
            <span class="text-[11px] text-secondary font-semibold">2</span>
          </button>
        </div>
      </section>

      <!-- 3. Daftar Riwayat Transaksi -->
      <section class="px-gutter mt-2 flex flex-col gap-2.5" id="transaction-list">

        <!-- Item Nota 1 -->
        <article 
          class="bg-surface-container-lowest rounded-xl p-3 shadow-sm border border-outline-variant/30 flex flex-col gap-2 transaction-item cursor-pointer hover:shadow-md transition-shadow active:scale-[0.99]" 
          data-id="NOTA-018" 
          data-nota="NOTA-018" 
          data-type="lunas tunai"
        >
          <div class="flex items-center justify-between border-b border-outline-variant/20 pb-1.5">
            <div class="flex items-center gap-2">
              <span class="font-tabular-numeric text-tabular-numeric font-extrabold text-on-surface">NOTA-018</span>
              <span class="text-on-surface-variant text-[11px] bg-surface-container px-1.5 py-0.5 rounded font-medium">14:35 WIB</span>
            </div>
            <div class="flex items-center gap-1.5">
              <span class="text-[11px] font-bold text-on-surface px-2 py-0.5 rounded bg-surface-container">Lunas</span>
              <span class="font-tabular-numeric text-title-md font-extrabold text-on-surface">Rp 1.730.000</span>
            </div>
          </div>
          <div class="flex flex-col gap-0.5">
            <div class="flex items-center gap-1 text-[13px] font-semibold text-on-surface">
              <span class="material-symbols-outlined text-[16px] text-on-surface-variant">person</span>
              Pak Joko (Mandor Ruko Permata)
            </div>
            <p class="text-body-md text-on-surface-variant text-[12px] truncate pl-5">10 Semen Tiga Roda 50kg, 15 Besi Beton 10mm</p>
          </div>
          <div class="flex items-center justify-end gap-1.5 pt-1 border-t border-outline-variant/20">
            <button 
              type="button" 
              data-nota="NOTA-018" 
              class="btn-detail-nota h-7 px-2.5 rounded-lg bg-surface-container text-on-surface hover:bg-surface-container-high text-label-md font-semibold flex items-center gap-1 transition-colors active:scale-95"
            >
              <span class="material-symbols-outlined text-[15px]">visibility</span>
              Rincian
            </button>
            <button 
              type="button" 
              data-nota="NOTA-018" 
              class="btn-print-card h-7 px-2.5 rounded-lg bg-primary text-on-primary hover:bg-primary-container text-label-md font-semibold flex items-center gap-1 shadow-sm transition-colors active:scale-95"
            >
              <span class="material-symbols-outlined text-[15px]">print</span>
              Cetak
            </button>
          </div>
        </article>

        <!-- Item Nota 2 -->
        <article 
          class="bg-surface-container-lowest rounded-xl p-3 shadow-sm border border-outline-variant/30 flex flex-col gap-2 transaction-item cursor-pointer hover:shadow-md transition-shadow active:scale-[0.99]" 
          data-id="NOTA-017" 
          data-nota="NOTA-017" 
          data-type="lunas non-tunai"
        >
          <div class="flex items-center justify-between border-b border-outline-variant/20 pb-1.5">
            <div class="flex items-center gap-2">
              <span class="font-tabular-numeric text-tabular-numeric font-extrabold text-on-surface">NOTA-017</span>
              <span class="text-on-surface-variant text-[11px] bg-surface-container px-1.5 py-0.5 rounded font-medium">13:10 WIB</span>
            </div>
            <div class="flex items-center gap-1.5">
              <span class="text-[11px] font-bold text-on-surface px-2 py-0.5 rounded bg-surface-container">Lunas</span>
              <span class="font-tabular-numeric text-title-md font-extrabold text-on-surface">Rp 384.000</span>
            </div>
          </div>
          <div class="flex flex-col gap-0.5">
            <div class="flex items-center gap-1 text-[13px] font-semibold text-on-surface">
              <span class="material-symbols-outlined text-[16px] text-on-surface-variant">person</span>
              Bu Hani (Rumah Kebon Jeruk)
            </div>
            <p class="text-body-md text-on-surface-variant text-[12px] truncate pl-5">2 Catylac Putih 5kg, 3 Pipa PVC AW 3/4"</p>
          </div>
          <div class="flex items-center justify-end gap-1.5 pt-1 border-t border-outline-variant/20">
            <button 
              type="button" 
              data-nota="NOTA-017" 
              class="btn-detail-nota h-7 px-2.5 rounded-lg bg-surface-container text-on-surface hover:bg-surface-container-high text-label-md font-semibold flex items-center gap-1 transition-colors active:scale-95"
            >
              <span class="material-symbols-outlined text-[15px]">visibility</span>
              Rincian
            </button>
            <button 
              type="button" 
              data-nota="NOTA-017" 
              class="btn-print-card h-7 px-2.5 rounded-lg bg-primary text-on-primary hover:bg-primary-container text-label-md font-semibold flex items-center gap-1 shadow-sm transition-colors active:scale-95"
            >
              <span class="material-symbols-outlined text-[15px]">print</span>
              Cetak
            </button>
          </div>
        </article>

        <!-- Item Nota 3 (DP) -->
        <article 
          class="bg-surface-container-lowest rounded-xl p-3 shadow-sm border border-secondary/30 flex flex-col gap-2 transaction-item cursor-pointer hover:shadow-md transition-shadow active:scale-[0.99]" 
          data-id="NOTA-016" 
          data-nota="NOTA-016" 
          data-type="tempo non-tunai"
        >
          <div class="flex items-center justify-between border-b border-outline-variant/20 pb-1.5">
            <div class="flex items-center gap-2">
              <span class="font-tabular-numeric text-tabular-numeric font-extrabold text-on-surface">NOTA-016</span>
              <span class="text-on-surface-variant text-[11px] bg-surface-container px-1.5 py-0.5 rounded font-medium">11:45 WIB</span>
            </div>
            <div class="flex items-center gap-1.5 ml-auto">
              <span class="text-[11px] font-bold px-2 py-0.5 rounded bg-secondary-fixed text-secondary">DP</span>
              <span class="font-tabular-numeric text-title-md font-extrabold text-secondary">Rp 4.670.000</span>
            </div>
          </div>
          <div class="flex flex-col gap-0.5">
            <div class="flex items-center gap-1 text-[13px] font-semibold text-on-surface">
              <span class="material-symbols-outlined text-[16px] text-secondary">engineering</span>
              Mandor Asep (CV Karya Mandiri)
            </div>
            <p class="text-body-md text-on-surface-variant text-[12px] truncate pl-5">30 Semen Gresik, 40 Triplek Meranti, Ongkir Truk</p>
          </div>
          <div class="flex items-center justify-between pt-1 border-t border-outline-variant/20">
            <button 
              type="button" 
              data-nota="NOTA-016" 
              data-nominal="Rp 4.670.000" 
              class="btn-settle-nota h-7 px-2.5 rounded-lg bg-secondary text-on-secondary hover:bg-secondary-container text-label-md font-bold flex items-center gap-1 shadow-sm active:scale-95 transition-all"
            >
              <span class="material-symbols-outlined text-[15px]">check_circle</span>
              Lunaskan
            </button>
            <div class="flex items-center gap-1.5 ml-auto">
              <button 
                type="button" 
                data-nota="NOTA-016" 
                class="btn-detail-nota h-7 px-2.5 rounded-lg bg-surface-container text-on-surface hover:bg-surface-container-high text-label-md font-semibold flex items-center gap-1 transition-colors active:scale-95"
              >
                <span class="material-symbols-outlined text-[15px]">visibility</span>
                Rincian
              </button>
              <button 
                type="button" 
                data-nota="NOTA-016" 
                class="btn-print-card h-7 px-2.5 rounded-lg bg-secondary text-on-secondary hover:bg-secondary-container text-label-md font-semibold flex items-center gap-1 shadow-sm transition-colors active:scale-95"
              >
                <span class="material-symbols-outlined text-[15px]">print</span>
                Cetak
              </button>
            </div>
          </div>
        </article>

        <!-- Item Nota 4 -->
        <article 
          class="bg-surface-container-lowest rounded-xl p-3 shadow-sm border border-outline-variant/30 flex flex-col gap-2 transaction-item cursor-pointer hover:shadow-md transition-shadow active:scale-[0.99]" 
          data-id="NOTA-015" 
          data-nota="NOTA-015" 
          data-type="lunas non-tunai"
        >
          <div class="flex items-center justify-between border-b border-outline-variant/20 pb-1.5">
            <div class="flex items-center gap-2">
              <span class="font-tabular-numeric text-tabular-numeric font-extrabold text-on-surface">NOTA-015</span>
              <span class="text-on-surface-variant text-[11px] bg-surface-container px-1.5 py-0.5 rounded font-medium">10:20 WIB</span>
            </div>
            <div class="flex items-center gap-1.5">
              <span class="text-[11px] font-bold text-on-surface px-2 py-0.5 rounded bg-surface-container">Lunas</span>
              <span class="font-tabular-numeric text-title-md font-extrabold text-on-surface">Rp 2.260.000</span>
            </div>
          </div>
          <div class="flex flex-col gap-0.5">
            <div class="flex items-center gap-1 text-[13px] font-semibold text-on-surface">
              <span class="material-symbols-outlined text-[16px] text-on-surface-variant">person</span>
              Pak Slamet (Bengkel Las Kurnia)
            </div>
            <p class="text-body-md text-on-surface-variant text-[12px] truncate pl-5">50 Kg Paku Kayu 5cm, 20 Lbr Seng Gelombang</p>
          </div>
          <div class="flex items-center justify-end gap-1.5 pt-1 border-t border-outline-variant/20">
            <button 
              type="button" 
              data-nota="NOTA-015" 
              class="btn-detail-nota h-7 px-2.5 rounded-lg bg-surface-container text-on-surface hover:bg-surface-container-high text-label-md font-semibold flex items-center gap-1 transition-colors active:scale-95"
            >
              <span class="material-symbols-outlined text-[15px]">visibility</span>
              Rincian
            </button>
            <button 
              type="button" 
              data-nota="NOTA-015" 
              class="btn-print-card h-7 px-2.5 rounded-lg bg-primary text-on-primary hover:bg-primary-container text-label-md font-semibold flex items-center gap-1 shadow-sm transition-colors active:scale-95"
            >
              <span class="material-symbols-outlined text-[15px]">print</span>
              Cetak
            </button>
          </div>
        </article>

      </section>

      <!-- Toolbar Bawah Sticky -->
      <div class="fixed bottom-0 left-0 right-0 w-full z-40 bg-surface-container-lowest/95 backdrop-blur-xl border-t border-outline-variant/30 shadow-lg pb-safe pointer-events-auto">
        <div class="max-w-lg mx-auto p-3 flex items-center gap-2">
          <button 
            type="button" 
            id="btn-rekap-shift" 
            class="h-11 flex-1 bg-secondary text-on-secondary rounded-xl font-label-lg text-label-lg font-bold flex items-center justify-center gap-1.5 shadow-sm active:scale-[0.98] transition-all hover:bg-secondary-container"
          >
            <span class="material-symbols-outlined text-[18px]">receipt</span>
            Rekap Z-Report
          </button>
          <button 
            type="button" 
            id="btn-export-excel" 
            class="h-11 px-4 bg-surface-container-high text-on-surface rounded-xl font-label-lg text-label-lg font-bold flex items-center justify-center gap-1.5 active:scale-[0.98] transition-all hover:bg-surface-container"
          >
            <span class="material-symbols-outlined text-[18px]">file_download</span>
            Export
          </button>
        </div>
      </div>

    </div>
  </main>

  <!-- Modal Detail Nota -->
  <div 
    id="modal-detail-nota" 
    role="dialog" 
    aria-labelledby="modal-nota-id" 
    aria-modal="true" 
    class="fixed inset-0 z-50 flex flex-col justify-end md:justify-center items-center transition-all duration-300 ease-out opacity-0 pointer-events-none"
  >
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" id="modal-backdrop"></div>
    <div class="relative w-full max-w-lg max-h-[88vh] md:max-h-[85vh] bg-surface-container-lowest rounded-t-[1.5rem] md:rounded-2xl shadow-2xl flex flex-col overflow-hidden transform transition-all duration-300 ease-out z-10 translate-y-8" id="modal-sheet-panel">
      
      <div class="w-full flex justify-center pt-3 pb-1 md:hidden shrink-0 bg-surface-container-lowest">
        <div class="w-12 h-1 rounded-full bg-outline-variant/60"></div>
      </div>

      <div class="px-space-md md:px-gutter pt-2 md:pt-3 pb-3 border-b border-outline-variant/30 flex items-start justify-between bg-surface-container-lowest shrink-0 z-20">
        <div class="flex flex-col gap-0.5">
          <div class="flex items-center gap-space-xs">
            <span class="font-headline-md text-headline-md font-extrabold text-on-surface" id="modal-nota-id">NOTA-016</span>
            <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-secondary-fixed text-secondary" id="modal-status-badge">DP (Jatuh Tempo: 10 Jun)</span>
          </div>
          <div class="flex items-center gap-2 text-on-surface-variant text-[12px] font-medium">
            <span class="flex items-center gap-1" id="modal-nota-datetime">
              <span class="material-symbols-outlined text-[14px]">schedule</span>
              27 Mei 2024, 11:45 WIB
            </span>
            <span>•</span>
            <span class="text-on-surface font-semibold" id="modal-nota-kasir">Kasir Hendra</span>
          </div>
        </div>
        <button 
          type="button" 
          id="btn-close-modal-x" 
          aria-label="Tutup Detail" 
          class="w-8 h-8 rounded-full bg-surface-container hover:bg-surface-container-high text-on-surface-variant hover:text-on-surface flex items-center justify-center transition-colors active:scale-95"
        >
          <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
      </div>

      <div class="flex-1 overflow-y-auto custom-scrollbar px-space-md md:px-gutter py-space-md flex flex-col gap-space-md text-on-surface">
        <!-- Customer Info -->
        <div class="bg-surface-container-low rounded-xl p-space-md flex flex-col gap-space-xs border border-outline-variant/30 shrink-0">
          <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider flex items-center gap-1">
              <span class="material-symbols-outlined text-[15px]">badge</span>Data Pelanggan / Proyek
            </span>
            <span class="text-[11px] font-semibold text-secondary" id="modal-customer-type">Akun DP Proyek</span>
          </div>
          <div class="flex flex-col gap-1 pt-1">
            <p class="font-title-md text-title-md font-bold text-on-surface" id="modal-customer-name">Mandor Asep (CV Karya Mandiri)</p>
            <div class="flex items-center gap-1 text-[12px] text-on-surface-variant">
              <span class="material-symbols-outlined text-[15px]">phone</span>
              <span id="modal-customer-phone">+62 813-4455-9012 (WhatsApp)</span>
            </div>
            <div class="flex items-start gap-1 text-[12px] text-on-surface-variant">
              <span class="material-symbols-outlined text-[15px] shrink-0 mt-0.5">location_on</span>
              <span id="modal-customer-address">Cluster Anggrek Residence Blok D-05</span>
            </div>
          </div>
        </div>

        <!-- Material Items List -->
        <div class="flex flex-col gap-space-xs shrink-0">
          <div class="flex items-center justify-between pb-1 border-b border-outline-variant/30">
            <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Rincian Material Bangunan</span>
            <span class="text-[11px] text-on-surface-variant font-medium" id="modal-item-count">3 Macam Barang</span>
          </div>
          <div class="flex flex-col divide-y divide-outline-variant/20" id="modal-items-container">
            <div class="py-2.5 flex items-start justify-between gap-space-sm">
              <div class="flex flex-col">
                <span class="font-title-md text-[14px] font-bold text-on-surface">Semen Gresik 50kg</span>
                <span class="text-[12px] text-on-surface-variant">30 Sak × Rp 64.000</span>
              </div>
              <span class="font-tabular-numeric text-tabular-numeric font-bold text-on-surface whitespace-nowrap">Rp 1.920.000</span>
            </div>
            <div class="py-2.5 flex items-start justify-between gap-space-sm">
              <div class="flex flex-col">
                <span class="font-title-md text-[14px] font-bold text-on-surface">Triplek Meranti 12mm</span>
                <span class="text-[12px] text-on-surface-variant">40 Lembar × Rp 65.000</span>
              </div>
              <span class="font-tabular-numeric text-tabular-numeric font-bold text-on-surface whitespace-nowrap">Rp 2.600.000</span>
            </div>
            <div class="py-2.5 flex items-start justify-between gap-space-sm">
              <div class="flex flex-col">
                <span class="font-title-md text-[14px] font-bold text-on-surface">Ongkos Kirim Truk Colt Diesel</span>
                <span class="text-[12px] text-on-surface-variant">1 Ritase Proyek</span>
              </div>
              <span class="font-tabular-numeric text-tabular-numeric font-bold text-on-surface whitespace-nowrap">Rp 150.000</span>
            </div>
          </div>
        </div>

        <!-- Financial Summary -->
        <div class="bg-surface-container rounded-xl p-space-md flex flex-col gap-2 border border-outline-variant/30 shrink-0">
          <div class="flex items-center justify-between text-[13px] text-on-surface-variant">
            <span>Subtotal Material</span>
            <span class="font-medium text-on-surface" id="modal-subtotal">Rp 4.670.000</span>
          </div>
          <div class="flex items-center justify-between text-[13px] text-on-surface-variant">
            <span>Ongkos Kirim Armada Toko</span>
            <span class="font-medium text-on-surface" id="modal-shipping">Sudah Termasuk (Rp 150.000)</span>
          </div>
          <div class="flex items-center justify-between text-[13px] text-on-surface-variant">
            <span>Diskon Pelanggan</span>
            <span class="font-medium text-on-surface" id="modal-discount">Rp 0</span>
          </div>
          <div class="pt-2 border-t border-outline-variant/40 flex items-center justify-between">
            <span class="font-title-md text-title-md font-extrabold text-on-surface">Total Tagihan</span>
            <span class="font-headline-md text-headline-md font-extrabold text-on-surface" id="modal-grandtotal">Rp 4.670.000</span>
          </div>
          <div class="pt-1.5 flex flex-col gap-1 text-[12px] text-on-surface-variant border-t border-dashed border-outline-variant/40">
            <div class="flex items-center justify-between">
              <span id="modal-pay-label">Uang Muka (DP)</span>
              <span class="font-semibold text-on-surface" id="modal-paid-amount">Rp 0 (Belum Dibayar)</span>
            </div>
            <div class="flex items-center justify-between" id="modal-change-row">
              <span>Sisa Tagihan DP</span>
              <span class="font-semibold text-on-surface" id="modal-change-amount">Rp 4.670.000</span>
            </div>
          </div>
        </div>

        <div class="text-[11px] text-on-surface-variant/80 flex items-center gap-1 justify-center pb-1 shrink-0">
          <span class="material-symbols-outlined text-[14px]">verified</span>
          <span>Dicetak via Terminal Kasir POS-01 Toko Bangunan Terpadu</span>
        </div>
      </div>

      <div class="p-space-md border-t border-outline-variant/30 bg-surface-container-lowest flex items-center gap-space-sm shrink-0 z-20">
        <button 
          type="button" 
          id="btn-close-modal" 
          class="h-11 px-4 flex-1 rounded-xl border border-outline-variant text-on-surface font-label-lg text-label-lg font-bold hover:bg-surface-container transition-colors active:scale-95"
        >
          Tutup
        </button>
        <button 
          type="button" 
          id="btn-reprint-modal" 
          class="h-11 px-4 flex-1 bg-primary text-on-primary rounded-xl font-label-lg text-label-lg font-bold hover:bg-primary-container flex items-center justify-center gap-1.5 shadow-sm active:scale-95 transition-all"
        >
          <span class="material-symbols-outlined text-[18px]">print</span>
          Cetak Ulang
        </button>
        <button 
          type="button" 
          id="btn-whatsapp-share" 
          title="Bagikan via WhatsApp" 
          aria-label="Bagikan Nota via WhatsApp" 
          class="h-11 w-11 shrink-0 bg-surface-container-high hover:bg-surface-container text-on-surface rounded-xl flex items-center justify-center transition-colors active:scale-95"
        >
          <span class="material-symbols-outlined text-[20px]">share</span>
        </button>
      </div>
    </div>
  </div>

  <!-- Modal Rekap Kasir (Z-Report) -->
  <div 
    id="modal-rekap-shift" 
    role="dialog" 
    aria-labelledby="modal-rekap-title" 
    aria-modal="true" 
    class="fixed inset-0 z-50 flex flex-col justify-end md:justify-center items-center transition-all duration-300 ease-out opacity-0 pointer-events-none"
  >
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" id="modal-rekap-backdrop"></div>
    <div class="relative w-full max-w-lg max-h-[88vh] md:max-h-[85vh] bg-surface-container-lowest rounded-t-[1.5rem] md:rounded-2xl shadow-2xl flex flex-col overflow-hidden transform transition-all duration-300 ease-out z-10 translate-y-8" id="modal-rekap-sheet-panel">
      
      <div class="w-full flex justify-center pt-3 pb-1 md:hidden shrink-0 bg-surface-container-lowest">
        <div class="w-12 h-1 rounded-full bg-outline-variant/60"></div>
      </div>

      <div class="px-space-md md:px-gutter pt-2 md:pt-3 pb-3 border-b border-outline-variant/30 flex items-start justify-between bg-surface-container-lowest shrink-0 z-20">
        <div class="flex flex-col gap-0.5">
          <div class="flex items-center gap-space-xs">
            <span class="material-symbols-outlined text-secondary text-[22px]">receipt</span>
            <h3 class="font-headline-md text-headline-md font-extrabold text-on-surface" id="modal-rekap-title">Rekap Kasir (Z-Report)</h3>
          </div>
          <div class="flex items-center gap-2 text-on-surface-variant text-[12px] font-medium">
            <span class="flex items-center gap-1">
              <span class="material-symbols-outlined text-[14px]">today</span>
              27 Mei 2024
            </span>
            <span>•</span>
            <span class="text-on-surface font-semibold">Kasir Hendra</span>
            <span>•</span>
            <span class="text-secondary font-semibold">Shift Pagi</span>
          </div>
        </div>
        <button 
          type="button" 
          id="btn-close-rekap-x" 
          aria-label="Tutup Rekap" 
          class="w-8 h-8 rounded-full bg-surface-container hover:bg-surface-container-high text-on-surface-variant hover:text-on-surface flex items-center justify-center transition-colors active:scale-95"
        >
          <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
      </div>

      <div class="flex-1 overflow-y-auto custom-scrollbar px-space-md md:px-gutter py-space-md flex flex-col gap-space-md text-on-surface">
        <div class="bg-secondary/10 border border-secondary/30 rounded-xl p-space-md flex flex-col gap-1 shrink-0">
          <div class="flex items-center justify-between text-[12px] text-secondary font-bold uppercase tracking-wider">
            <span>Total Uang Fisik di Laci</span>
            <span class="text-[11px] font-normal text-on-surface-variant">(Modal + Tunai)</span>
          </div>
          <div class="flex items-baseline justify-between pt-0.5">
            <h2 class="font-headline-lg-mobile text-headline-lg-mobile font-extrabold text-secondary">Rp 5.700.000</h2>
            <span class="text-[12px] text-on-surface-variant font-medium">Kasir Siap Tutup Buku</span>
          </div>
        </div>

        <div class="bg-surface-container-low rounded-xl p-space-md flex flex-col gap-2 border border-outline-variant/30 shrink-0">
          <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Saldo Awal Kasir</span>
          <div class="flex items-center justify-between text-body-md">
            <span class="text-on-surface">Modal Awal Kasir (Uang Kembalian)</span>
            <span class="font-tabular-numeric font-bold text-on-surface">Rp 500.000</span>
          </div>
        </div>

        <div class="bg-surface-container rounded-xl p-space-md flex flex-col gap-2.5 border border-outline-variant/30 shrink-0">
          <div class="flex items-center justify-between pb-1.5 border-b border-outline-variant/30">
            <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Rincian Omzet Penjualan</span>
            <span class="text-label-md font-bold text-secondary">28 Transaksi</span>
          </div>
          <div class="flex items-center justify-between text-[13px]">
            <div class="flex items-center gap-1.5">
              <span class="w-2 h-2 rounded-full bg-primary"></span>
              <span class="text-on-surface">Tunai (Cash di Laci)</span>
              <span class="text-[11px] text-on-surface-variant font-medium">(18 trx)</span>
            </div>
            <span class="font-tabular-numeric font-bold text-on-surface">Rp 5.200.000</span>
          </div>
          <div class="flex items-center justify-between text-[13px]">
            <div class="flex items-center gap-1.5">
              <span class="w-2 h-2 rounded-full bg-surface-container-highest"></span>
              <span class="text-on-surface">QRIS (Gopay/Shopee/OVO)</span>
              <span class="text-[11px] text-on-surface-variant font-medium">(5 trx)</span>
            </div>
            <span class="font-tabular-numeric font-bold text-on-surface">Rp 1.150.000</span>
          </div>
          <div class="flex items-center justify-between text-[13px]">
            <div class="flex items-center gap-1.5">
              <span class="w-2 h-2 rounded-full bg-primary-container"></span>
              <span class="text-on-surface">Transfer Bank (BCA)</span>
              <span class="text-[11px] text-on-surface-variant font-medium">(2 trx)</span>
            </div>
            <span class="font-tabular-numeric font-bold text-on-surface">Rp 1.100.000</span>
          </div>
          <div class="flex items-center justify-between text-[13px]">
            <div class="flex items-center gap-1.5">
              <span class="w-2 h-2 rounded-full bg-secondary-container"></span>
              <span class="text-secondary font-semibold">DP</span>
              <span class="text-[11px] text-on-surface-variant font-medium">(3 trx)</span>
            </div>
            <span class="font-tabular-numeric font-bold text-secondary">Rp 1.000.000</span>
          </div>
          <div class="pt-2 border-t border-outline-variant/40 flex items-center justify-between">
            <span class="font-title-md text-title-md font-extrabold text-on-surface">Total Omzet Penjualan</span>
            <span class="font-headline-md text-headline-md font-extrabold text-on-surface">Rp 8.450.000</span>
          </div>
        </div>

        <div class="text-[11px] text-on-surface-variant/80 flex items-center gap-1 justify-center pb-1 shrink-0">
          <span class="material-symbols-outlined text-[14px]">verified</span>
          <span>Laporan Z-Report Kasir POS-01 Toko Bangunan Terpadu</span>
        </div>
      </div>

      <div class="p-space-md border-t border-outline-variant/30 bg-surface-container-lowest flex items-center gap-space-sm shrink-0 z-20">
        <button 
          type="button" 
          id="btn-close-rekap-modal" 
          class="h-11 flex-1 rounded-xl border border-outline-variant text-on-surface font-label-lg text-label-lg font-bold hover:bg-surface-container transition-colors active:scale-95"
        >
          Tutup
        </button>
        <button 
          type="button" 
          id="btn-print-zreport-modal" 
          class="h-11 px-4 flex-1 bg-secondary text-on-secondary rounded-xl font-label-lg text-label-lg font-bold hover:bg-secondary-container flex items-center justify-center gap-1.5 shadow-sm active:scale-95 transition-all"
        >
          <span class="material-symbols-outlined text-[18px]">print</span>
          Cetak Z-Report
        </button>
      </div>
    </div>
  </div>

  <!-- Modal Konfirmasi Pelunasan -->
  <div 
    id="modal-confirm-settle" 
    role="dialog" 
    aria-labelledby="modal-confirm-settle-title" 
    aria-modal="true" 
    class="fixed inset-0 z-50 flex flex-col justify-end md:justify-center items-center transition-all duration-300 ease-out opacity-0 pointer-events-none"
  >
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" id="modal-settle-backdrop"></div>
    <div class="relative w-full max-w-sm bg-surface-container-lowest rounded-t-[1.5rem] md:rounded-2xl shadow-2xl p-space-md flex flex-col gap-space-md transform transition-all duration-300 ease-out z-10 translate-y-8" id="modal-settle-sheet-panel">
      <div class="flex items-center justify-between pb-2 border-b border-outline-variant/30">
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 rounded-full bg-secondary-fixed flex items-center justify-center text-secondary">
            <span class="material-symbols-outlined text-[20px]">verified</span>
          </div>
          <h3 class="font-title-lg text-title-lg font-bold text-on-surface" id="modal-confirm-settle-title">Konfirmasi Pelunasan Nota</h3>
        </div>
        <button 
          type="button" 
          id="btn-close-settle-x" 
          aria-label="Tutup Modal" 
          class="w-8 h-8 rounded-full bg-surface-container hover:bg-surface-container-high text-on-surface-variant flex items-center justify-center transition-colors active:scale-95"
        >
          <span class="material-symbols-outlined text-[18px]">close</span>
        </button>
      </div>

      <div class="flex flex-col gap-2">
        <p class="text-body-md text-on-surface-variant">Apakah Anda yakin ingin mengubah status nota ini menjadi <strong class="text-on-surface font-bold">LUNAS</strong>?</p>
        <div class="bg-surface-container-low rounded-xl p-3 border border-outline-variant/30 flex flex-col gap-1">
          <div class="flex justify-between items-center text-body-md">
            <span class="text-on-surface-variant">Nomor Nota:</span>
            <span class="font-bold font-tabular-numeric text-on-surface" id="settle-target-nota">NOTA-016</span>
          </div>
          <div class="flex justify-between items-center text-body-md">
            <span class="text-on-surface-variant">Sisa Tagihan:</span>
            <span class="font-bold font-tabular-numeric text-secondary" id="settle-target-nominal">Rp 4.670.000</span>
          </div>
          <div class="flex justify-between items-center text-body-md">
            <span class="text-on-surface-variant">Metode:</span>
            <span class="font-medium text-on-surface">Kas / Tunai Toko</span>
          </div>
        </div>
        <p class="text-[11px] text-on-surface-variant">*Perubahan status akan dicatat dalam ringkasan transaksi hari ini.</p>
      </div>

      <div class="flex items-center gap-space-sm pt-2">
        <button 
          type="button" 
          id="btn-cancel-settle" 
          class="h-11 flex-1 rounded-xl border border-outline-variant text-on-surface font-label-lg text-label-lg font-bold hover:bg-surface-container transition-colors active:scale-95"
        >
          Batal
        </button>
        <button 
          type="button" 
          id="btn-confirm-settle-action" 
          class="h-11 flex-1 bg-secondary text-on-secondary rounded-xl font-label-lg text-label-lg font-bold hover:bg-secondary-container flex items-center justify-center gap-1 shadow-sm active:scale-95 transition-all"
        >
          <span class="material-symbols-outlined text-[18px]">done_all</span>
          Ya, Ubah ke Lunas
        </button>
      </div>
    </div>
  </div>

  <!-- Toast Notification -->
  <div 
    id="toast-settle" 
    class="fixed bottom-6 left-1/2 transform -translate-x-1/2 z-50 transition-all duration-300 opacity-0 pointer-events-none translate-y-4"
  >
    <div class="bg-primary-container text-on-primary px-4 py-2.5 rounded-xl shadow-2xl flex items-center gap-2 border border-outline-variant/40">
      <span class="material-symbols-outlined text-secondary-container text-[20px]">check_circle</span>
      <span class="text-body-md font-semibold" id="toast-settle-text">Status NOTA-016 berhasil diubah menjadi LUNAS</span>
    </div>
  </div>

</body>
</html>