@extends('layouts.admin')

@section('title', 'Data Stok Barang - UD.Tentrem')
@section('body_class', 'bg-surface text-on-surface antialiased selection:bg-secondary-container selection:text-on-primary')

@push('head')
  @vite(['resources/js/admin.js'])
  <style>@layer base{html,body{margin:0;padding:0;overflow-x:hidden;}body{overscroll-behavior:none;}main>:first-child{margin-top:0!important;}main>:last-child{margin-bottom:0!important;}}::-webkit-scrollbar{display:none;}</style>
@endpush

@section('content')
<div class="min-h-screen bg-surface px-3.5 sm:px-6 lg:px-[1.5rem] pt-4 sm:pt-6 pb-32" style="height: 100vh; overflow-y: auto;">

<div class="flex flex-col w-full gap-5 sm:gap-6">
<!-- Title & Action Section -->
<div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3.5">
<div class="flex flex-col">
<div class="flex items-center gap-2 mb-1">
<span class="text-[11px] leading-[14px] uppercase tracking-wider text-secondary font-bold">Manajemen Inventaris</span>
<span class="w-1.5 h-1.5 rounded-full bg-outline-variant"></span>
<span class="text-[11px] leading-[14px] tracking-[0.04em] font-bold text-on-surface-variant">Live Stock Sync</span>
</div>
<h1 class="text-2xl sm:text-[28px] sm:leading-[36px] text-on-surface tracking-tight font-extrabold">Data Stok Barang</h1>
<p class="text-xs sm:text-[13px] sm:leading-[18px] sm:font-normal text-on-surface-variant max-w-2xl mt-0.5">
            Kelola inventaris material, harga modal &amp; jual, serta status ketersediaan barang secara real-time.
          </p>
