<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>Data Stok Barang - Aura Material</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

  @vite(['resources/css/stok.css', 'resources/js/laporanstok.js'])

  <style>@layer base{html,body{margin:0;padding:0;overflow-x:hidden;}body{overscroll-behavior:none;}main>:first-child{margin-top:0!important;}main>:last-child{margin-bottom:0!important;}}::-webkit-scrollbar{display:none;}</style>
</head>
<body class="bg-surface font-body-md text-slate-800 antialiased overflow-x-hidden min-h-screen w-full selection:bg-secondary-container selection:text-white overflow-y-auto" style="min-height: 100vh;"><!-- Mobile & Tablet Backdrop Overlay -->
<div class="fixed inset-0 bg-inverse-surface/60 backdrop-blur-sm z-40 transition-opacity duration-300 hidden" id="sidebarBackdrop" onclick="toggleMobileSidebar()"></div>
<!-- Drawer Sidebar: Always visible on Desktop (lg >= 1024px), toggleable on Mobile/Tablet -->
<aside class="fixed left-0 top-0 h-full w-64 bg-primary text-on-primary z-50 flex flex-col justify-between shadow-2xl lg:shadow-none transform lg:translate-x-0 transition-transform duration-300 ease-in-out overflow-y-auto -translate-x-full" id="sidebarDrawer">
<div class="flex flex-col">
<div class="h-16 px-gutter flex items-center justify-between border-b border-white/10 bg-primary-container/30">
<div class="flex items-center gap-space-sm">
<div class="w-8 h-8 rounded-lg bg-secondary-container flex items-center justify-center shrink-0 shadow-md">
<span class="material-symbols-outlined text-white text-[20px]">construction</span>
</div>
<div class="flex flex-col">
<span class="font-title-md text-title-md tracking-tight uppercase text-white font-bold leading-tight">Aura Material</span>
<span class="font-label-md text-label-md text-on-primary-container">DEPOT &amp; SUPPLY</span>
</div>
</div>
<button aria-label="Tutup menu" class="lg:hidden text-on-primary-container hover:text-white p-1 rounded-lg transition-colors" onclick="toggleMobileSidebar()">
<span class="material-symbols-outlined text-[22px]">close</span>
</button>
</div>
<div class="px-gutter pt-space-lg pb-space-xs">

</div>
<nav class="flex flex-col gap-1 px-space-md mt-1" id="sidebarNav">


<a href="{{ route('admin.laporanstok') }}" data-path="data-stok-barang" class="{{ request()->routeIs('admin.laporanstok') ? 'sidebar-item flex items-center gap-space-md px-3 py-2.5 rounded-lg bg-secondary-container text-white font-bold shadow-sm transition-all' : 'sidebar-item flex items-center gap-space-md px-3 py-2.5 rounded-lg text-on-primary-container hover:bg-white/10 hover:text-white transition-all font-body-md text-body-md' }}" @if(request()->routeIs('admin.laporanstok')) aria-current="page" @endif>
<span class="material-symbols-outlined text-[20px]">inventory_2</span>
<span class="">Data Stok Barang</span>
</a>
<a href="{{ route('admin.riwayatbulanan') }}" data-path="riwayat-nota" class="{{ request()->routeIs('admin.riwayatbulanan') ? 'sidebar-item flex items-center gap-space-md px-3 py-2.5 rounded-lg bg-secondary-container text-white font-bold shadow-sm transition-all' : 'sidebar-item flex items-center gap-space-md px-3 py-2.5 rounded-lg text-on-primary-container hover:bg-white/10 hover:text-white transition-all font-body-md text-body-md' }}" @if(request()->routeIs('admin.riwayatbulanan')) aria-current="page" @endif>
<span class="material-symbols-outlined text-[20px]">receipt_long</span>
<span class="font-title-md text-title-md">Riwayat Nota</span>
</a>
<a href="{{ route('admin.dashboard') }}" data-path="laporan-keuangan" class="{{ request()->routeIs('admin.dashboard') ? 'sidebar-item flex items-center gap-space-md px-3 py-2.5 rounded-lg bg-secondary-container text-white font-bold shadow-sm transition-all' : 'sidebar-item flex items-center gap-space-md px-3 py-2.5 rounded-lg text-on-primary-container hover:bg-white/10 hover:text-white transition-all font-body-md text-body-md' }}" @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif>
<span class="material-symbols-outlined text-[20px]">analytics</span>
<span class="">Laporan Keuangan</span>
</a>

</nav>
</div>
<div class="p-4 bg-primary-container/50 border-t border-white/5">
<div class="flex items-center justify-between">
<div class="flex items-center gap-2">
<span class="relative flex h-2.5 w-2.5">
<span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
<span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
</span>
<span class="font-label-md text-label-md text-slate-300">Depot v2.4 Online</span>
</div>
<span class="text-xs px-2 py-0.5 rounded bg-white/10 text-slate-300 font-mono">Sync OK</span>
</div>
</div>
</aside>
<!-- Main Wrapper -->
<div class="pl-0 lg:pl-64 flex flex-col min-h-screen w-full transition-[padding] duration-300">
<!-- Navbar Header -->
<header class="top-0 left-0 lg:left-64 right-0 h-16 bg-white border-b border-slate-200/80 shadow-[0_1px_4px_rgba(0,0,0,0.03)] z-30 flex items-center justify-between px-3 sm:px-6 lg:px-gutter-desktop gap-2 sm:gap-4 transition-all sticky">
<div class="flex items-center gap-2 sm:gap-space-md min-w-0">
<!-- Hamburger Button: Visible on Mobile (<768px) and Tablet (768px-1023px) -->
<button aria-label="Buka menu navigasi" class="lg:hidden p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-800 transition-colors flex items-center justify-center shrink-0 border border-slate-200" onclick="toggleMobileSidebar()">
<span class="material-symbols-outlined text-[22px]">menu</span>
</button>
<!-- Breadcrumbs & Depot Branch badge -->

</div>
<!-- Right Header Toolbar -->
<div class="flex items-center gap-1.5 sm:gap-3 shrink-0">
<!-- Search SKU quick box (Desktop & Tablet md+) -->
<div class="hidden md:flex items-center gap-2 bg-slate-100 px-3 py-1.5 rounded-lg text-slate-500 border border-slate-200/80 focus-within:border-secondary-container focus-within:bg-white transition-all">
<span class="material-symbols-outlined text-[18px]">search</span>
<input class="bg-transparent text-xs w-28 lg:w-40 border-0 p-0 focus:ring-0 text-slate-800 placeholder:text-slate-400" placeholder="Pencarian SKU..." type="text">
<span class="font-label-md text-[10px] bg-slate-200 text-slate-600 px-1.5 py-0.5 rounded font-mono font-bold">F2</span>
</div>
<!-- Quick Action Icons -->
<div class="flex items-center gap-1">


