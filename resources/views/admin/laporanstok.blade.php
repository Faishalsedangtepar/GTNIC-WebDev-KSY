@extends('layouts.admin')

@section('title', 'Laporan Keuangan - Aura Material')
@section('body_class', 'bg-[#f8f9ff] text-[#0b1c30]')
@section('show_date', 'ya')

@push('head')
  <style>@layer base{html,body{margin:0;padding:0;}body{overscroll-behavior:none;}main>:first-child{margin-top:0!important;}main>:last-child{margin-bottom:0!important;}}::-webkit-scrollbar{display:none;}</style>
@endpush

@section('content')
<div class="bg-[#f8f9ff] min-h-[calc(100vh-4rem)] p-[1rem] lg:p-[1.5rem] pb-24">
<div class="flex flex-col w-full space-y-[2rem]">
<!-- SECTION 1: HEADER & FILTER PERIODE -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-[1.25rem] bg-[#ffffff] p-[2rem] rounded-[0.5rem] shadow-sm">
<div class="flex flex-col space-y-[0.25rem]">
<div class="flex items-center gap-[0.5rem] text-[#a73a00]">


</div>
<h1 class="text-[28px] leading-[36px] text-[#0b1c30] font-bold tracking-tight">Laporan Pendapatan &amp; Laba Kotor</h1>
<p class="text-[13px] leading-[18px] font-normal text-[#45464d] max-w-2xl">
        Rekapitulasi total omzet dan estimasi laba kotor penjualan material selama satu bulan berjalan tanpa potongan operasional atau pengeluaran.
      </p>
</div>
<!-- Actions & Filter Controls -->
<div class="flex flex-wrap items-center gap-[0.5rem]">
<div class="relative inline-flex items-center bg-[#eff4ff] rounded-[0.25rem] px-[0.75rem] py-[0.5rem]">
<span class="material-symbols-outlined text-[#45464d] text-[18px] mr-[0.25rem]">calendar_month</span>
<select class="bg-transparent text-[15px] leading-[20px] text-[#0b1c30] font-semibold focus:outline-none cursor-pointer pr-[0.75rem]">
<option selected="" value="2026-05">Bulan Ini (Mei 2026)</option>
<option value="2026-04">April 2026</option>
<option value="2026-03">Maret 2026</option>
<option value="2026-02">Februari 2026</option>
</select>
</div>
<button class="inline-flex items-center gap-[0.25rem] bg-[#eff4ff] hover:bg-[#e5eeff] text-[#0b1c30] text-[15px] leading-[20px] font-semibold px-[0.75rem] py-[0.5rem] rounded-[0.25rem] transition-colors shadow-sm" onclick="window.print()" type="button">
<span class="material-symbols-outlined text-[18px]">print</span>
<span class="">Cetak Ringkasan</span>
</button>
<button class="inline-flex items-center gap-[0.25rem] bg-[#131b2e] hover:bg-[#001d31] text-[#ffffff] text-[15px] leading-[20px] font-semibold px-[0.75rem] py-[0.5rem] rounded-[0.25rem] transition-colors shadow-sm" type="button">
<span class="material-symbols-outlined text-[18px]">file_download</span>
<span class="">Ekspor Excel</span>
</button>
</div>
</div>
<!-- SECTION 2: KARTU RINGKASAN UTAMA (3 KARTU BERSIH & FOKUS) -->
<div class="grid grid-cols-1 gap-[1.25rem] lg:grid-cols-2">
<!-- Card 1: Total Pendapatan Kotor (Omzet) -->
<div class="flex flex-col justify-between bg-[#ffffff] p-[2rem] rounded-[0.5rem] shadow-sm relative overflow-hidden">
<div class="space-y-[0.25rem]">
<div class="flex items-center justify-between">
<span class="text-[13px] leading-[16px] font-semibold text-[#45464d] uppercase tracking-wider">Omzet Per Bulan</span>

</div>
<div class="pt-[0.25rem]">
<span class="text-[28px] leading-[36px] font-bold text-[#0b1c30] tracking-tight block">Rp 284.500.000</span>
</div>
</div>
<div class="mt-[1.25rem] pt-[0.75rem] bg-[#eff4ff]/60 -mx-[2rem] -mb-[2rem] px-[2rem] pb-[0.75rem] flex items-center justify-between">
<div class="flex items-center gap-[0.25rem] text-[#45464d]">
<span class="material-symbols-outlined text-[16px]">receipt_long</span>
<span class="text-[13px] leading-[18px] font-normal">428 Transaksi / Nota Selesai</span>
</div>

</div>
</div>
<!-- Card 2: Total Laba Kotor -->

<!-- Card 3: Rata-rata Penjualan Harian -->
<div class="flex flex-col justify-between bg-[#ffffff] p-[2rem] rounded-[0.5rem] shadow-sm relative overflow-hidden">
<div class="space-y-[0.25rem]">
<div class="flex items-center justify-between">
<span class="text-[13px] leading-[16px] font-semibold text-[#45464d] uppercase tracking-wider">Rata-Rata Harian</span>
<span class="material-symbols-outlined text-[#45464d] text-[20px]">calendar_today</span>
</div>
<div class="pt-[0.25rem]">
<span class="text-[28px] leading-[36px] font-bold text-[#0b1c30] tracking-tight block">Rp 9.483.333 <span class="text-[13px] leading-[18px] text-[#45464d] font-normal">/ hari</span></span>
</div>
</div>
<div class="mt-[1.25rem] pt-[0.75rem] bg-[#eff4ff]/60 -mx-[2rem] -mb-[2rem] px-[2rem] pb-[0.75rem] flex items-center justify-between">
<div class="flex items-center gap-[0.25rem] text-[#45464d]">
<span class="material-symbols-outlined text-[16px]">pace</span>
<span class="text-[13px] leading-[18px] font-normal">Rata-rata 14 transaksi per hari</span>
</div>

</div>
</div>
</div>
<!-- SECTION 3: KONTRIBUSI PENDAPATAN PER KATEGORI BARANG -->
<div class="bg-[#ffffff] p-[2rem] rounded-[0.5rem] shadow-sm flex flex-col space-y-[1.25rem]"><div class="flex flex-col sm:flex-row sm:items-center justify-between gap-[0.25rem]"><div class="flex flex-col"><h2 class="text-[17px] leading-[24px] font-bold text-[#0b1c30]">Kontribusi Omzet per Kategori</h2><p class="text-[13px] leading-[18px] font-normal text-[#45464d]">Distribusi pendapatan penjualan menurut kelompok barang.</p></div><span class="text-[11px] leading-[14px] text-[#45464d] uppercase tracking-wider font-semibold">5 Kategori Material</span></div><div class="grid grid-cols-1 lg:grid-cols-2 gap-x-[2rem] gap-y-[1.25rem] pt-[0.25rem]"><div class="flex flex-col space-y-[0.25rem]"><div class="flex items-center justify-between"><div class="flex items-center gap-[0.25rem]"><span class="w-2.5 h-2.5 rounded-full bg-[#a73a00]"></span><span class="text-[15px] leading-[20px] font-bold text-[#0b1c30]">Semen &amp; Mortar</span></div><div class="flex items-center gap-[0.5rem]"><span class="text-[15px] leading-[20px] tracking-[-0.01em] text-[#0b1c30] font-bold">Rp 98.200.000</span><span class="text-[11px] leading-[14px] tracking-[0.04em] font-bold text-[#45464d]">34.5%</span></div></div><div class="w-full bg-[#dce9ff] h-2 rounded-full overflow-hidden"><div class="bg-[#a73a00] h-full rounded-full" style="width: 34.5%"></div></div></div><div class="flex flex-col space-y-[0.25rem]"><div class="flex items-center justify-between"><div class="flex items-center gap-[0.25rem]"><span class="w-2.5 h-2.5 rounded-full bg-[#131b2e]"></span><span class="text-[15px] leading-[20px] font-bold text-[#0b1c30]">Besi &amp; Baja Tulang</span></div><div class="flex items-center gap-[0.5rem]"><span class="text-[15px] leading-[20px] tracking-[-0.01em] text-[#0b1c30] font-bold">Rp 85.350.000</span><span class="text-[11px] leading-[14px] tracking-[0.04em] font-bold text-[#45464d]">30.0%</span></div></div><div class="w-full bg-[#dce9ff] h-2 rounded-full overflow-hidden"><div class="bg-[#131b2e] h-full rounded-full" style="width: 30.0%"></div></div></div><div class="flex flex-col space-y-[0.25rem]"><div class="flex items-center justify-between"><div class="flex items-center gap-[0.25rem]"><span class="w-2.5 h-2.5 rounded-full bg-[#fd651e]"></span><span class="text-[15px] leading-[20px] font-bold text-[#0b1c30]">Pasir &amp; Batu Bata</span></div><div class="flex items-center gap-[0.5rem]"><span class="text-[15px] leading-[20px] tracking-[-0.01em] text-[#0b1c30] font-bold">Rp 45.100.000</span><span class="text-[11px] leading-[14px] tracking-[0.04em] font-bold text-[#45464d]">15.8%</span></div></div><div class="w-full bg-[#dce9ff] h-2 rounded-full overflow-hidden"><div class="bg-[#fd651e] h-full rounded-full" style="width: 15.8%"></div></div></div><div class="flex flex-col space-y-[0.25rem]"><div class="flex items-center justify-between"><div class="flex items-center gap-[0.25rem]"><span class="w-2.5 h-2.5 rounded-full bg-[#188ace]"></span><span class="text-[15px] leading-[20px] font-bold text-[#0b1c30]">Cat &amp; Perlengkapan Sanitasi</span></div><div class="flex items-center gap-[0.5rem]"><span class="text-[15px] leading-[20px] tracking-[-0.01em] text-[#0b1c30] font-bold">Rp 32.850.000</span><span class="text-[11px] leading-[14px] tracking-[0.04em] font-bold text-[#45464d]">11.5%</span></div></div><div class="w-full bg-[#dce9ff] h-2 rounded-full overflow-hidden"><div class="bg-[#188ace] h-full rounded-full" style="width: 11.5%"></div></div></div><div class="flex flex-col space-y-[0.25rem] lg:col-span-2"><div class="flex items-center justify-between"><div class="flex items-center gap-[0.25rem]"><span class="w-2.5 h-2.5 rounded-full bg-[#76777d]"></span><span class="text-[15px] leading-[20px] font-bold text-[#0b1c30]">Kayu &amp; Triplek</span></div><div class="flex items-center gap-[0.5rem]"><span class="text-[15px] leading-[20px] tracking-[-0.01em] text-[#0b1c30] font-bold">Rp 23.000.000</span><span class="text-[11px] leading-[14px] tracking-[0.04em] font-bold text-[#45464d]">8.2%</span></div></div><div class="w-full bg-[#dce9ff] h-2 rounded-full overflow-hidden"><div class="bg-[#76777d] h-full rounded-full" style="width: 8.2%"></div></div></div></div></div>
<!-- SECTION 4: TABEL REKAPAN PENDAPATAN HARIAN (1 BULAN) -->
<div class="bg-[#ffffff] rounded-[0.5rem] shadow-sm flex flex-col overflow-hidden">
<div class="p-[2rem] flex flex-col sm:flex-row sm:items-center justify-between gap-[0.75rem]">
<div>
<h2 class="text-[17px] leading-[24px] font-bold text-[#0b1c30]">Rekapan Harian Penjualan (Mei 2026)</h2>
<p class="text-[13px] leading-[18px] font-normal text-[#45464d]">Rincian pendapatan kotor, estimasi laba, dan pencatatan transaksi per hari.</p>
</div>
<div class="flex items-center gap-[0.5rem] text-[#45464d] text-[11px] leading-[14px] tracking-[0.04em] font-bold">


</div>
</div>
<!-- Data Table Container with Spacious Layout & High Horizontal Scrollability -->
<div class="w-full overflow-x-auto">
<table class="w-full text-left whitespace-nowrap"><thead><tr class="bg-[#eff4ff] text-[#45464d] text-[11px] leading-[14px] font-bold uppercase tracking-wider"><th class="py-[0.75rem] px-[2rem]">Tanggal</th><th class="py-[0.75rem] px-[1.25rem] text-center">Jumlah Transaksi</th><th class="py-[0.75rem] px-[2rem] text-right">Pendapatan Kotor</th><th class="py-[0.75rem] px-[2rem] text-center">Status Setoran</th></tr></thead><tbody class="divide-y divide-[#e5eeff] text-[#0b1c30] text-[13px] leading-[18px] font-normal"><tr class="hover:bg-[#eff4ff]/40 transition-colors"><td class="py-[0.75rem] px-[2rem] text-[15px] leading-[20px] font-bold">01 Mei 2026</td><td class="py-[0.75rem] px-[1.25rem] text-center text-[15px] leading-[20px] tracking-[-0.01em] font-bold">16 Nota</td><td class="py-[0.75rem] px-[2rem] text-right text-[15px] leading-[20px] tracking-[-0.01em] font-semibold">Rp 11.250.000</td><td class="py-[0.75rem] px-[2rem] text-center"><span class="inline-flex items-center gap-1 bg-[#dce9ff] text-[#0b1c30] text-[11px] leading-[14px] tracking-[0.04em] px-[0.5rem] py-0.5 rounded-full font-bold"><span class="material-symbols-outlined text-[14px] text-[#a73a00]">check_circle</span> Masuk Kasir</span></td></tr><tr class="hover:bg-[#eff4ff]/40 transition-colors bg-[#ffffff]"><td class="py-[0.75rem] px-[2rem] text-[15px] leading-[20px] font-bold">02 Mei 2026</td><td class="py-[0.75rem] px-[1.25rem] text-center text-[15px] leading-[20px] tracking-[-0.01em] font-bold">14 Nota</td><td class="py-[0.75rem] px-[2rem] text-right text-[15px] leading-[20px] tracking-[-0.01em] font-semibold">Rp 9.800.000</td><td class="py-[0.75rem] px-[2rem] text-center"><span class="inline-flex items-center gap-1 bg-[#dce9ff] text-[#0b1c30] text-[11px] leading-[14px] tracking-[0.04em] px-[0.5rem] py-0.5 rounded-full font-bold"><span class="material-symbols-outlined text-[14px] text-[#a73a00]">check_circle</span> Masuk Kasir</span></td></tr><tr class="hover:bg-[#eff4ff]/40 transition-colors"><td class="py-[0.75rem] px-[2rem] text-[15px] leading-[20px] font-bold">03 Mei 2026</td><td class="py-[0.75rem] px-[1.25rem] text-center text-[15px] leading-[20px] tracking-[-0.01em] font-bold">11 Nota</td><td class="py-[0.75rem] px-[2rem] text-right text-[15px] leading-[20px] tracking-[-0.01em] font-semibold">Rp 7.450.000</td><td class="py-[0.75rem] px-[2rem] text-center"><span class="inline-flex items-center gap-1 bg-[#dce9ff] text-[#0b1c30] text-[11px] leading-[14px] tracking-[0.04em] px-[0.5rem] py-0.5 rounded-full font-bold"><span class="material-symbols-outlined text-[14px] text-[#a73a00]">check_circle</span> Masuk Kasir</span></td></tr><tr class="hover:bg-[#eff4ff]/40 transition-colors bg-[#ffffff]"><td class="py-[0.75rem] px-[2rem] text-[15px] leading-[20px] font-bold">04 Mei 2026</td><td class="py-[0.75rem] px-[1.25rem] text-center text-[15px] leading-[20px] tracking-[-0.01em] font-bold">19 Nota</td><td class="py-[0.75rem] px-[2rem] text-right text-[15px] leading-[20px] tracking-[-0.01em] font-semibold">Rp 14.300.000</td><td class="py-[0.75rem] px-[2rem] text-center"><span class="inline-flex items-center gap-1 bg-[#dce9ff] text-[#0b1c30] text-[11px] leading-[14px] tracking-[0.04em] px-[0.5rem] py-0.5 rounded-full font-bold"><span class="material-symbols-outlined text-[14px] text-[#a73a00]">check_circle</span> Masuk Kasir</span></td></tr><tr class="hover:bg-[#eff4ff]/40 transition-colors"><td class="py-[0.75rem] px-[2rem] text-[15px] leading-[20px] font-bold">05 Mei 2026</td><td class="py-[0.75rem] px-[1.25rem] text-center text-[15px] leading-[20px] tracking-[-0.01em] font-bold">15 Nota</td><td class="py-[0.75rem] px-[2rem] text-right text-[15px] leading-[20px] tracking-[-0.01em] font-semibold">Rp 10.600.000</td><td class="py-[0.75rem] px-[2rem] text-center"><span class="inline-flex items-center gap-1 bg-[#dce9ff] text-[#0b1c30] text-[11px] leading-[14px] tracking-[0.04em] px-[0.5rem] py-0.5 rounded-full font-bold"><span class="material-symbols-outlined text-[14px] text-[#a73a00]">check_circle</span> Masuk Kasir</span></td></tr><tr class="hover:bg-[#eff4ff]/40 transition-colors bg-[#ffffff]"><td class="py-[0.75rem] px-[2rem] text-[15px] leading-[20px] font-bold">06 Mei 2026</td><td class="py-[0.75rem] px-[1.25rem] text-center text-[15px] leading-[20px] tracking-[-0.01em] font-bold">18 Nota</td><td class="py-[0.75rem] px-[2rem] text-right text-[15px] leading-[20px] tracking-[-0.01em] font-semibold">Rp 12.850.000</td><td class="py-[0.75rem] px-[2rem] text-center"><span class="inline-flex items-center gap-1 bg-[#dce9ff] text-[#0b1c30] text-[11px] leading-[14px] tracking-[0.04em] px-[0.5rem] py-0.5 rounded-full font-bold"><span class="material-symbols-outlined text-[14px] text-[#a73a00]">check_circle</span> Masuk Kasir</span></td></tr><tr class="hover:bg-[#eff4ff]/40 transition-colors"><td class="py-[0.75rem] px-[2rem] text-[15px] leading-[20px] font-bold">07 Mei 2026</td><td class="py-[0.75rem] px-[1.25rem] text-center text-[15px] leading-[20px] tracking-[-0.01em] font-bold">13 Nota</td><td class="py-[0.75rem] px-[2rem] text-right text-[15px] leading-[20px] tracking-[-0.01em] font-semibold">Rp 8.900.000</td><td class="py-[0.75rem] px-[2rem] text-center"><span class="inline-flex items-center gap-1 bg-[#dce9ff] text-[#0b1c30] text-[11px] leading-[14px] tracking-[0.04em] px-[0.5rem] py-0.5 rounded-full font-bold"><span class="material-symbols-outlined text-[14px] text-[#a73a00]">check_circle</span> Masuk Kasir</span></td></tr><tr class="hover:bg-[#eff4ff]/40 transition-colors bg-[#ffffff]"><td class="py-[0.75rem] px-[2rem] text-[15px] leading-[20px] font-bold">08 Mei 2026</td><td class="py-[0.75rem] px-[1.25rem] text-center text-[15px] leading-[20px] tracking-[-0.01em] font-bold">12 Nota</td><td class="py-[0.75rem] px-[2rem] text-right text-[15px] leading-[20px] tracking-[-0.01em] font-semibold">Rp 7.800.000</td><td class="py-[0.75rem] px-[2rem] text-center"><span class="inline-flex items-center gap-1 bg-[#dce9ff] text-[#0b1c30] text-[11px] leading-[14px] tracking-[0.04em] px-[0.5rem] py-0.5 rounded-full font-bold"><span class="material-symbols-outlined text-[14px] text-[#a73a00]">check_circle</span> Masuk Kasir</span></td></tr><tr class="hover:bg-[#eff4ff]/40 transition-colors"><td class="py-[0.75rem] px-[2rem] text-[15px] leading-[20px] font-bold">09 Mei 2026</td><td class="py-[0.75rem] px-[1.25rem] text-center text-[15px] leading-[20px] tracking-[-0.01em] font-bold">17 Nota</td><td class="py-[0.75rem] px-[2rem] text-right text-[15px] leading-[20px] tracking-[-0.01em] font-semibold">Rp 13.100.000</td><td class="py-[0.75rem] px-[2rem] text-center"><span class="inline-flex items-center gap-1 bg-[#dce9ff] text-[#0b1c30] text-[11px] leading-[14px] tracking-[0.04em] px-[0.5rem] py-0.5 rounded-full font-bold"><span class="material-symbols-outlined text-[14px] text-[#a73a00]">check_circle</span> Masuk Kasir</span></td></tr><tr class="hover:bg-[#eff4ff]/40 transition-colors bg-[#ffffff]"><td class="py-[0.75rem] px-[2rem] text-[15px] leading-[20px] font-bold">10 Mei 2026</td><td class="py-[0.75rem] px-[1.25rem] text-center text-[15px] leading-[20px] tracking-[-0.01em] font-bold">8 Nota</td><td class="py-[0.75rem] px-[2rem] text-right text-[15px] leading-[20px] tracking-[-0.01em] font-semibold">Rp 5.200.000</td><td class="py-[0.75rem] px-[2rem] text-center"><span class="inline-flex items-center gap-1 bg-[#dce9ff] text-[#0b1c30] text-[11px] leading-[14px] tracking-[0.04em] px-[0.5rem] py-0.5 rounded-full font-bold"><span class="material-symbols-outlined text-[14px] text-[#a73a00]">check_circle</span> Masuk Kasir</span></td></tr><tr class="bg-[#eff4ff]/30"><td class="py-[0.5rem] px-[2rem] text-center text-[11px] leading-[14px] font-bold text-[#45464d] tracking-wider uppercase" colspan="4">• • • Menampilkan 10 dari 30 hari rekapitulasi data Mei 2026 • • •</td></tr><tr class="hover:bg-[#eff4ff]/40 transition-colors"><td class="py-[0.75rem] px-[2rem] text-[15px] leading-[20px] font-bold">29 Mei 2026</td><td class="py-[0.75rem] px-[1.25rem] text-center text-[15px] leading-[20px] tracking-[-0.01em] font-bold">15 Nota</td><td class="py-[0.75rem] px-[2rem] text-right text-[15px] leading-[20px] tracking-[-0.01em] font-semibold">Rp 10.400.000</td><td class="py-[0.75rem] px-[2rem] text-center"><span class="inline-flex items-center gap-1 bg-[#dce9ff] text-[#0b1c30] text-[11px] leading-[14px] tracking-[0.04em] px-[0.5rem] py-0.5 rounded-full font-bold"><span class="material-symbols-outlined text-[14px] text-[#a73a00]">check_circle</span> Masuk Kasir</span></td></tr><tr class="hover:bg-[#eff4ff]/40 transition-colors bg-[#ffffff]"><td class="py-[0.75rem] px-[2rem] text-[15px] leading-[20px] font-bold">30 Mei 2026</td><td class="py-[0.75rem] px-[1.25rem] text-center text-[15px] leading-[20px] tracking-[-0.01em] font-bold">16 Nota</td><td class="py-[0.75rem] px-[2rem] text-right text-[15px] leading-[20px] tracking-[-0.01em] font-semibold">Rp 11.500.000</td><td class="py-[0.75rem] px-[2rem] text-center"><span class="inline-flex items-center gap-1 bg-[#dce9ff] text-[#0b1c30] text-[11px] leading-[14px] tracking-[0.04em] px-[0.5rem] py-0.5 rounded-full font-bold"><span class="material-symbols-outlined text-[14px] text-[#a73a00]">check_circle</span> Masuk Kasir</span></td></tr></tbody><tfoot><tr class="bg-[#131b2e] text-[#ffffff] text-[20px] leading-[26px] tracking-[-0.01em] font-bold"><td class="py-[1.25rem] px-[2rem]">TOTAL AKUMULASI (1 BULAN)</td><td class="py-[1.25rem] px-[1.25rem] text-center text-[15px] leading-[20px] tracking-[-0.01em] font-bold text-[#dae2fd]">428 Nota</td><td class="py-[1.25rem] px-[2rem] text-right text-[15px] leading-[20px] tracking-[-0.01em] font-bold">Rp 284.500.000</td><td class="py-[1.25rem] px-[2rem] text-center"><span class="inline-flex items-center gap-1 bg-[#001d31] text-[#131b2e] text-[11px] leading-[14px] tracking-[0.04em] font-bold px-[0.75rem] py-1 rounded-full uppercase">Semua Lunas</span></td></tr></tfoot></table>
</div>
</div>
<!-- SECTION 5: CATATAN PENGAWASAN FINANSIAL (MINIMALIS) -->
<div class="p-[1.25rem] bg-[#eff4ff] rounded-[0.5rem] flex items-start gap-[0.75rem]">
<span class="material-symbols-outlined text-[#a73a00] text-[22px] flex-shrink-0 mt-0.5">info</span>
<div class="flex flex-col space-y-[0.25rem] text-[#45464d] text-[13px] leading-[18px] font-normal">
<span class="text-[15px] leading-[20px] font-bold text-[#0b1c30]">Catatan Finansial Penjualan</span>
<p class="">
        Semua angka pendapatan kotor merefleksikan transaksi kasir POS yang telah valid dan barang telah diterbitkan surat jalan / diserahkan langsung. Laba kotor dihitung dari selisih harga jual dengan harga modal beli awal barang secara real-time. Tidak memperhitungkan beban operasional listrik, gaji pegawai, maupun bahan bakar armada kirim.
      </p>
</div>
</div>
</div>
</div>
@endsection