</div>
<div class="w-full sm:w-auto flex items-center shrink-0 relative">
<!-- Button Tambah Data: Full width on mobile, auto on tablet/desktop -->
<button class="w-full sm:w-auto h-11 px-5 bg-secondary hover:bg-secondary-container active:scale-[0.98] text-on-primary rounded-[0.75rem] font-semibold text-sm flex items-center justify-center gap-2 shadow-sm shadow-secondary/20 transition-all cursor-pointer" onclick="openModal('add')">
<span class="material-symbols-outlined text-[20px]">add_circle</span>
<span class="">Tambah Data Barang</span>
</button>
</div>
</div>
<!-- Ringkasan Stok Cards Overview (Desktop 4-cols, Tablet/Mobile 2-cols) -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
<!-- 1. Total SKU -->
<div class="bg-surface-container-lowest p-3.5 sm:p-4 rounded-[0.75rem] border border-outline-variant/40 shadow-sm flex items-center justify-between hover:border-outline-variant transition-colors">
<div class="flex flex-col">
<span class="text-[11px] uppercase tracking-wider text-on-surface-variant font-semibold">Total SKU</span>
<span class="text-xl sm:text-2xl text-on-surface font-extrabold tabular-nums mt-0.5">840</span>
</div>
</div>
<!-- 2. Stok Tersedia -->
<div class="bg-surface-container-lowest p-3.5 sm:p-4 rounded-[0.75rem] border border-outline-variant/40 shadow-sm flex items-center justify-between hover:border-outline-variant transition-colors">
<div class="flex flex-col">
<span class="text-[11px] uppercase tracking-wider text-emerald-700 font-semibold">Stok Tersedia</span>
<span class="text-xl sm:text-2xl text-on-surface font-extrabold tabular-nums mt-0.5">792</span>
</div>
</div>
<!-- 3. Stok Menipis -->
<div class="bg-surface-container-lowest p-3.5 sm:p-4 rounded-[0.75rem] border border-outline-variant/40 shadow-sm flex items-center justify-between hover:border-outline-variant transition-colors">
<div class="flex flex-col">
<span class="text-[11px] uppercase tracking-wider text-amber-700 font-semibold">Stok Menipis</span>
<span class="text-xl sm:text-2xl text-on-surface font-extrabold tabular-nums mt-0.5">36</span>
</div>
</div>
<!-- 4. Stok Habis -->
<div class="bg-surface-container-lowest p-3.5 sm:p-4 rounded-[0.75rem] border border-outline-variant/40 shadow-sm flex items-center justify-between hover:border-outline-variant transition-colors">
<div class="flex flex-col">
<span class="text-[11px] uppercase tracking-wider text-error font-semibold">Stok Habis</span>
<span class="text-xl sm:text-2xl text-on-surface font-extrabold tabular-nums mt-0.5">12</span>
</div>
</div>
</div>
<!-- Main Container: Filter + Search + (Table / Mobile Cards) -->
<div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/40 shadow-sm p-4 sm:p-5 flex flex-col gap-4">
<!-- Quick Category Chips (Smooth Horizontal Scroll on mobile/tablet) -->
<div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none whitespace-nowrap -mx-1 px-1"><button class="category-tab px-3.5 py-1.5 rounded-[0.375rem] bg-primary-container text-on-primary font-semibold text-xs shrink-0 shadow-sm transition-transform active:scale-95 cursor-pointer" data-category="Semua" onclick="filterByCategory('Semua')">Semua (840)</button><button class="category-tab px-3.5 py-1.5 rounded-[0.375rem] bg-surface-container hover:bg-surface-container-high text-on-surface font-medium text-xs shrink-0 transition-colors cursor-pointer" data-category="Semen" onclick="filterByCategory('Semen')">Semen (184)</button><button class="category-tab px-3.5 py-1.5 rounded-[0.375rem] bg-surface-container hover:bg-surface-container-high text-on-surface font-medium text-xs shrink-0 transition-colors cursor-pointer" data-category="Besi &amp; Baja" onclick="filterByCategory('Besi &amp; Baja')">Besi &amp; Baja (230)</button><button class="category-tab px-3.5 py-1.5 rounded-[0.375rem] bg-surface-container hover:bg-surface-container-high text-on-surface font-medium text-xs shrink-0 transition-colors cursor-pointer" data-category="Cat &amp; Kimia" onclick="filterByCategory('Cat &amp; Kimia')">Cat &amp; Kimia (165)</button><button class="category-tab px-3.5 py-1.5 rounded-[0.375rem] bg-surface-container hover:bg-surface-container-high text-on-surface font-medium text-xs shrink-0 transition-colors cursor-pointer" data-category="Keramik &amp; Pasir" onclick="filterByCategory('Keramik &amp; Pasir')">Keramik &amp; Pasir (261)</button></div>
<!-- Controls Row: Entries Dropdown & Search Input (Stack on mobile, row on tablet/desktop) -->
<div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 pt-1 border-t border-surface-container">
<div class="flex items-center gap-2 text-on-surface-variant text-xs sm:text-sm">
<span class="">Tampilkan</span>
<div class="relative inline-block">
<select class="appearance-none bg-surface-container hover:bg-surface-container-high/80 text-on-surface font-semibold text-xs sm:text-sm rounded-[0.375rem] pl-3 pr-8 py-2 border border-outline-variant/40 focus:outline-none focus:ring-1 focus:ring-secondary cursor-pointer transition-colors">
<option selected="" value="10">10</option>
<option value="25">25</option>
<option value="50">50</option>
<option value="100">100</option>
</select>
<span class="material-symbols-outlined absolute right-2 top-2.5 text-on-surface-variant text-[18px] pointer-events-none">expand_more</span>
</div>
<span class="">data per halaman</span>
</div>
<!-- Search Input: Flex on tablet/desktop, Full width on mobile -->
<div class="relative w-full sm:w-72 lg:w-80">
<span class="material-symbols-outlined absolute left-3 top-2.5 text-outline text-[20px]">search</span>
<input class="w-full pl-10 pr-9 py-2 bg-surface-container-low hover:bg-surface-container focus:bg-surface-container-lowest text-on-surface border border-outline-variant/40 rounded-[0.375rem] text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/40 focus:border-secondary transition-all placeholder:text-outline" id="tableSearch" placeholder="Cari kode, nama, atau brand..." type="text" oninput="applyInventoryFilter()">
<button class="material-symbols-outlined absolute right-2.5 top-2.5 text-outline hover:text-on-surface-variant text-[18px] cursor-pointer" onclick="document.getElementById('tableSearch').value=''; applyInventoryFilter();" title="Hapus pencarian">close</button>
</div>
</div>
<!-- TAMPILAN 1: TABEL LENGKAP 10 KOLOM (Tablet md & Desktop lg+) -->
<div class="hidden md:block overflow-x-auto w-full rounded-[0.75rem] border border-outline-variant/40">
<table class="min-w-[860px] w-full text-left text-sm border-collapse">
<thead>
<tr class="bg-surface-container-low text-on-surface-variant border-b border-outline-variant/40 font-semibold text-xs uppercase tracking-wider">
<th class="py-3 px-3 w-12 text-center">No</th>
<th class="py-3 px-3">Kode Barang</th>
<th class="py-3 px-4">Nama Barang</th>
<th class="py-3 px-3">Kategori</th>
<th class="py-3 px-3">Brand</th>
<th class="py-3 px-4 text-right">Stok Fisik</th>
<th class="py-3 px-4 text-right">Harga Beli</th>
<th class="py-3 px-4 text-right">Harga Jual</th>
<th class="py-3 px-3 text-center">Status</th>
<th class="py-3 px-3 text-center w-28">Opsi</th>
</tr>
</thead>
<tbody class="divide-y divide-surface-container text-on-surface"><tr class="inventory-row hover:bg-surface-container-low/80 transition-colors" data-category="Semen" data-text="smn-001 semen gresik 50kg semen"><td class="py-3.5 px-3 text-center text-on-surface-variant font-semibold row-number">1</td><td class="py-3.5 px-3"><span class="font-mono text-xs font-bold text-on-surface bg-surface-container px-2 py-1 rounded border border-outline-variant/40">SMN-001</span></td><td class="py-3.5 px-4 font-bold text-on-surface">Semen Gresik 50kg</td><td class="py-3.5 px-3"><span class="px-2 py-0.5 rounded-md bg-tertiary-fixed text-on-tertiary-fixed-variant font-semibold text-xs border border-tertiary-fixed-dim/40">Semen</span></td><td class="py-3.5 px-3 text-on-surface-variant">Semen Gresik</td><td class="py-3.5 px-4 text-right font-bold text-on-surface">240 <span class="font-normal text-xs text-on-surface-variant">Zak</span></td><td class="py-3.5 px-4 text-right text-on-surface-variant tabular-nums font-mono text-xs">Rp 56.000</td><td class="py-3.5 px-4 text-right font-bold text-on-surface tabular-nums font-mono text-xs">Rp 64.000</td><td class="py-3.5 px-3 text-center"><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold text-xs border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Tersedia</span></td><td class="py-3.5 px-3 text-center"><div class="flex items-center justify-center gap-1.5"><button class="p-1.5 rounded-[0.375rem] bg-surface-container hover:bg-surface-container-high text-on-surface transition-colors cursor-pointer" onclick="openModal('edit', {kode:'SMN-001', nama:'Semen Gresik 50kg', kategori:'Semen', brand:'Semen Gresik', stok:'240', satuan:'Zak', beli:'56000', jual:'64000'})" title="Edit Data"><span class="material-symbols-outlined text-[18px]">edit</span></button><button class="p-1.5 rounded-[0.375rem] bg-amber-50 hover:bg-amber-100 text-amber-700 transition-colors cursor-pointer" onclick="openModal('adjust', {kode:'SMN-001', nama:'Semen Gresik 50kg', stok:'240', satuan:'Zak'})" title="Penyesuaian Stok"><span class="material-symbols-outlined text-[18px]">swap_vert</span></button><button class="p-1.5 rounded-[0.375rem] bg-error-container/50 hover:bg-error-container text-error transition-colors cursor-pointer" onclick="openModal('delete', {kode:'SMN-001', nama:'Semen Gresik 50kg'})" title="Hapus Data"><span class="material-symbols-outlined text-[18px]">delete</span></button></div></td></tr><tr class="inventory-row hover:bg-surface-container-low/80 transition-colors" data-category="Besi &amp; Baja" data-text="bsi-013 besi ulir 13mm sni besi &amp; baja krakatau steel"><td class="py-3.5 px-3 text-center text-on-surface-variant font-semibold row-number">2</td><td class="py-3.5 px-3"><span class="font-mono text-xs font-bold text-on-surface bg-surface-container px-2 py-1 rounded border border-outline-variant/40">BSI-013</span></td><td class="py-3.5 px-4 font-bold text-on-surface">Besi Ulir 13mm SNI</td><td class="py-3.5 px-3"><span class="px-2 py-0.5 rounded-md bg-primary-fixed text-on-primary-fixed-variant font-semibold text-xs border border-primary-fixed-dim/50">Besi &amp; Baja</span></td><td class="py-3.5 px-3 text-on-surface-variant">Krakatau Steel</td><td class="py-3.5 px-4 text-right font-bold text-amber-600">15 <span class="font-normal text-xs text-on-surface-variant">Btg</span></td><td class="py-3.5 px-4 text-right text-on-surface-variant tabular-nums font-mono text-xs">Rp 108.000</td><td class="py-3.5 px-4 text-right font-bold text-on-surface tabular-nums font-mono text-xs">Rp 125.000</td><td class="py-3.5 px-3 text-center"><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 font-bold text-xs border border-amber-200"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Stok Menipis</span></td><td class="py-3.5 px-3 text-center"><div class="flex items-center justify-center gap-1.5"><button class="p-1.5 rounded-[0.375rem] bg-surface-container hover:bg-surface-container-high text-on-surface transition-colors cursor-pointer" onclick="openModal('edit', {kode:'BSI-013', nama:'Besi Ulir 13mm SNI', kategori:'Besi &amp; Baja', brand:'Krakatau Steel', stok:'15', satuan:'Btg', beli:'108000', jual:'125000'})" title="Edit Data"><span class="material-symbols-outlined text-[18px]">edit</span></button><button class="p-1.5 rounded-[0.375rem] bg-amber-50 hover:bg-amber-100 text-amber-700 transition-colors cursor-pointer" onclick="openModal('adjust', {kode:'BSI-013', nama:'Besi Ulir 13mm SNI', stok:'15', satuan:'Btg'})" title="Penyesuaian Stok"><span class="material-symbols-outlined text-[18px]">swap_vert</span></button><button class="p-1.5 rounded-[0.375rem] bg-error-container/50 hover:bg-error-container text-error transition-colors cursor-pointer" onclick="openModal('delete', {kode:'BSI-013', nama:'Besi Ulir 13mm SNI'})" title="Hapus Data"><span class="material-symbols-outlined text-[18px]">delete</span></button></div></td></tr><tr class="inventory-row hover:bg-surface-container-low/80 transition-colors" data-category="Cat &amp; Kimia" data-text="cat-044 cat avitex 5kg super white cat &amp; kimia avian brands"><td class="py-3.5 px-3 text-center text-on-surface-variant font-semibold row-number">3</td><td class="py-3.5 px-3"><span class="font-mono text-xs font-bold text-on-surface bg-surface-container px-2 py-1 rounded border border-outline-variant/40">CAT-044</span></td><td class="py-3.5 px-4 font-bold text-on-surface">Cat Avitex 5kg Super White</td><td class="py-3.5 px-3"><span class="px-2 py-0.5 rounded-md bg-secondary-fixed text-on-secondary-fixed-variant font-semibold text-xs border border-secondary-fixed-dim/50">Cat &amp; Kimia</span></td><td class="py-3.5 px-3 text-on-surface-variant">Avian Brands</td><td class="py-3.5 px-4 text-right font-bold text-error">0 <span class="font-normal text-xs text-on-surface-variant">Pail</span></td><td class="py-3.5 px-4 text-right text-on-surface-variant tabular-nums font-mono text-xs">Rp 89.000</td><td class="py-3.5 px-4 text-right font-bold text-on-surface tabular-nums font-mono text-xs">Rp 102.500</td><td class="py-3.5 px-3 text-center"><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-error-container/50 text-on-error-container font-bold text-xs border border-error/20"><span class="w-1.5 h-1.5 rounded-full bg-error"></span>Habis</span></td><td class="py-3.5 px-3 text-center"><div class="flex items-center justify-center gap-1.5"><button class="p-1.5 rounded-[0.375rem] bg-surface-container hover:bg-surface-container-high text-on-surface transition-colors cursor-pointer" onclick="openModal('edit', {kode:'CAT-044', nama:'Cat Avitex 5kg Super White', kategori:'Cat &amp; Kimia', brand:'Avian Brands', stok:'0', satuan:'Pail', beli:'89000', jual:'102500'})" title="Edit Data"><span class="material-symbols-outlined text-[18px]">edit</span></button><button class="p-1.5 rounded-[0.375rem] bg-amber-50 hover:bg-amber-100 text-amber-700 transition-colors cursor-pointer" onclick="openModal('adjust', {kode:'CAT-044', nama:'Cat Avitex 5kg Super White', stok:'0', satuan:'Pail'})" title="Penyesuaian Stok"><span class="material-symbols-outlined text-[18px]">swap_vert</span></button><button class="p-1.5 rounded-[0.375rem] bg-error-container/50 hover:bg-error-container text-error transition-colors cursor-pointer" onclick="openModal('delete', {kode:'CAT-044', nama:'Cat Avitex 5kg Super White'})" title="Hapus Data"><span class="material-symbols-outlined text-[18px]">delete</span></button></div></td></tr><tr class="inventory-row hover:bg-surface-container-low/80 transition-colors" data-category="Keramik &amp; Pasir" data-text="psr-002 pasir pasang 1 pick-up keramik &amp; pasir mandiri pasir"><td class="py-3.5 px-3 text-center text-on-surface-variant font-semibold row-number">4</td><td class="py-3.5 px-3"><span class="font-mono text-xs font-bold text-on-surface bg-surface-container px-2 py-1 rounded border border-outline-variant/40">PSR-002</span></td><td class="py-3.5 px-4 font-bold text-on-surface">Pasir Pasang 1 Pick-Up</td><td class="py-3.5 px-3"><span class="px-2 py-0.5 rounded-md bg-amber-50 text-amber-800 font-semibold text-xs border border-amber-200">Keramik &amp; Pasir</span></td><td class="py-3.5 px-3 text-on-surface-variant">Mandiri Pasir</td><td class="py-3.5 px-4 text-right font-bold text-on-surface">42 <span class="font-normal text-xs text-on-surface-variant">M³</span></td><td class="py-3.5 px-4 text-right text-on-surface-variant tabular-nums font-mono text-xs">Rp 320.000</td><td class="py-3.5 px-4 text-right font-bold text-on-surface tabular-nums font-mono text-xs">Rp 385.000</td><td class="py-3.5 px-3 text-center"><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold text-xs border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Tersedia</span></td><td class="py-3.5 px-3 text-center"><div class="flex items-center justify-center gap-1.5"><button class="p-1.5 rounded-[0.375rem] bg-surface-container hover:bg-surface-container-high text-on-surface transition-colors cursor-pointer" onclick="openModal('edit', {kode:'PSR-002', nama:'Pasir Pasang 1 Pick-Up', kategori:'Keramik &amp; Pasir', brand:'Mandiri Pasir', stok:'42', satuan:'M³', beli:'320000', jual:'385000'})" title="Edit Data"><span class="material-symbols-outlined text-[18px]">edit</span></button><button class="p-1.5 rounded-[0.375rem] bg-amber-50 hover:bg-amber-100 text-amber-700 transition-colors cursor-pointer" onclick="openModal('adjust', {kode:'PSR-002', nama:'Pasir Pasang 1 Pick-Up', stok:'42', satuan:'M³'})" title="Penyesuaian Stok"><span class="material-symbols-outlined text-[18px]">swap_vert</span></button><button class="p-1.5 rounded-[0.375rem] bg-error-container/50 hover:bg-error-container text-error transition-colors cursor-pointer" onclick="openModal('delete', {kode:'PSR-002', nama:'Pasir Pasang 1 Pick-Up'})" title="Hapus Data"><span class="material-symbols-outlined text-[18px]">delete</span></button></div></td></tr><tr class="inventory-row hover:bg-surface-container-low/80 transition-colors" data-category="Keramik &amp; Pasir" data-text="hbl-007 bata ringan hebel 7.5cm keramik &amp; pasir blesscon"><td class="py-3.5 px-3 text-center text-on-surface-variant font-semibold row-number">5</td><td class="py-3.5 px-3"><span class="font-mono text-xs font-bold text-on-surface bg-surface-container px-2 py-1 rounded border border-outline-variant/40">HBL-007</span></td><td class="py-3.5 px-4 font-bold text-on-surface">Bata Ringan Hebel 7.5cm</td><td class="py-3.5 px-3"><span class="px-2 py-0.5 rounded-md bg-amber-50 text-amber-800 font-semibold text-xs border border-amber-200">Keramik &amp; Pasir</span></td><td class="py-3.5 px-3 text-on-surface-variant">Blesscon</td><td class="py-3.5 px-4 text-right font-bold text-amber-600">8 <span class="font-normal text-xs text-on-surface-variant">M³</span></td><td class="py-3.5 px-4 text-right text-on-surface-variant tabular-nums font-mono text-xs">Rp 540.000</td><td class="py-3.5 px-4 text-right font-bold text-on-surface tabular-nums font-mono text-xs">Rp 610.000</td><td class="py-3.5 px-3 text-center"><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 font-bold text-xs border border-amber-200"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Stok Menipis</span></td><td class="py-3.5 px-3 text-center"><div class="flex items-center justify-center gap-1.5"><button class="p-1.5 rounded-[0.375rem] bg-surface-container hover:bg-surface-container-high text-on-surface transition-colors cursor-pointer" onclick="openModal('edit', {kode:'HBL-007', nama:'Bata Ringan Hebel 7.5cm', kategori:'Keramik &amp; Pasir', brand:'Blesscon', stok:'8', satuan:'M³', beli:'540000', jual:'610000'})" title="Edit Data"><span class="material-symbols-outlined text-[18px]">edit</span></button><button class="p-1.5 rounded-[0.375rem] bg-amber-50 hover:bg-amber-100 text-amber-700 transition-colors cursor-pointer" onclick="openModal('adjust', {kode:'HBL-007', nama:'Bata Ringan Hebel 7.5cm', stok:'8', satuan:'M³'})" title="Penyesuaian Stok"><span class="material-symbols-outlined text-[18px]">swap_vert</span></button><button class="p-1.5 rounded-[0.375rem] bg-error-container/50 hover:bg-error-container text-error transition-colors cursor-pointer" onclick="openModal('delete', {kode:'HBL-007', nama:'Bata Ringan Hebel 7.5cm'})" title="Hapus Data"><span class="material-symbols-outlined text-[18px]">delete</span></button></div></td></tr><tr class="hidden" id="noResultsRow"><td class="py-8 text-center text-on-surface-variant" colspan="10"><div class="flex flex-col items-center justify-center gap-2"><span class="material-symbols-outlined text-outline text-[32px]">inventory_2</span><span class="font-medium text-sm">Tidak ada data barang yang sesuai filter kategori ini</span></div></td></tr></tbody>
</table>
</div>
<!-- TAMPILAN 2: DAFTAR KARTU MOBILE (Mobile < md: block, Tablet & Desktop md+: hidden) -->
<div class="block md:hidden flex flex-col gap-3.5 w-full"><div class="inventory-card-mobile bg-surface-container-lowest border border-outline-variant/40 rounded-[0.75rem] p-3.5 shadow-sm flex flex-col gap-3" data-category="Semen" data-text="smn-001 semen gresik 50kg semen"><div class="flex items-start justify-between gap-2"><div class="flex items-start gap-2.5"><div class="w-8 h-8 rounded-[0.375rem] bg-tertiary-fixed text-on-tertiary-fixed-variant flex items-center justify-center shrink-0 mt-0.5"><span class="material-symbols-outlined text-[20px]">inventory</span></div><div><h3 class="font-bold text-on-surface text-sm leading-snug">Semen Gresik 50kg</h3><div class="flex items-center gap-2 mt-0.5"><span class="font-mono text-[11px] font-bold bg-surface-container px-1.5 py-0.5 rounded text-on-surface">SMN-001</span><span class="text-xs text-on-surface-variant">Semen • Semen Gresik</span></div></div></div><span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[11px] shrink-0 border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Tersedia</span></div><div class="grid grid-cols-3 gap-2 bg-surface-container-low p-2.5 rounded-[0.375rem] text-left border border-surface-container"><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-outline">Stok Fisik</span><span class="text-sm font-extrabold text-on-surface mt-0.5">240 <span class="text-xs font-normal text-on-surface-variant">Zak</span></span></div><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-outline">Harga Beli</span><span class="text-xs font-semibold text-on-surface-variant mt-0.5 font-mono">Rp 56.000</span></div><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-outline">Harga Jual</span><span class="text-xs font-bold text-on-surface mt-0.5 font-mono">Rp 64.000</span></div></div><div class="grid grid-cols-3 gap-2 pt-1"><button class="h-10 rounded-[0.375rem] bg-surface-container hover:bg-surface-container-high active:scale-95 text-on-surface font-semibold text-xs flex items-center justify-center gap-1 transition-all" onclick="openModal('edit', {kode:'SMN-001', nama:'Semen Gresik 50kg', kategori:'Semen', brand:'Semen Gresik', stok:'240', satuan:'Zak', beli:'56000', jual:'64000'})"><span class="material-symbols-outlined text-[16px]">edit</span><span class="">Edit</span></button><button class="h-10 rounded-[0.375rem] bg-amber-50 hover:bg-amber-100 active:scale-95 text-amber-800 font-semibold text-xs flex items-center justify-center gap-1 transition-all border border-amber-200/50" onclick="openModal('adjust', {kode:'SMN-001', nama:'Semen Gresik 50kg', stok:'240', satuan:'Zak'})"><span class="material-symbols-outlined text-[16px]">swap_vert</span><span class="">Sesuaikan</span></button><button class="h-10 rounded-[0.375rem] bg-error-container/50 hover:bg-error-container active:scale-95 text-error font-semibold text-xs flex items-center justify-center gap-1 transition-all" onclick="openModal('delete', {kode:'SMN-001', nama:'Semen Gresik 50kg'})"><span class="material-symbols-outlined text-[16px]">delete</span><span class="">Hapus</span></button></div></div><div class="inventory-card-mobile bg-surface-container-lowest border border-amber-200/80 rounded-[0.75rem] p-3.5 shadow-sm flex flex-col gap-3" data-category="Besi &amp; Baja" data-text="bsi-013 besi ulir 13mm sni besi &amp; baja krakatau steel"><div class="flex items-start justify-between gap-2"><div class="flex items-start gap-2.5"><div class="w-8 h-8 rounded-[0.375rem] bg-primary-fixed text-on-primary-fixed-variant flex items-center justify-center shrink-0 mt-0.5"><span class="material-symbols-outlined text-[20px]">architecture</span></div><div><h3 class="font-bold text-on-surface text-sm leading-snug">Besi Ulir 13mm SNI</h3><div class="flex items-center gap-2 mt-0.5"><span class="font-mono text-[11px] font-bold bg-surface-container px-1.5 py-0.5 rounded text-on-surface">BSI-013</span><span class="text-xs text-on-surface-variant">Besi &amp; Baja • Krakatau Steel</span></div></div></div><span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 font-bold text-[11px] shrink-0 border border-amber-200"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Menipis</span></div><div class="grid grid-cols-3 gap-2 bg-amber-50/50 p-2.5 rounded-[0.375rem] text-left border border-amber-100/60"><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-amber-700">Stok Fisik</span><span class="text-sm font-extrabold text-amber-600 mt-0.5">15 <span class="text-xs font-normal text-on-surface-variant">Btg</span></span></div><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-outline">Harga Beli</span><span class="text-xs font-semibold text-on-surface-variant mt-0.5 font-mono">Rp 108.000</span></div><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-outline">Harga Jual</span><span class="text-xs font-bold text-on-surface mt-0.5 font-mono">Rp 125.000</span></div></div><div class="grid grid-cols-3 gap-2 pt-1"><button class="h-10 rounded-[0.375rem] bg-surface-container hover:bg-surface-container-high active:scale-95 text-on-surface font-semibold text-xs flex items-center justify-center gap-1 transition-all" onclick="openModal('edit', {kode:'BSI-013', nama:'Besi Ulir 13mm SNI', kategori:'Besi &amp; Baja', brand:'Krakatau Steel', stok:'15', satuan:'Btg', beli:'108000', jual:'125000'})"><span class="material-symbols-outlined text-[16px]">edit</span><span class="">Edit</span></button><button class="h-10 rounded-[0.375rem] bg-amber-50 hover:bg-amber-100 active:scale-95 text-amber-800 font-semibold text-xs flex items-center justify-center gap-1 transition-all border border-amber-200/50" onclick="openModal('adjust', {kode:'BSI-013', nama:'Besi Ulir 13mm SNI', stok:'15', satuan:'Btg'})"><span class="material-symbols-outlined text-[16px]">swap_vert</span><span class="">Sesuaikan</span></button><button class="h-10 rounded-[0.375rem] bg-error-container/50 hover:bg-error-container active:scale-95 text-error font-semibold text-xs flex items-center justify-center gap-1 transition-all" onclick="openModal('delete', {kode:'BSI-013', nama:'Besi Ulir 13mm SNI'})"><span class="material-symbols-outlined text-[16px]">delete</span><span class="">Hapus</span></button></div></div><div class="inventory-card-mobile bg-surface-container-lowest border border-error/20 rounded-[0.75rem] p-3.5 shadow-sm flex flex-col gap-3" data-category="Cat &amp; Kimia" data-text="cat-044 cat avitex 5kg super white cat &amp; kimia avian brands"><div class="flex items-start justify-between gap-2"><div class="flex items-start gap-2.5"><div class="w-8 h-8 rounded-[0.375rem] bg-error-container/50 text-on-error-container flex items-center justify-center shrink-0 mt-0.5"><span class="material-symbols-outlined text-[20px]">format_paint</span></div><div><h3 class="font-bold text-on-surface text-sm leading-snug">Cat Avitex 5kg Super White</h3><div class="flex items-center gap-2 mt-0.5"><span class="font-mono text-[11px] font-bold bg-surface-container px-1.5 py-0.5 rounded text-on-surface">CAT-044</span><span class="text-xs text-on-surface-variant">Cat &amp; Kimia • Avian Brands</span></div></div></div><span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-error-container/50 text-on-error-container font-bold text-[11px] shrink-0 border border-error/20"><span class="w-1.5 h-1.5 rounded-full bg-error"></span>Habis</span></div><div class="grid grid-cols-3 gap-2 bg-error-container/30 p-2.5 rounded-[0.375rem] text-left border border-error/10"><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-error">Stok Fisik</span><span class="text-sm font-extrabold text-error mt-0.5">0 <span class="text-xs font-normal text-on-surface-variant">Pail</span></span></div><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-outline">Harga Beli</span><span class="text-xs font-semibold text-on-surface-variant mt-0.5 font-mono">Rp 89.000</span></div><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-outline">Harga Jual</span><span class="text-xs font-bold text-on-surface mt-0.5 font-mono">Rp 102.500</span></div></div><div class="grid grid-cols-3 gap-2 pt-1"><button class="h-10 rounded-[0.375rem] bg-surface-container hover:bg-surface-container-high active:scale-95 text-on-surface font-semibold text-xs flex items-center justify-center gap-1 transition-all" onclick="openModal('edit', {kode:'CAT-044', nama:'Cat Avitex 5kg Super White', kategori:'Cat &amp; Kimia', brand:'Avian Brands', stok:'0', satuan:'Pail', beli:'89000', jual:'102500'})"><span class="material-symbols-outlined text-[16px]">edit</span><span class="">Edit</span></button><button class="h-10 rounded-[0.375rem] bg-amber-50 hover:bg-amber-100 active:scale-95 text-amber-800 font-semibold text-xs flex items-center justify-center gap-1 transition-all border border-amber-200/50" onclick="openModal('adjust', {kode:'CAT-044', nama:'Cat Avitex 5kg Super White', stok:'0', satuan:'Pail'})"><span class="material-symbols-outlined text-[16px]">swap_vert</span><span class="">Restok</span></button><button class="h-10 rounded-[0.375rem] bg-error-container/50 hover:bg-error-container active:scale-95 text-error font-semibold text-xs flex items-center justify-center gap-1 transition-all" onclick="openModal('delete', {kode:'CAT-044', nama:'Cat Avitex 5kg Super White'})"><span class="material-symbols-outlined text-[16px]">delete</span><span class="">Hapus</span></button></div></div><div class="inventory-card-mobile bg-surface-container-lowest border border-outline-variant/40 rounded-[0.75rem] p-3.5 shadow-sm flex flex-col gap-3" data-category="Keramik &amp; Pasir" data-text="psr-002 pasir pasang 1 pick-up keramik &amp; pasir mandiri pasir"><div class="flex items-start justify-between gap-2"><div class="flex items-start gap-2.5"><div class="w-8 h-8 rounded-[0.375rem] bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 mt-0.5"><span class="material-symbols-outlined text-[20px]">layers</span></div><div><h3 class="font-bold text-on-surface text-sm leading-snug">Pasir Pasang 1 Pick-Up</h3><div class="flex items-center gap-2 mt-0.5"><span class="font-mono text-[11px] font-bold bg-surface-container px-1.5 py-0.5 rounded text-on-surface">PSR-002</span><span class="text-xs text-on-surface-variant">Keramik &amp; Pasir • Mandiri Pasir</span></div></div></div><span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[11px] shrink-0 border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Tersedia</span></div><div class="grid grid-cols-3 gap-2 bg-surface-container-low p-2.5 rounded-[0.375rem] text-left border border-surface-container"><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-outline">Stok Fisik</span><span class="text-sm font-extrabold text-on-surface mt-0.5">42 <span class="text-xs font-normal text-on-surface-variant">M³</span></span></div><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-outline">Harga Beli</span><span class="text-xs font-semibold text-on-surface-variant mt-0.5 font-mono">Rp 320.000</span></div><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-outline">Harga Jual</span><span class="text-xs font-bold text-on-surface mt-0.5 font-mono">Rp 385.000</span></div></div><div class="grid grid-cols-3 gap-2 pt-1"><button class="h-10 rounded-[0.375rem] bg-surface-container hover:bg-surface-container-high active:scale-95 text-on-surface font-semibold text-xs flex items-center justify-center gap-1 transition-all" onclick="openModal('edit', {kode:'PSR-002', nama:'Pasir Pasang 1 Pick-Up', kategori:'Keramik &amp; Pasir', brand:'Mandiri Pasir', stok:'42', satuan:'M³', beli:'320000', jual:'385000'})"><span class="material-symbols-outlined text-[16px]">edit</span><span class="">Edit</span></button><button class="h-10 rounded-[0.375rem] bg-amber-50 hover:bg-amber-100 active:scale-95 text-amber-800 font-semibold text-xs flex items-center justify-center gap-1 transition-all border border-amber-200/50" onclick="openModal('adjust', {kode:'PSR-002', nama:'Pasir Pasang 1 Pick-Up', stok:'42', satuan:'M³'})"><span class="material-symbols-outlined text-[16px]">swap_vert</span><span class="">Sesuaikan</span></button><button class="h-10 rounded-[0.375rem] bg-error-container/50 hover:bg-error-container active:scale-95 text-error font-semibold text-xs flex items-center justify-center gap-1 transition-all" onclick="openModal('delete', {kode:'PSR-002', nama:'Pasir Pasang 1 Pick-Up'})"><span class="material-symbols-outlined text-[16px]">delete</span><span class="">Hapus</span></button></div></div><div class="inventory-card-mobile bg-surface-container-lowest border border-amber-200/80 rounded-[0.75rem] p-3.5 shadow-sm flex flex-col gap-3" data-category="Keramik &amp; Pasir" data-text="hbl-007 bata ringan hebel 7.5cm keramik &amp; pasir blesscon"><div class="flex items-start justify-between gap-2"><div class="flex items-start gap-2.5"><div class="w-8 h-8 rounded-[0.375rem] bg-amber-50 text-amber-800 flex items-center justify-center shrink-0 mt-0.5"><span class="material-symbols-outlined text-[20px]">view_in_ar</span></div><div><h3 class="font-bold text-on-surface text-sm leading-snug">Bata Ringan Hebel 7.5cm</h3><div class="flex items-center gap-2 mt-0.5"><span class="font-mono text-[11px] font-bold bg-surface-container px-1.5 py-0.5 rounded text-on-surface">HBL-007</span><span class="text-xs text-on-surface-variant">Keramik &amp; Pasir • Blesscon</span></div></div></div><span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 font-bold text-[11px] shrink-0 border border-amber-200"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Menipis</span></div><div class="grid grid-cols-3 gap-2 bg-amber-50/50 p-2.5 rounded-[0.375rem] text-left border border-amber-100/60"><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-amber-700">Stok Fisik</span><span class="text-sm font-extrabold text-amber-600 mt-0.5">8 <span class="text-xs font-normal text-on-surface-variant">M³</span></span></div><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-outline">Harga Beli</span><span class="text-xs font-semibold text-on-surface-variant mt-0.5 font-mono">Rp 540.000</span></div><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-outline">Harga Jual</span><span class="text-xs font-bold text-on-surface mt-0.5 font-mono">Rp 610.000</span></div></div><div class="grid grid-cols-3 gap-2 pt-1"><button class="h-10 rounded-[0.375rem] bg-surface-container hover:bg-surface-container-high active:scale-95 text-on-surface font-semibold text-xs flex items-center justify-center gap-1 transition-all" onclick="openModal('edit', {kode:'HBL-007', nama:'Bata Ringan Hebel 7.5cm', kategori:'Keramik &amp; Pasir', brand:'Blesscon', stok:'8', satuan:'M³', beli:'540000', jual:'610000'})"><span class="material-symbols-outlined text-[16px]">edit</span><span class="">Edit</span></button><button class="h-10 rounded-[0.375rem] bg-amber-50 hover:bg-amber-100 active:scale-95 text-amber-800 font-semibold text-xs flex items-center justify-center gap-1 transition-all border border-amber-200/50" onclick="openModal('adjust', {kode:'HBL-007', nama:'Bata Ringan Hebel 7.5cm', stok:'8', satuan:'M³'})"><span class="material-symbols-outlined text-[16px]">swap_vert</span><span class="">Sesuaikan</span></button><button class="h-10 rounded-[0.375rem] bg-error-container/50 hover:bg-error-container active:scale-95 text-error font-semibold text-xs flex items-center justify-center gap-1 transition-all" onclick="openModal('delete', {kode:'HBL-007', nama:'Bata Ringan Hebel 7.5cm'})"><span class="material-symbols-outlined text-[16px]">delete</span><span class="">Hapus</span></button></div></div><div class="hidden text-center py-6 text-on-surface-variant" id="noResultsMobile"><span class="material-symbols-outlined text-outline text-[28px]">inventory_2</span><p class="text-xs font-medium mt-1">Tidak ada data barang yang sesuai</p></div></div>
<!-- Responsive Pagination & Footer Table Controls -->
<div class="flex flex-col md:flex-row items-center justify-between gap-3 pt-4 border-t border-surface-container text-on-surface-variant text-xs sm:text-sm" id="paginationContainer">
<div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 text-on-surface-variant"><span class="">Menampilkan <span class="font-bold text-on-surface font-mono" id="pageInfoRange">1 - 5</span> dari <span class="font-bold text-on-surface font-mono" id="pageTotalCount">840</span> barang</span><span class="hidden sm:inline-block text-outline-variant">•</span><span class="hidden sm:inline-flex items-center px-2 py-0.5 rounded bg-surface-container font-medium text-[11px] text-on-surface-variant">Halaman <span class="font-bold text-on-surface ml-1" id="pageCurrentBadge">1</span> / <span id="pageTotalPages" class="">84</span></span></div>
<nav aria-label="Navigasi Halaman" class="flex items-center flex-wrap justify-center gap-1 sm:gap-1.5 select-none">
<button class="h-8 px-2.5 sm:px-3 rounded-[0.375rem] bg-surface-container hover:bg-surface-container-high active:scale-95 text-on-surface-variant font-semibold text-xs flex items-center gap-1 transition-all disabled:opacity-40 disabled:cursor-not-allowed" disabled="" id="btnPrevPage" onclick="goToPage(currentPage - 1)">
<span class="material-symbols-outlined text-[16px]">chevron_left</span>
<span class="hidden sm:inline">Sebelumnya</span>
</button>
<div class="flex items-center gap-1" id="paginationButtons">
<button class="page-btn w-8 h-8 rounded-[0.375rem] bg-primary-container text-on-primary font-bold text-xs flex items-center justify-center shadow-sm transition-all" data-page="1" onclick="goToPage(1)">1</button>
<button class="page-btn w-8 h-8 rounded-[0.375rem] hover:bg-surface-container text-on-surface font-medium text-xs flex items-center justify-center transition-colors" data-page="2" onclick="goToPage(2)">2</button>
<button class="page-btn w-8 h-8 rounded-[0.375rem] hover:bg-surface-container text-on-surface font-medium text-xs flex items-center justify-center transition-colors" data-page="3" onclick="goToPage(3)">3</button>
<span class="px-1 text-outline text-xs font-mono">...</span>
<button class="page-btn w-8 h-8 rounded-[0.375rem] hover:bg-surface-container text-on-surface font-medium text-xs flex items-center justify-center transition-colors" data-page="84" onclick="goToPage(84)">84</button>
</div>
<button class="h-8 px-2.5 sm:px-3 rounded-[0.375rem] bg-surface-container hover:bg-surface-container-high active:scale-95 text-on-surface font-semibold text-xs flex items-center gap-1 transition-all" id="btnNextPage" onclick="goToPage(currentPage + 1)">
<span class="hidden sm:inline">Selanjutnya</span>
<span class="material-symbols-outlined text-[16px]">chevron_right</span>
</button>
</nav>
</div>
</div>
</div>
</div>
@endsection