</div>
<div class="h-6 w-px bg-slate-200 hidden sm:block"></div>
<!-- User Admin Profile -->
<div class="flex items-center gap-2 pl-0.5">
<div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center shrink-0 font-bold text-xs ring-2 ring-secondary-container/20">
<span class="material-symbols-outlined text-[18px]">person</span>
</div>
<div class="hidden sm:flex flex-col text-left">
<span class="font-label-lg text-xs text-slate-900 font-bold leading-tight">Admin Gudang</span>
<span class="font-label-md text-[10px] text-slate-500 uppercase tracking-wider">Super Admin</span>
</div>
</div>
</div>
</header>
<!-- Page Content Container -->
<main class="relative w-full min-h-screen bg-slate-50 px-3.5 sm:px-6 lg:px-gutter-desktop pt-4 sm:pt-6 pb-32" style="min-height: 100vh; overflow-y: visible;">
<div class="flex flex-col w-full gap-5 sm:gap-6">
<!-- Title & Action Section -->
<div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3.5">
<div class="flex flex-col">
<div class="flex items-center gap-2 mb-1">
<span class="font-label-md text-label-md uppercase tracking-wider text-secondary-container font-bold">Manajemen Inventaris</span>
<span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
<span class="font-label-md text-label-md text-slate-500">Live Stock Sync</span>
</div>
<h1 class="font-headline-lg text-2xl sm:text-headline-lg text-slate-900 tracking-tight font-extrabold">Data Stok Barang</h1>
<p class="font-body-md text-xs sm:text-body-md text-slate-500 max-w-2xl mt-0.5">
            Kelola inventaris material, harga modal &amp; jual, serta status ketersediaan barang secara real-time.
          </p>