@push('modals')
<!-- ========================================== -->
<!-- MODAL 1: Tambah Data Barang (Responsive) -->
<!-- ========================================== -->
<div class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex flex-col items-stretch md:items-center md:justify-center p-3 sm:p-4 pt-16 md:pt-4 overflow-y-auto hidden" id="modalAdd"><div class="bg-surface-container-lowest rounded-2xl shadow-2xl w-full max-w-md md:max-w-2xl mx-auto md:my-auto max-h-[85vh] md:max-h-[90vh] flex flex-col border border-outline-variant/40 animate-in fade-in zoom-in duration-150"><div class="p-3.5 sm:p-5 md:p-6 border-b border-surface-container flex items-center justify-between sticky top-0 bg-surface-container-lowest rounded-t-2xl z-10"><div class="flex items-center gap-2.5 sm:gap-3"><div class="w-8 h-8 sm:w-10 sm:h-10 rounded-[0.75rem] bg-secondary-fixed border border-secondary-fixed-dim/50 flex items-center justify-center text-secondary shrink-0"><span class="material-symbols-outlined text-[20px] sm:text-[24px]">add_box</span></div><div><h3 class="font-bold text-on-surface text-sm sm:text-base md:text-lg">Tambah Data Barang</h3><p class="text-[11px] sm:text-xs text-on-surface-variant">Detail spesifikasi SKU material baru</p></div></div><button class="text-outline hover:text-on-surface p-1 sm:p-1.5 rounded-[0.375rem] hover:bg-surface-container transition-colors cursor-pointer" onclick="closeModal('add')"><span class="material-symbols-outlined text-[18px] sm:text-[20px]">close</span></button></div><form class="p-3.5 sm:p-5 md:p-6 flex flex-col gap-3 sm:gap-4 overflow-y-auto" onsubmit="event.preventDefault(); closeModal('add'); alert('Barang berhasil ditambahkan ke inventaris!');"><div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-4"><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Kode Barang (SKU)</label><input class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-1.5 sm:py-2 rounded-[0.375rem] font-mono text-xs sm:text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all" placeholder="e.g. SMN-005" required="" type="text"></div><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Kategori</label><select class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-1.5 sm:py-2 rounded-[0.375rem] text-xs sm:text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all cursor-pointer" required=""><option value="">Pilih Kategori</option><option value="Semen">Semen</option><option value="Besi &amp; Baja">Besi &amp; Baja</option><option value="Cat &amp; Kimia">Cat &amp; Kimia</option><option value="Keramik &amp; Pasir">Keramik &amp; Pasir</option></select></div></div><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Nama Barang Lengkap</label><input class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-1.5 sm:py-2 rounded-[0.375rem] text-xs sm:text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all" placeholder="e.g. Semen Tiga Roda 50kg" required="" type="text"></div><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Brand / Pabrikan</label><input class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-1.5 sm:py-2 rounded-[0.375rem] text-xs sm:text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all" placeholder="e.g. Indocement" required="" type="text"></div><div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-4"><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Jumlah Stok Awal</label><input class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-1.5 sm:py-2 rounded-[0.375rem] text-xs sm:text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 font-mono transition-all" min="0" placeholder="0" required="" type="number"></div><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Satuan Unit</label><select class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-1.5 sm:py-2 rounded-[0.375rem] text-xs sm:text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all cursor-pointer" required=""><option value="Zak">Zak</option><option value="Btg">Btg (Batang)</option><option value="Pail">Pail</option><option value="M³">M³ (Meter Kubik)</option><option value="Dus">Dus</option></select></div></div><div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-4"><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Harga Beli / Modal (Rp)</label><input class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-1.5 sm:py-2 rounded-[0.375rem] font-mono text-xs sm:text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all" min="0" placeholder="50000" required="" type="number"></div><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Harga Jual Normal (Rp)</label><input class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-1.5 sm:py-2 rounded-[0.375rem] font-mono text-xs sm:text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all" min="0" placeholder="60000" required="" type="number"></div></div><div class="flex items-center justify-end gap-2 pt-2.5 sm:pt-3 border-t border-surface-container"><button class="px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-[0.75rem] bg-surface-container hover:bg-surface-container-high text-on-surface font-semibold text-xs sm:text-sm transition-colors cursor-pointer" onclick="closeModal('add')" type="button">Batal</button><button class="px-4 sm:px-5 py-2 sm:py-2.5 rounded-[0.75rem] bg-secondary hover:bg-secondary-container text-on-primary font-semibold text-xs sm:text-sm shadow-sm shadow-secondary/30 transition-all cursor-pointer" type="submit">Simpan Barang</button></div></form></div></div>
<!-- ========================================== -->
<!-- MODAL 2: Edit Data Barang (Responsive) -->
<!-- ========================================== -->
<div class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-3.5 sm:p-4 overflow-y-auto" id="modalEdit">
<div class="bg-surface-container-lowest rounded-2xl shadow-2xl max-w-lg w-full max-h-[92vh] flex flex-col my-auto border border-outline-variant/40 animate-in fade-in zoom-in duration-150">
<div class="p-4 sm:p-5 border-b border-surface-container flex items-center justify-between sticky top-0 bg-surface-container-lowest rounded-t-2xl z-10">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-[0.75rem] bg-tertiary-fixed border border-tertiary-fixed-dim/40 flex items-center justify-center text-on-tertiary-fixed-variant shrink-0">
<span class="material-symbols-outlined text-[24px]">edit_note</span>
</div>
<div>
<h3 class="font-bold text-on-surface text-base sm:text-lg">Edit Data Barang</h3>
<p class="text-xs text-on-surface-variant">Perbarui spesifikasi, harga beli, atau harga jual</p>
</div>
</div>
<button class="text-outline hover:text-on-surface p-1.5 rounded-[0.375rem] hover:bg-surface-container transition-colors" onclick="closeModal('edit')">
<span class="material-symbols-outlined text-[20px]">close</span>
</button>
</div>
<form class="p-4 sm:p-5 flex flex-col gap-4 overflow-y-auto" onsubmit="event.preventDefault(); closeModal('edit'); alert('Perubahan barang berhasil disimpan!');">
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
<div class="flex flex-col gap-1.5">
<label class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Kode Barang (SKU)</label>
<input class="bg-surface-container border border-outline-variant/40 text-on-surface-variant px-3 py-2 rounded-[0.375rem] font-mono text-sm cursor-not-allowed" id="editKode" readonly="" type="text">
</div>
<div class="flex flex-col gap-1.5">
<label class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Kategori</label>
<input class="bg-surface-container-low border border-outline-variant/40 focus:bg-surface-container-lowest focus:border-secondary px-3 py-2 rounded-[0.375rem] text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all" id="editKategori" type="text">
</div>
</div>
<div class="flex flex-col gap-1.5">
<label class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Nama Barang</label>
<input class="bg-surface-container-low border border-outline-variant/40 focus:bg-surface-container-lowest focus:border-secondary px-3 py-2 rounded-[0.375rem] text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all" id="editNama" required="" type="text">
</div>
<div class="flex flex-col gap-1.5">
<label class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Brand / Manufaktur</label>
<input class="bg-surface-container-low border border-outline-variant/40 focus:bg-surface-container-lowest focus:border-secondary px-3 py-2 rounded-[0.375rem] text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all" id="editBrand" required="" type="text">
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
<div class="flex flex-col gap-1.5">
<label class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Stok Fisik</label>
<input class="bg-surface-container-low border border-outline-variant/40 focus:bg-surface-container-lowest focus:border-secondary px-3 py-2 rounded-[0.375rem] font-mono text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all" id="editStok" min="0" required="" type="number">
</div>
<div class="flex flex-col gap-1.5">
<label class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Satuan Unit</label>
<select class="bg-surface-container-low border border-outline-variant/40 focus:bg-surface-container-lowest focus:border-secondary px-3 py-2 rounded-[0.375rem] text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all" id="editSatuan">
<option value="Zak">Zak</option>
<option value="Btg">Btg</option>
<option value="Pail">Pail</option>
<option value="M³">M³</option>
<option value="Dus">Dus</option>
</select>
</div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
<div class="flex flex-col gap-1.5">
<label class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Harga Beli (Rp)</label>
<input class="bg-surface-container-low border border-outline-variant/40 focus:bg-surface-container-lowest focus:border-secondary px-3 py-2 rounded-[0.375rem] font-mono text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all" id="editBeli" min="0" required="" type="number">
</div>
<div class="flex flex-col gap-1.5">
<label class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Harga Jual (Rp)</label>
<input class="bg-surface-container-low border border-outline-variant/40 focus:bg-surface-container-lowest focus:border-secondary px-3 py-2 rounded-[0.375rem] font-mono text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all" id="editJual" min="0" required="" type="number">
</div>
</div>
<div class="flex items-center justify-end gap-2.5 pt-3 border-t border-surface-container">
<button class="px-4 py-2.5 rounded-[0.75rem] bg-surface-container hover:bg-surface-container-high text-on-surface font-semibold text-xs sm:text-sm transition-colors cursor-pointer" onclick="closeModal('edit')" type="button">
          Batal
        </button>
<button class="px-5 py-2.5 rounded-[0.75rem] bg-primary-container hover:bg-primary-container text-on-primary font-semibold text-xs sm:text-sm shadow-sm transition-all cursor-pointer" type="submit">
          Perbarui Data
        </button>
</div>
</form>
</div>
</div>
<!-- ========================================== -->
<!-- MODAL 3: Penyesuaian / Stock Adjustment Modal -->
<!-- ========================================== -->
<div class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-3.5 sm:p-4 overflow-y-auto hidden" id="modalAdjust">
<div class="bg-surface-container-lowest rounded-2xl shadow-2xl max-w-md w-full p-4 sm:p-6 my-auto border border-outline-variant/40 animate-in fade-in zoom-in duration-150">
<div class="flex items-center gap-3 mb-4">
<div class="w-10 h-10 rounded-[0.75rem] bg-amber-50 border border-amber-200/80 flex items-center justify-center text-amber-700 shrink-0">
<span class="material-symbols-outlined text-[24px]">swap_vert</span>
</div>
<div>
<h3 class="font-bold text-on-surface text-base sm:text-lg">Penyesuaian Stok Fisik</h3>
<p class="text-xs text-on-surface-variant" id="adjustTargetText">SMN-001 - Semen Gresik 50kg</p>
</div>
</div>
<form class="flex flex-col gap-3.5" onsubmit="event.preventDefault(); closeModal('adjust'); alert('Penyesuaian stok berhasil dicatat ke kartu stok!');">
<div class="bg-surface-container-low p-3 rounded-[0.75rem] border border-outline-variant/40 flex items-center justify-between">
<span class="text-xs text-on-surface-variant font-medium">Stok Sistem Saat Ini:</span>
<span class="text-sm font-bold text-on-surface font-mono" id="adjustCurrentStock">240 Zak</span>
</div>
<div class="flex flex-col gap-1.5">
<label class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Jenis Penyesuaian</label>
<div class="grid grid-cols-2 gap-2">
<label class="flex items-center gap-2 p-2.5 rounded-[0.375rem] border border-outline-variant/40 hover:bg-surface-container-low cursor-pointer">
<input checked="" class="text-secondary focus:ring-secondary" name="adjustType" type="radio" value="masuk">
<span class="text-xs font-semibold text-on-surface">Barang Masuk (+)</span>
</label>
<label class="flex items-center gap-2 p-2.5 rounded-[0.375rem] border border-outline-variant/40 hover:bg-surface-container-low cursor-pointer">
<input class="text-secondary focus:ring-secondary" name="adjustType" type="radio" value="keluar">
<span class="text-xs font-semibold text-on-surface">Koreksi / Rusak (-)</span>
</label>
</div>
</div>
<div class="flex flex-col gap-1.5">
<label class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Jumlah Perubahan</label>
<input class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-2 rounded-[0.375rem] text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 font-mono" min="1" placeholder="Contoh: 10" required="" type="number">
</div>
<div class="flex flex-col gap-1.5">
<label class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Catatan / Alasan</label>
<textarea class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-2 rounded-[0.375rem] text-xs text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20" placeholder="Restok supplier, salah hitung opname..." rows="2"></textarea>
</div>
<div class="flex items-center justify-end gap-2.5 pt-3 border-t border-surface-container">
<button class="px-4 py-2.5 rounded-[0.75rem] bg-surface-container hover:bg-surface-container-high text-on-surface font-semibold text-xs transition-colors cursor-pointer" onclick="closeModal('adjust')" type="button">
          Batal
        </button>