</div>
<div class="w-full sm:w-auto flex items-center shrink-0 relative">
<!-- Button Tambah Data: Full width on mobile, auto on tablet/desktop -->
<button class="w-full sm:w-auto h-11 px-5 bg-secondary-container hover:bg-orange-700 active:scale-[0.98] text-white rounded-xl font-semibold text-sm flex items-center justify-center gap-2 shadow-sm shadow-orange-500/20 transition-all cursor-pointer" onclick="openModal('add')">
<span class="material-symbols-outlined text-[20px]">add_circle</span>
<span class="">Tambah Data Barang</span>
</button>
</div>
</div>
<!-- Ringkasan Stok Cards Overview (Desktop 4-cols, Tablet/Mobile 2-cols) -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
<!-- 1. Total SKU -->
<div class="bg-white p-3.5 sm:p-4 rounded-xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:border-slate-300 transition-colors">
<div class="flex flex-col">
<span class="font-label-md text-[11px] uppercase tracking-wider text-slate-500 font-semibold">Total SKU</span>
<span class="font-headline-md text-xl sm:text-2xl text-slate-900 font-extrabold tabular-nums mt-0.5">840</span>
</div>
</div>
<!-- 2. Stok Tersedia -->
<div class="bg-white p-3.5 sm:p-4 rounded-xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:border-slate-300 transition-colors">
<div class="flex flex-col">
<span class="font-label-md text-[11px] uppercase tracking-wider text-emerald-700 font-semibold">Stok Tersedia</span>
<span class="font-headline-md text-xl sm:text-2xl text-slate-900 font-extrabold tabular-nums mt-0.5">792</span>
</div>
</div>
<!-- 3. Stok Menipis -->
<div class="bg-white p-3.5 sm:p-4 rounded-xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:border-slate-300 transition-colors">
<div class="flex flex-col">
<span class="font-label-md text-[11px] uppercase tracking-wider text-amber-700 font-semibold">Stok Menipis</span>
<span class="font-headline-md text-xl sm:text-2xl text-slate-900 font-extrabold tabular-nums mt-0.5">36</span>
</div>
</div>
<!-- 4. Stok Habis -->
<div class="bg-white p-3.5 sm:p-4 rounded-xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:border-slate-300 transition-colors">
<div class="flex flex-col">
<span class="font-label-md text-[11px] uppercase tracking-wider text-red-600 font-semibold">Stok Habis</span>
<span class="font-headline-md text-xl sm:text-2xl text-slate-900 font-extrabold tabular-nums mt-0.5">12</span>
</div>
</div>
</div>
<!-- Main Container: Filter + Search + (Table / Mobile Cards) -->
<div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 sm:p-5 flex flex-col gap-4">
<!-- Quick Category Chips (Smooth Horizontal Scroll on mobile/tablet) -->
<div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none whitespace-nowrap -mx-1 px-1"><button class="category-tab px-3.5 py-1.5 rounded-lg bg-primary text-white font-semibold text-xs shrink-0 shadow-sm transition-transform active:scale-95 cursor-pointer" data-category="Semua" onclick="filterByCategory('Semua')">Semua (840)</button><button class="category-tab px-3.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-xs shrink-0 transition-colors cursor-pointer" data-category="Semen" onclick="filterByCategory('Semen')">Semen (184)</button><button class="category-tab px-3.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-xs shrink-0 transition-colors cursor-pointer" data-category="Besi &amp; Baja" onclick="filterByCategory('Besi &amp; Baja')">Besi &amp; Baja (230)</button><button class="category-tab px-3.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-xs shrink-0 transition-colors cursor-pointer" data-category="Cat &amp; Kimia" onclick="filterByCategory('Cat &amp; Kimia')">Cat &amp; Kimia (165)</button><button class="category-tab px-3.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-xs shrink-0 transition-colors cursor-pointer" data-category="Keramik &amp; Pasir" onclick="filterByCategory('Keramik &amp; Pasir')">Keramik &amp; Pasir (261)</button></div>
<!-- Controls Row: Entries Dropdown & Search Input (Stack on mobile, row on tablet/desktop) -->
<div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 pt-1 border-t border-slate-100">
<div class="flex items-center gap-2 text-slate-600 text-xs sm:text-sm">
<span class="">Tampilkan</span>
<div class="relative inline-block">
<select class="appearance-none bg-slate-100 hover:bg-slate-200/80 text-slate-800 font-semibold text-xs sm:text-sm rounded-lg pl-3 pr-8 py-2 border border-slate-200 focus:outline-none focus:ring-1 focus:ring-secondary-container cursor-pointer transition-colors">
<option selected="" value="10">10</option>
<option value="25">25</option>
<option value="50">50</option>
<option value="100">100</option>
</select>
<span class="material-symbols-outlined absolute right-2 top-2.5 text-slate-500 text-[18px] pointer-events-none">expand_more</span>
</div>
<span class="">data per halaman</span>
</div>
<!-- Search Input: Flex on tablet/desktop, Full width on mobile -->
<div class="relative w-full sm:w-72 lg:w-80">
<span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-[20px]">search</span>
<input class="w-full pl-10 pr-9 py-2 bg-slate-50 hover:bg-slate-100 focus:bg-white text-slate-900 border border-slate-200 rounded-lg text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary-container/40 focus:border-secondary-container transition-all placeholder:text-slate-400" id="tableSearch" placeholder="Cari kode, nama, atau brand..." type="text" oninput="applyInventoryFilter()">
<button class="material-symbols-outlined absolute right-2.5 top-2.5 text-slate-400 hover:text-slate-600 text-[18px] cursor-pointer" onclick="document.getElementById('tableSearch').value=''; applyInventoryFilter();" title="Hapus pencarian">close</button>
</div>
</div>
<!-- TAMPILAN 1: TABEL LENGKAP 10 KOLOM (Tablet md & Desktop lg+) -->
<div class="hidden md:block overflow-x-auto w-full rounded-xl border border-slate-200">
<table class="min-w-[860px] w-full text-left text-sm border-collapse">
<thead>
<tr class="bg-slate-50 text-slate-600 border-b border-slate-200 font-semibold text-xs uppercase tracking-wider">
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
<tbody class="divide-y divide-slate-100 text-slate-800"><tr class="inventory-row hover:bg-slate-50/80 transition-colors" data-category="Semen" data-text="smn-001 semen gresik 50kg semen"><td class="py-3.5 px-3 text-center text-slate-500 font-semibold row-number">1</td><td class="py-3.5 px-3"><span class="font-mono text-xs font-bold text-slate-800 bg-slate-100 px-2 py-1 rounded border border-slate-200">SMN-001</span></td><td class="py-3.5 px-4 font-bold text-slate-900">Semen Gresik 50kg</td><td class="py-3.5 px-3"><span class="px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 font-semibold text-xs border border-blue-100">Semen</span></td><td class="py-3.5 px-3 text-slate-600">Semen Gresik</td><td class="py-3.5 px-4 text-right font-bold text-slate-900">240 <span class="font-normal text-xs text-slate-500">Zak</span></td><td class="py-3.5 px-4 text-right text-slate-600 tabular-nums font-mono text-xs">Rp 56.000</td><td class="py-3.5 px-4 text-right font-bold text-slate-900 tabular-nums font-mono text-xs">Rp 64.000</td><td class="py-3.5 px-3 text-center"><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold text-xs border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Tersedia</span></td><td class="py-3.5 px-3 text-center"><div class="flex items-center justify-center gap-1.5"><button class="p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer" onclick="openModal('edit', {kode:'SMN-001', nama:'Semen Gresik 50kg', kategori:'Semen', brand:'Semen Gresik', stok:'240', satuan:'Zak', beli:'56000', jual:'64000'})" title="Edit Data"><span class="material-symbols-outlined text-[18px]">edit</span></button><button class="p-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 transition-colors cursor-pointer" onclick="openModal('adjust', {kode:'SMN-001', nama:'Semen Gresik 50kg', stok:'240', satuan:'Zak'})" title="Penyesuaian Stok"><span class="material-symbols-outlined text-[18px]">swap_vert</span></button><button class="p-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition-colors cursor-pointer" onclick="openModal('delete', {kode:'SMN-001', nama:'Semen Gresik 50kg'})" title="Hapus Data"><span class="material-symbols-outlined text-[18px]">delete</span></button></div></td></tr><tr class="inventory-row hover:bg-slate-50/80 transition-colors" data-category="Besi &amp; Baja" data-text="bsi-013 besi ulir 13mm sni besi &amp; baja krakatau steel"><td class="py-3.5 px-3 text-center text-slate-500 font-semibold row-number">2</td><td class="py-3.5 px-3"><span class="font-mono text-xs font-bold text-slate-800 bg-slate-100 px-2 py-1 rounded border border-slate-200">BSI-013</span></td><td class="py-3.5 px-4 font-bold text-slate-900">Besi Ulir 13mm SNI</td><td class="py-3.5 px-3"><span class="px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 font-semibold text-xs border border-indigo-100">Besi &amp; Baja</span></td><td class="py-3.5 px-3 text-slate-600">Krakatau Steel</td><td class="py-3.5 px-4 text-right font-bold text-amber-600">15 <span class="font-normal text-xs text-slate-500">Btg</span></td><td class="py-3.5 px-4 text-right text-slate-600 tabular-nums font-mono text-xs">Rp 108.000</td><td class="py-3.5 px-4 text-right font-bold text-slate-900 tabular-nums font-mono text-xs">Rp 125.000</td><td class="py-3.5 px-3 text-center"><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 font-bold text-xs border border-amber-200"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Stok Menipis</span></td><td class="py-3.5 px-3 text-center"><div class="flex items-center justify-center gap-1.5"><button class="p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer" onclick="openModal('edit', {kode:'BSI-013', nama:'Besi Ulir 13mm SNI', kategori:'Besi &amp; Baja', brand:'Krakatau Steel', stok:'15', satuan:'Btg', beli:'108000', jual:'125000'})" title="Edit Data"><span class="material-symbols-outlined text-[18px]">edit</span></button><button class="p-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 transition-colors cursor-pointer" onclick="openModal('adjust', {kode:'BSI-013', nama:'Besi Ulir 13mm SNI', stok:'15', satuan:'Btg'})" title="Penyesuaian Stok"><span class="material-symbols-outlined text-[18px]">swap_vert</span></button><button class="p-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition-colors cursor-pointer" onclick="openModal('delete', {kode:'BSI-013', nama:'Besi Ulir 13mm SNI'})" title="Hapus Data"><span class="material-symbols-outlined text-[18px]">delete</span></button></div></td></tr><tr class="inventory-row hover:bg-slate-50/80 transition-colors" data-category="Cat &amp; Kimia" data-text="cat-044 cat avitex 5kg super white cat &amp; kimia avian brands"><td class="py-3.5 px-3 text-center text-slate-500 font-semibold row-number">3</td><td class="py-3.5 px-3"><span class="font-mono text-xs font-bold text-slate-800 bg-slate-100 px-2 py-1 rounded border border-slate-200">CAT-044</span></td><td class="py-3.5 px-4 font-bold text-slate-900">Cat Avitex 5kg Super White</td><td class="py-3.5 px-3"><span class="px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 font-semibold text-xs border border-purple-100">Cat &amp; Kimia</span></td><td class="py-3.5 px-3 text-slate-600">Avian Brands</td><td class="py-3.5 px-4 text-right font-bold text-red-600">0 <span class="font-normal text-xs text-slate-500">Pail</span></td><td class="py-3.5 px-4 text-right text-slate-600 tabular-nums font-mono text-xs">Rp 89.000</td><td class="py-3.5 px-4 text-right font-bold text-slate-900 tabular-nums font-mono text-xs">Rp 102.500</td><td class="py-3.5 px-3 text-center"><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-50 text-red-700 font-bold text-xs border border-red-200"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>Habis</span></td><td class="py-3.5 px-3 text-center"><div class="flex items-center justify-center gap-1.5"><button class="p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer" onclick="openModal('edit', {kode:'CAT-044', nama:'Cat Avitex 5kg Super White', kategori:'Cat &amp; Kimia', brand:'Avian Brands', stok:'0', satuan:'Pail', beli:'89000', jual:'102500'})" title="Edit Data"><span class="material-symbols-outlined text-[18px]">edit</span></button><button class="p-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 transition-colors cursor-pointer" onclick="openModal('adjust', {kode:'CAT-044', nama:'Cat Avitex 5kg Super White', stok:'0', satuan:'Pail'})" title="Penyesuaian Stok"><span class="material-symbols-outlined text-[18px]">swap_vert</span></button><button class="p-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition-colors cursor-pointer" onclick="openModal('delete', {kode:'CAT-044', nama:'Cat Avitex 5kg Super White'})" title="Hapus Data"><span class="material-symbols-outlined text-[18px]">delete</span></button></div></td></tr><tr class="inventory-row hover:bg-slate-50/80 transition-colors" data-category="Keramik &amp; Pasir" data-text="psr-002 pasir pasang 1 pick-up keramik &amp; pasir mandiri pasir"><td class="py-3.5 px-3 text-center text-slate-500 font-semibold row-number">4</td><td class="py-3.5 px-3"><span class="font-mono text-xs font-bold text-slate-800 bg-slate-100 px-2 py-1 rounded border border-slate-200">PSR-002</span></td><td class="py-3.5 px-4 font-bold text-slate-900">Pasir Pasang 1 Pick-Up</td><td class="py-3.5 px-3"><span class="px-2 py-0.5 rounded-md bg-amber-50 text-amber-800 font-semibold text-xs border border-amber-200">Keramik &amp; Pasir</span></td><td class="py-3.5 px-3 text-slate-600">Mandiri Pasir</td><td class="py-3.5 px-4 text-right font-bold text-slate-900">42 <span class="font-normal text-xs text-slate-500">M³</span></td><td class="py-3.5 px-4 text-right text-slate-600 tabular-nums font-mono text-xs">Rp 320.000</td><td class="py-3.5 px-4 text-right font-bold text-slate-900 tabular-nums font-mono text-xs">Rp 385.000</td><td class="py-3.5 px-3 text-center"><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold text-xs border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Tersedia</span></td><td class="py-3.5 px-3 text-center"><div class="flex items-center justify-center gap-1.5"><button class="p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer" onclick="openModal('edit', {kode:'PSR-002', nama:'Pasir Pasang 1 Pick-Up', kategori:'Keramik &amp; Pasir', brand:'Mandiri Pasir', stok:'42', satuan:'M³', beli:'320000', jual:'385000'})" title="Edit Data"><span class="material-symbols-outlined text-[18px]">edit</span></button><button class="p-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 transition-colors cursor-pointer" onclick="openModal('adjust', {kode:'PSR-002', nama:'Pasir Pasang 1 Pick-Up', stok:'42', satuan:'M³'})" title="Penyesuaian Stok"><span class="material-symbols-outlined text-[18px]">swap_vert</span></button><button class="p-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition-colors cursor-pointer" onclick="openModal('delete', {kode:'PSR-002', nama:'Pasir Pasang 1 Pick-Up'})" title="Hapus Data"><span class="material-symbols-outlined text-[18px]">delete</span></button></div></td></tr><tr class="inventory-row hover:bg-slate-50/80 transition-colors" data-category="Keramik &amp; Pasir" data-text="hbl-007 bata ringan hebel 7.5cm keramik &amp; pasir blesscon"><td class="py-3.5 px-3 text-center text-slate-500 font-semibold row-number">5</td><td class="py-3.5 px-3"><span class="font-mono text-xs font-bold text-slate-800 bg-slate-100 px-2 py-1 rounded border border-slate-200">HBL-007</span></td><td class="py-3.5 px-4 font-bold text-slate-900">Bata Ringan Hebel 7.5cm</td><td class="py-3.5 px-3"><span class="px-2 py-0.5 rounded-md bg-amber-50 text-amber-800 font-semibold text-xs border border-amber-200">Keramik &amp; Pasir</span></td><td class="py-3.5 px-3 text-slate-600">Blesscon</td><td class="py-3.5 px-4 text-right font-bold text-amber-600">8 <span class="font-normal text-xs text-slate-500">M³</span></td><td class="py-3.5 px-4 text-right text-slate-600 tabular-nums font-mono text-xs">Rp 540.000</td><td class="py-3.5 px-4 text-right font-bold text-slate-900 tabular-nums font-mono text-xs">Rp 610.000</td><td class="py-3.5 px-3 text-center"><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 font-bold text-xs border border-amber-200"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Stok Menipis</span></td><td class="py-3.5 px-3 text-center"><div class="flex items-center justify-center gap-1.5"><button class="p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer" onclick="openModal('edit', {kode:'HBL-007', nama:'Bata Ringan Hebel 7.5cm', kategori:'Keramik &amp; Pasir', brand:'Blesscon', stok:'8', satuan:'M³', beli:'540000', jual:'610000'})" title="Edit Data"><span class="material-symbols-outlined text-[18px]">edit</span></button><button class="p-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 transition-colors cursor-pointer" onclick="openModal('adjust', {kode:'HBL-007', nama:'Bata Ringan Hebel 7.5cm', stok:'8', satuan:'M³'})" title="Penyesuaian Stok"><span class="material-symbols-outlined text-[18px]">swap_vert</span></button><button class="p-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition-colors cursor-pointer" onclick="openModal('delete', {kode:'HBL-007', nama:'Bata Ringan Hebel 7.5cm'})" title="Hapus Data"><span class="material-symbols-outlined text-[18px]">delete</span></button></div></td></tr><tr class="hidden" id="noResultsRow"><td class="py-8 text-center text-slate-500" colspan="10"><div class="flex flex-col items-center justify-center gap-2"><span class="material-symbols-outlined text-slate-400 text-[32px]">inventory_2</span><span class="font-medium text-sm">Tidak ada data barang yang sesuai filter kategori ini</span></div></td></tr></tbody>
</table>
</div>
<!-- TAMPILAN 2: DAFTAR KARTU MOBILE (Mobile < md: block, Tablet & Desktop md+: hidden) -->
<div class="block md:hidden flex flex-col gap-3.5 w-full"><div class="inventory-card-mobile bg-white border border-slate-200 rounded-xl p-3.5 shadow-sm flex flex-col gap-3" data-category="Semen" data-text="smn-001 semen gresik 50kg semen"><div class="flex items-start justify-between gap-2"><div class="flex items-start gap-2.5"><div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center shrink-0 mt-0.5"><span class="material-symbols-outlined text-[20px]">inventory</span></div><div><h3 class="font-bold text-slate-900 text-sm leading-snug">Semen Gresik 50kg</h3><div class="flex items-center gap-2 mt-0.5"><span class="font-mono text-[11px] font-bold bg-slate-100 px-1.5 py-0.5 rounded text-slate-700">SMN-001</span><span class="text-xs text-slate-500">Semen • Semen Gresik</span></div></div></div><span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[11px] shrink-0 border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Tersedia</span></div><div class="grid grid-cols-3 gap-2 bg-slate-50 p-2.5 rounded-lg text-left border border-slate-100"><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-slate-400">Stok Fisik</span><span class="text-sm font-extrabold text-slate-900 mt-0.5">240 <span class="text-xs font-normal text-slate-500">Zak</span></span></div><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-slate-400">Harga Beli</span><span class="text-xs font-semibold text-slate-600 mt-0.5 font-mono">Rp 56.000</span></div><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-slate-400">Harga Jual</span><span class="text-xs font-bold text-slate-900 mt-0.5 font-mono">Rp 64.000</span></div></div><div class="grid grid-cols-3 gap-2 pt-1"><button class="h-10 rounded-lg bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 font-semibold text-xs flex items-center justify-center gap-1 transition-all" onclick="openModal('edit', {kode:'SMN-001', nama:'Semen Gresik 50kg', kategori:'Semen', brand:'Semen Gresik', stok:'240', satuan:'Zak', beli:'56000', jual:'64000'})"><span class="material-symbols-outlined text-[16px]">edit</span><span class="">Edit</span></button><button class="h-10 rounded-lg bg-amber-50 hover:bg-amber-100 active:scale-95 text-amber-800 font-semibold text-xs flex items-center justify-center gap-1 transition-all border border-amber-200/50" onclick="openModal('adjust', {kode:'SMN-001', nama:'Semen Gresik 50kg', stok:'240', satuan:'Zak'})"><span class="material-symbols-outlined text-[16px]">swap_vert</span><span class="">Sesuaikan</span></button><button class="h-10 rounded-lg bg-red-50 hover:bg-red-100 active:scale-95 text-red-600 font-semibold text-xs flex items-center justify-center gap-1 transition-all" onclick="openModal('delete', {kode:'SMN-001', nama:'Semen Gresik 50kg'})"><span class="material-symbols-outlined text-[16px]">delete</span><span class="">Hapus</span></button></div></div><div class="inventory-card-mobile bg-white border border-amber-200/80 rounded-xl p-3.5 shadow-sm flex flex-col gap-3" data-category="Besi &amp; Baja" data-text="bsi-013 besi ulir 13mm sni besi &amp; baja krakatau steel"><div class="flex items-start justify-between gap-2"><div class="flex items-start gap-2.5"><div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center shrink-0 mt-0.5"><span class="material-symbols-outlined text-[20px]">architecture</span></div><div><h3 class="font-bold text-slate-900 text-sm leading-snug">Besi Ulir 13mm SNI</h3><div class="flex items-center gap-2 mt-0.5"><span class="font-mono text-[11px] font-bold bg-slate-100 px-1.5 py-0.5 rounded text-slate-700">BSI-013</span><span class="text-xs text-slate-500">Besi &amp; Baja • Krakatau Steel</span></div></div></div><span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 font-bold text-[11px] shrink-0 border border-amber-200"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Menipis</span></div><div class="grid grid-cols-3 gap-2 bg-amber-50/50 p-2.5 rounded-lg text-left border border-amber-100/60"><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-amber-700">Stok Fisik</span><span class="text-sm font-extrabold text-amber-600 mt-0.5">15 <span class="text-xs font-normal text-slate-500">Btg</span></span></div><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-slate-400">Harga Beli</span><span class="text-xs font-semibold text-slate-600 mt-0.5 font-mono">Rp 108.000</span></div><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-slate-400">Harga Jual</span><span class="text-xs font-bold text-slate-900 mt-0.5 font-mono">Rp 125.000</span></div></div><div class="grid grid-cols-3 gap-2 pt-1"><button class="h-10 rounded-lg bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 font-semibold text-xs flex items-center justify-center gap-1 transition-all" onclick="openModal('edit', {kode:'BSI-013', nama:'Besi Ulir 13mm SNI', kategori:'Besi &amp; Baja', brand:'Krakatau Steel', stok:'15', satuan:'Btg', beli:'108000', jual:'125000'})"><span class="material-symbols-outlined text-[16px]">edit</span><span class="">Edit</span></button><button class="h-10 rounded-lg bg-amber-50 hover:bg-amber-100 active:scale-95 text-amber-800 font-semibold text-xs flex items-center justify-center gap-1 transition-all border border-amber-200/50" onclick="openModal('adjust', {kode:'BSI-013', nama:'Besi Ulir 13mm SNI', stok:'15', satuan:'Btg'})"><span class="material-symbols-outlined text-[16px]">swap_vert</span><span class="">Sesuaikan</span></button><button class="h-10 rounded-lg bg-red-50 hover:bg-red-100 active:scale-95 text-red-600 font-semibold text-xs flex items-center justify-center gap-1 transition-all" onclick="openModal('delete', {kode:'BSI-013', nama:'Besi Ulir 13mm SNI'})"><span class="material-symbols-outlined text-[16px]">delete</span><span class="">Hapus</span></button></div></div><div class="inventory-card-mobile bg-white border border-red-200/80 rounded-xl p-3.5 shadow-sm flex flex-col gap-3" data-category="Cat &amp; Kimia" data-text="cat-044 cat avitex 5kg super white cat &amp; kimia avian brands"><div class="flex items-start justify-between gap-2"><div class="flex items-start gap-2.5"><div class="w-8 h-8 rounded-lg bg-red-50 text-red-700 flex items-center justify-center shrink-0 mt-0.5"><span class="material-symbols-outlined text-[20px]">format_paint</span></div><div><h3 class="font-bold text-slate-900 text-sm leading-snug">Cat Avitex 5kg Super White</h3><div class="flex items-center gap-2 mt-0.5"><span class="font-mono text-[11px] font-bold bg-slate-100 px-1.5 py-0.5 rounded text-slate-700">CAT-044</span><span class="text-xs text-slate-500">Cat &amp; Kimia • Avian Brands</span></div></div></div><span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-red-50 text-red-700 font-bold text-[11px] shrink-0 border border-red-200"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>Habis</span></div><div class="grid grid-cols-3 gap-2 bg-red-50/50 p-2.5 rounded-lg text-left border border-red-100/60"><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-red-600">Stok Fisik</span><span class="text-sm font-extrabold text-red-600 mt-0.5">0 <span class="text-xs font-normal text-slate-500">Pail</span></span></div><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-slate-400">Harga Beli</span><span class="text-xs font-semibold text-slate-600 mt-0.5 font-mono">Rp 89.000</span></div><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-slate-400">Harga Jual</span><span class="text-xs font-bold text-slate-900 mt-0.5 font-mono">Rp 102.500</span></div></div><div class="grid grid-cols-3 gap-2 pt-1"><button class="h-10 rounded-lg bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 font-semibold text-xs flex items-center justify-center gap-1 transition-all" onclick="openModal('edit', {kode:'CAT-044', nama:'Cat Avitex 5kg Super White', kategori:'Cat &amp; Kimia', brand:'Avian Brands', stok:'0', satuan:'Pail', beli:'89000', jual:'102500'})"><span class="material-symbols-outlined text-[16px]">edit</span><span class="">Edit</span></button><button class="h-10 rounded-lg bg-amber-50 hover:bg-amber-100 active:scale-95 text-amber-800 font-semibold text-xs flex items-center justify-center gap-1 transition-all border border-amber-200/50" onclick="openModal('adjust', {kode:'CAT-044', nama:'Cat Avitex 5kg Super White', stok:'0', satuan:'Pail'})"><span class="material-symbols-outlined text-[16px]">swap_vert</span><span class="">Restok</span></button><button class="h-10 rounded-lg bg-red-50 hover:bg-red-100 active:scale-95 text-red-600 font-semibold text-xs flex items-center justify-center gap-1 transition-all" onclick="openModal('delete', {kode:'CAT-044', nama:'Cat Avitex 5kg Super White'})"><span class="material-symbols-outlined text-[16px]">delete</span><span class="">Hapus</span></button></div></div><div class="inventory-card-mobile bg-white border border-slate-200 rounded-xl p-3.5 shadow-sm flex flex-col gap-3" data-category="Keramik &amp; Pasir" data-text="psr-002 pasir pasang 1 pick-up keramik &amp; pasir mandiri pasir"><div class="flex items-start justify-between gap-2"><div class="flex items-start gap-2.5"><div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 mt-0.5"><span class="material-symbols-outlined text-[20px]">layers</span></div><div><h3 class="font-bold text-slate-900 text-sm leading-snug">Pasir Pasang 1 Pick-Up</h3><div class="flex items-center gap-2 mt-0.5"><span class="font-mono text-[11px] font-bold bg-slate-100 px-1.5 py-0.5 rounded text-slate-700">PSR-002</span><span class="text-xs text-slate-500">Keramik &amp; Pasir • Mandiri Pasir</span></div></div></div><span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[11px] shrink-0 border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Tersedia</span></div><div class="grid grid-cols-3 gap-2 bg-slate-50 p-2.5 rounded-lg text-left border border-slate-100"><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-slate-400">Stok Fisik</span><span class="text-sm font-extrabold text-slate-900 mt-0.5">42 <span class="text-xs font-normal text-slate-500">M³</span></span></div><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-slate-400">Harga Beli</span><span class="text-xs font-semibold text-slate-600 mt-0.5 font-mono">Rp 320.000</span></div><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-slate-400">Harga Jual</span><span class="text-xs font-bold text-slate-900 mt-0.5 font-mono">Rp 385.000</span></div></div><div class="grid grid-cols-3 gap-2 pt-1"><button class="h-10 rounded-lg bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 font-semibold text-xs flex items-center justify-center gap-1 transition-all" onclick="openModal('edit', {kode:'PSR-002', nama:'Pasir Pasang 1 Pick-Up', kategori:'Keramik &amp; Pasir', brand:'Mandiri Pasir', stok:'42', satuan:'M³', beli:'320000', jual:'385000'})"><span class="material-symbols-outlined text-[16px]">edit</span><span class="">Edit</span></button><button class="h-10 rounded-lg bg-amber-50 hover:bg-amber-100 active:scale-95 text-amber-800 font-semibold text-xs flex items-center justify-center gap-1 transition-all border border-amber-200/50" onclick="openModal('adjust', {kode:'PSR-002', nama:'Pasir Pasang 1 Pick-Up', stok:'42', satuan:'M³'})"><span class="material-symbols-outlined text-[16px]">swap_vert</span><span class="">Sesuaikan</span></button><button class="h-10 rounded-lg bg-red-50 hover:bg-red-100 active:scale-95 text-red-600 font-semibold text-xs flex items-center justify-center gap-1 transition-all" onclick="openModal('delete', {kode:'PSR-002', nama:'Pasir Pasang 1 Pick-Up'})"><span class="material-symbols-outlined text-[16px]">delete</span><span class="">Hapus</span></button></div></div><div class="inventory-card-mobile bg-white border border-amber-200/80 rounded-xl p-3.5 shadow-sm flex flex-col gap-3" data-category="Keramik &amp; Pasir" data-text="hbl-007 bata ringan hebel 7.5cm keramik &amp; pasir blesscon"><div class="flex items-start justify-between gap-2"><div class="flex items-start gap-2.5"><div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-800 flex items-center justify-center shrink-0 mt-0.5"><span class="material-symbols-outlined text-[20px]">view_in_ar</span></div><div><h3 class="font-bold text-slate-900 text-sm leading-snug">Bata Ringan Hebel 7.5cm</h3><div class="flex items-center gap-2 mt-0.5"><span class="font-mono text-[11px] font-bold bg-slate-100 px-1.5 py-0.5 rounded text-slate-700">HBL-007</span><span class="text-xs text-slate-500">Keramik &amp; Pasir • Blesscon</span></div></div></div><span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 font-bold text-[11px] shrink-0 border border-amber-200"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Menipis</span></div><div class="grid grid-cols-3 gap-2 bg-amber-50/50 p-2.5 rounded-lg text-left border border-amber-100/60"><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-amber-700">Stok Fisik</span><span class="text-sm font-extrabold text-amber-600 mt-0.5">8 <span class="text-xs font-normal text-slate-500">M³</span></span></div><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-slate-400">Harga Beli</span><span class="text-xs font-semibold text-slate-600 mt-0.5 font-mono">Rp 540.000</span></div><div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-slate-400">Harga Jual</span><span class="text-xs font-bold text-slate-900 mt-0.5 font-mono">Rp 610.000</span></div></div><div class="grid grid-cols-3 gap-2 pt-1"><button class="h-10 rounded-lg bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 font-semibold text-xs flex items-center justify-center gap-1 transition-all" onclick="openModal('edit', {kode:'HBL-007', nama:'Bata Ringan Hebel 7.5cm', kategori:'Keramik &amp; Pasir', brand:'Blesscon', stok:'8', satuan:'M³', beli:'540000', jual:'610000'})"><span class="material-symbols-outlined text-[16px]">edit</span><span class="">Edit</span></button><button class="h-10 rounded-lg bg-amber-50 hover:bg-amber-100 active:scale-95 text-amber-800 font-semibold text-xs flex items-center justify-center gap-1 transition-all border border-amber-200/50" onclick="openModal('adjust', {kode:'HBL-007', nama:'Bata Ringan Hebel 7.5cm', stok:'8', satuan:'M³'})"><span class="material-symbols-outlined text-[16px]">swap_vert</span><span class="">Sesuaikan</span></button><button class="h-10 rounded-lg bg-red-50 hover:bg-red-100 active:scale-95 text-red-600 font-semibold text-xs flex items-center justify-center gap-1 transition-all" onclick="openModal('delete', {kode:'HBL-007', nama:'Bata Ringan Hebel 7.5cm'})"><span class="material-symbols-outlined text-[16px]">delete</span><span class="">Hapus</span></button></div></div><div class="hidden text-center py-6 text-slate-500" id="noResultsMobile"><span class="material-symbols-outlined text-slate-400 text-[28px]">inventory_2</span><p class="text-xs font-medium mt-1">Tidak ada data barang yang sesuai</p></div></div>
<!-- Responsive Pagination & Footer Table Controls -->
<div class="flex flex-col md:flex-row items-center justify-between gap-3 pt-4 border-t border-slate-100 text-slate-500 text-xs sm:text-sm" id="paginationContainer">
<div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 text-slate-600"><span class="">Menampilkan <span class="font-bold text-slate-900 font-mono" id="pageInfoRange">1 - 5</span> dari <span class="font-bold text-slate-900 font-mono" id="pageTotalCount">840</span> barang</span><span class="hidden sm:inline-block text-slate-300">•</span><span class="hidden sm:inline-flex items-center px-2 py-0.5 rounded bg-slate-100 font-medium text-[11px] text-slate-600">Halaman <span class="font-bold text-slate-900 ml-1" id="pageCurrentBadge">1</span> / <span id="pageTotalPages" class="">84</span></span></div>
<nav aria-label="Navigasi Halaman" class="flex items-center flex-wrap justify-center gap-1 sm:gap-1.5 select-none">
<button class="h-8 px-2.5 sm:px-3 rounded-lg bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-600 font-semibold text-xs flex items-center gap-1 transition-all disabled:opacity-40 disabled:cursor-not-allowed" disabled="" id="btnPrevPage" onclick="goToPage(currentPage - 1)">
<span class="material-symbols-outlined text-[16px]">chevron_left</span>
<span class="hidden sm:inline">Sebelumnya</span>
</button>
<div class="flex items-center gap-1" id="paginationButtons">
<button class="page-btn w-8 h-8 rounded-lg bg-primary text-white font-bold text-xs flex items-center justify-center shadow-sm transition-all" data-page="1" onclick="goToPage(1)">1</button>
<button class="page-btn w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-700 font-medium text-xs flex items-center justify-center transition-colors" data-page="2" onclick="goToPage(2)">2</button>
<button class="page-btn w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-700 font-medium text-xs flex items-center justify-center transition-colors" data-page="3" onclick="goToPage(3)">3</button>
<span class="px-1 text-slate-400 text-xs font-mono">...</span>
<button class="page-btn w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-700 font-medium text-xs flex items-center justify-center transition-colors" data-page="84" onclick="goToPage(84)">84</button>
</div>
<button class="h-8 px-2.5 sm:px-3 rounded-lg bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-800 font-semibold text-xs flex items-center gap-1 transition-all" id="btnNextPage" onclick="goToPage(currentPage + 1)">
<span class="hidden sm:inline">Selanjutnya</span>
<span class="material-symbols-outlined text-[16px]">chevron_right</span>
</button>
</nav>
</div>
</div>
</div>
</main>
</div>
<!-- ========================================== -->
<!-- MODAL 1: Tambah Data Barang (Responsive) -->
<!-- ========================================== -->
<div class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex flex-col items-stretch md:items-center md:justify-center p-3 sm:p-4 pt-16 md:pt-4 overflow-y-auto hidden" id="modalAdd"><div class="bg-white rounded-2xl shadow-2xl w-full max-w-md md:max-w-2xl mx-auto md:my-auto max-h-[85vh] md:max-h-[90vh] flex flex-col border border-slate-200 animate-in fade-in zoom-in duration-150"><div class="p-3.5 sm:p-5 md:p-6 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white rounded-t-2xl z-10"><div class="flex items-center gap-2.5 sm:gap-3"><div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-orange-50 border border-orange-100 flex items-center justify-center text-secondary-container shrink-0"><span class="material-symbols-outlined text-[20px] sm:text-[24px]">add_box</span></div><div><h3 class="font-bold text-slate-900 text-sm sm:text-base md:text-lg">Tambah Data Barang</h3><p class="text-[11px] sm:text-xs text-slate-500">Detail spesifikasi SKU material baru</p></div></div><button class="text-slate-400 hover:text-slate-700 p-1 sm:p-1.5 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer" onclick="closeModal('add')"><span class="material-symbols-outlined text-[18px] sm:text-[20px]">close</span></button></div><form class="p-3.5 sm:p-5 md:p-6 flex flex-col gap-3 sm:gap-4 overflow-y-auto" onsubmit="event.preventDefault(); closeModal('add'); alert('Barang berhasil ditambahkan ke inventaris!');"><div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-4"><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-600">Kode Barang (SKU)</label><input class="bg-slate-50 focus:bg-white border border-slate-200 focus:border-secondary-container px-3 py-1.5 sm:py-2 rounded-lg font-mono text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-secondary-container/20 transition-all" placeholder="e.g. SMN-005" required="" type="text"></div><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-600">Kategori</label><select class="bg-slate-50 focus:bg-white border border-slate-200 focus:border-secondary-container px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-secondary-container/20 transition-all cursor-pointer" required=""><option value="">Pilih Kategori</option><option value="Semen">Semen</option><option value="Besi &amp; Baja">Besi &amp; Baja</option><option value="Cat &amp; Kimia">Cat &amp; Kimia</option><option value="Keramik &amp; Pasir">Keramik &amp; Pasir</option></select></div></div><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-600">Nama Barang Lengkap</label><input class="bg-slate-50 focus:bg-white border border-slate-200 focus:border-secondary-container px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-secondary-container/20 transition-all" placeholder="e.g. Semen Tiga Roda 50kg" required="" type="text"></div><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-600">Brand / Pabrikan</label><input class="bg-slate-50 focus:bg-white border border-slate-200 focus:border-secondary-container px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-secondary-container/20 transition-all" placeholder="e.g. Indocement" required="" type="text"></div><div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-4"><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-600">Jumlah Stok Awal</label><input class="bg-slate-50 focus:bg-white border border-slate-200 focus:border-secondary-container px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-secondary-container/20 font-mono transition-all" min="0" placeholder="0" required="" type="number"></div><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-600">Satuan Unit</label><select class="bg-slate-50 focus:bg-white border border-slate-200 focus:border-secondary-container px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-secondary-container/20 transition-all cursor-pointer" required=""><option value="Zak">Zak</option><option value="Btg">Btg (Batang)</option><option value="Pail">Pail</option><option value="M³">M³ (Meter Kubik)</option><option value="Dus">Dus</option></select></div></div><div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-4"><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-600">Harga Beli / Modal (Rp)</label><input class="bg-slate-50 focus:bg-white border border-slate-200 focus:border-secondary-container px-3 py-1.5 sm:py-2 rounded-lg font-mono text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-secondary-container/20 transition-all" min="0" placeholder="50000" required="" type="number"></div><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-600">Harga Jual Normal (Rp)</label><input class="bg-slate-50 focus:bg-white border border-slate-200 focus:border-secondary-container px-3 py-1.5 sm:py-2 rounded-lg font-mono text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-secondary-container/20 transition-all" min="0" placeholder="60000" required="" type="number"></div></div><div class="flex items-center justify-end gap-2 pt-2.5 sm:pt-3 border-t border-slate-100"><button class="px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs sm:text-sm transition-colors cursor-pointer" onclick="closeModal('add')" type="button">Batal</button><button class="px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl bg-secondary-container hover:bg-orange-700 text-white font-semibold text-xs sm:text-sm shadow-sm shadow-orange-500/30 transition-all cursor-pointer" type="submit">Simpan Barang</button></div></form></div></div>
<!-- ========================================== -->
<!-- MODAL 2: Edit Data Barang (Responsive) -->
<!-- ========================================== -->
<div class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-3.5 sm:p-4 overflow-y-auto" id="modalEdit">
<div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full max-h-[92vh] flex flex-col my-auto border border-slate-200 animate-in fade-in zoom-in duration-150">
<div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white rounded-t-2xl z-10">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-700 shrink-0">
<span class="material-symbols-outlined text-[24px]">edit_note</span>
</div>
<div>
<h3 class="font-bold text-slate-900 text-base sm:text-lg">Edit Data Barang</h3>
<p class="text-xs text-slate-500">Perbarui spesifikasi, harga beli, atau harga jual</p>
</div>
</div>
<button class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition-colors" onclick="closeModal('edit')">
<span class="material-symbols-outlined text-[20px]">close</span>
</button>
</div>
<form class="p-4 sm:p-5 flex flex-col gap-4 overflow-y-auto" onsubmit="event.preventDefault(); closeModal('edit'); alert('Perubahan barang berhasil disimpan!');">
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
<div class="flex flex-col gap-1.5">
<label class="text-[11px] font-bold uppercase tracking-wider text-slate-600">Kode Barang (SKU)</label>
<input class="bg-slate-100 border border-slate-200 text-slate-500 px-3 py-2 rounded-lg font-mono text-sm cursor-not-allowed" id="editKode" readonly="" type="text">
</div>
<div class="flex flex-col gap-1.5">
<label class="text-[11px] font-bold uppercase tracking-wider text-slate-600">Kategori</label>
<input class="bg-slate-50 border border-slate-200 focus:bg-white focus:border-secondary-container px-3 py-2 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-secondary-container/20 transition-all" id="editKategori" type="text">
</div>
</div>
<div class="flex flex-col gap-1.5">
<label class="text-[11px] font-bold uppercase tracking-wider text-slate-600">Nama Barang</label>
<input class="bg-slate-50 border border-slate-200 focus:bg-white focus:border-secondary-container px-3 py-2 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-secondary-container/20 transition-all" id="editNama" required="" type="text">
</div>
<div class="flex flex-col gap-1.5">
<label class="text-[11px] font-bold uppercase tracking-wider text-slate-600">Brand / Manufaktur</label>
<input class="bg-slate-50 border border-slate-200 focus:bg-white focus:border-secondary-container px-3 py-2 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-secondary-container/20 transition-all" id="editBrand" required="" type="text">
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
<div class="flex flex-col gap-1.5">
<label class="text-[11px] font-bold uppercase tracking-wider text-slate-600">Stok Fisik</label>
<input class="bg-slate-50 border border-slate-200 focus:bg-white focus:border-secondary-container px-3 py-2 rounded-lg font-mono text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-secondary-container/20 transition-all" id="editStok" min="0" required="" type="number">
</div>
<div class="flex flex-col gap-1.5">
<label class="text-[11px] font-bold uppercase tracking-wider text-slate-600">Satuan Unit</label>
<select class="bg-slate-50 border border-slate-200 focus:bg-white focus:border-secondary-container px-3 py-2 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-secondary-container/20 transition-all" id="editSatuan">
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
<label class="text-[11px] font-bold uppercase tracking-wider text-slate-600">Harga Beli (Rp)</label>
<input class="bg-slate-50 border border-slate-200 focus:bg-white focus:border-secondary-container px-3 py-2 rounded-lg font-mono text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-secondary-container/20 transition-all" id="editBeli" min="0" required="" type="number">
</div>
<div class="flex flex-col gap-1.5">
<label class="text-[11px] font-bold uppercase tracking-wider text-slate-600">Harga Jual (Rp)</label>
<input class="bg-slate-50 border border-slate-200 focus:bg-white focus:border-secondary-container px-3 py-2 rounded-lg font-mono text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-secondary-container/20 transition-all" id="editJual" min="0" required="" type="number">
</div>
</div>
<div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
<button class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs sm:text-sm transition-colors cursor-pointer" onclick="closeModal('edit')" type="button">
          Batal
        </button>