<button class="px-5 py-2.5 rounded-[0.75rem] bg-amber-600 hover:bg-amber-700 text-on-primary font-semibold text-xs shadow-sm transition-all cursor-pointer" type="submit">
          Simpan Penyesuaian
        </button>
</div>
</form>
</div>
</div>
<!-- ========================================== -->
<!-- MODAL 4: Konfirmasi Hapus Data (Responsive) -->
<!-- ========================================== -->
<div class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-3.5 sm:p-4" id="modalDelete">
<div class="bg-surface-container-lowest rounded-2xl shadow-2xl max-w-md w-full p-5 sm:p-6 my-auto border border-outline-variant/40 animate-in fade-in zoom-in duration-150">
<div class="flex items-center gap-3 mb-3">
<div class="w-10 h-10 rounded-[0.75rem] bg-error-container flex items-center justify-center text-error shrink-0">
<span class="material-symbols-outlined text-[24px]">warning</span>
</div>
<div>
<h3 class="font-bold text-on-surface text-base sm:text-lg">Hapus Data Barang?</h3>
<p class="text-xs font-semibold text-on-surface-variant" id="deleteTargetText">SKU: SMN-001</p>
</div>
</div>
<p class="text-xs sm:text-sm text-on-surface-variant mb-5 leading-relaxed">
      Apakah Anda yakin ingin menghapus barang ini dari database sistem? Tindakan ini tidak dapat dibatalkan dan akan mempengaruhi riwayat inventaris terkait.
    </p>
<div class="flex items-center justify-end gap-2.5">
<button class="px-4 py-2.5 rounded-[0.75rem] bg-surface-container hover:bg-surface-container-high text-on-surface font-semibold text-xs sm:text-sm transition-colors cursor-pointer" onclick="closeModal('delete')" type="button">
        Batal
      </button>
<button class="px-5 py-2.5 rounded-[0.75rem] bg-error hover:bg-error/90 text-on-primary font-semibold text-xs sm:text-sm transition-all shadow-sm shadow-error/30 cursor-pointer" onclick="confirmDeleteAction()" type="button">
        Ya, Hapus Data
      </button>
</div>
</div>
</div>
<!-- Interactive JS Handlers -->
@endpush