<button class="px-5 py-2.5 rounded-xl bg-primary hover:bg-slate-800 text-white font-semibold text-xs sm:text-sm shadow-sm transition-all cursor-pointer" type="submit">
          Perbarui Data
        </button>
</div>
</form>
</div>
</div>
<!-- ========================================== -->
<!-- MODAL 3: Penyesuaian / Stock Adjustment Modal -->
<!-- ========================================== -->
<div class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-3.5 sm:p-4 overflow-y-auto hidden" id="modalAdjust">
<div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-4 sm:p-6 my-auto border border-slate-200 animate-in fade-in zoom-in duration-150">
<div class="flex items-center gap-3 mb-4">
<div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-200/80 flex items-center justify-center text-amber-700 shrink-0">
<span class="material-symbols-outlined text-[24px]">swap_vert</span>
</div>
<div>
<h3 class="font-bold text-slate-900 text-base sm:text-lg">Penyesuaian Stok Fisik</h3>
<p class="text-xs text-slate-500" id="adjustTargetText">SMN-001 - Semen Gresik 50kg</p>
</div>
</div>
<form class="flex flex-col gap-3.5" onsubmit="event.preventDefault(); closeModal('adjust'); alert('Penyesuaian stok berhasil dicatat ke kartu stok!');">
<div class="bg-slate-50 p-3 rounded-xl border border-slate-200 flex items-center justify-between">
<span class="text-xs text-slate-600 font-medium">Stok Sistem Saat Ini:</span>
<span class="text-sm font-bold text-slate-900 font-mono" id="adjustCurrentStock">240 Zak</span>
</div>
<div class="flex flex-col gap-1.5">
<label class="text-[11px] font-bold uppercase tracking-wider text-slate-600">Jenis Penyesuaian</label>
<div class="grid grid-cols-2 gap-2">
<label class="flex items-center gap-2 p-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
<input checked="" class="text-secondary-container focus:ring-secondary-container" name="adjustType" type="radio" value="masuk">
<span class="text-xs font-semibold text-slate-800">Barang Masuk (+)</span>
</label>
<label class="flex items-center gap-2 p-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
<input class="text-secondary-container focus:ring-secondary-container" name="adjustType" type="radio" value="keluar">
<span class="text-xs font-semibold text-slate-800">Koreksi / Rusak (-)</span>
</label>
</div>
</div>
<div class="flex flex-col gap-1.5">
<label class="text-[11px] font-bold uppercase tracking-wider text-slate-600">Jumlah Perubahan</label>
<input class="bg-slate-50 focus:bg-white border border-slate-200 focus:border-secondary-container px-3 py-2 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-secondary-container/20 font-mono" min="1" placeholder="Contoh: 10" required="" type="number">
</div>
<div class="flex flex-col gap-1.5">
<label class="text-[11px] font-bold uppercase tracking-wider text-slate-600">Catatan / Alasan</label>
<textarea class="bg-slate-50 focus:bg-white border border-slate-200 focus:border-secondary-container px-3 py-2 rounded-lg text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-secondary-container/20" placeholder="Restok supplier, salah hitung opname..." rows="2"></textarea>
</div>
<div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
<button class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors cursor-pointer" onclick="closeModal('adjust')" type="button">
          Batal
        </button>
<button class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-semibold text-xs shadow-sm transition-all cursor-pointer" type="submit">
          Simpan Penyesuaian
        </button>
</div>
</form>
</div>
</div>
<!-- ========================================== -->
<!-- MODAL 4: Konfirmasi Hapus Data (Responsive) -->
<!-- ========================================== -->
<div class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-3.5 sm:p-4" id="modalDelete">
<div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-5 sm:p-6 my-auto border border-slate-200 animate-in fade-in zoom-in duration-150">
<div class="flex items-center gap-3 mb-3">
<div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center text-red-600 shrink-0">
<span class="material-symbols-outlined text-[24px]">warning</span>
</div>
<div>
<h3 class="font-bold text-slate-900 text-base sm:text-lg">Hapus Data Barang?</h3>
<p class="text-xs font-semibold text-slate-500" id="deleteTargetText">SKU: SMN-001</p>
</div>
</div>
<p class="text-xs sm:text-sm text-slate-600 mb-5 leading-relaxed">
      Apakah Anda yakin ingin menghapus barang ini dari database sistem? Tindakan ini tidak dapat dibatalkan dan akan mempengaruhi riwayat inventaris terkait.
    </p>
<div class="flex items-center justify-end gap-2.5">
<button class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs sm:text-sm transition-colors cursor-pointer" onclick="closeModal('delete')" type="button">
        Batal
      </button>
<button class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-xs sm:text-sm transition-all shadow-sm shadow-red-600/30 cursor-pointer" onclick="confirmDeleteAction()" type="button">
        Ya, Hapus Data
      </button>
</div>
</div>
</div>
<!-- Interactive JS Handlers -->
</body>
</html>