@extends('layouts.admin')

@section('title', 'Data Stok Barang - Aura Material')
@section('body_class', 'bg-surface text-on-surface antialiased selection:bg-secondary-container selection:text-on-primary')

@push('head')
  @vite(['resources/js/laporanstok.js'])
  <style>@layer base{html,body{margin:0;padding:0;overflow-x:hidden;}body{overscroll-behavior:none;}main>:first-child{margin-top:0!important;}main>:last-child{margin-bottom:0!important;}}::-webkit-scrollbar{display:none;}</style>
@endpush

@section('content')
@php
  $fmt = fn ($n) => rtrim(rtrim(number_format((float) $n, 2, ',', '.'), '0'), ',');
  $rp  = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
@endphp
<div class="min-h-screen bg-surface px-3.5 sm:px-6 lg:px-[1.5rem] pt-4 sm:pt-6 pb-32" style="height: 100vh; overflow-y: auto;">

<div class="flex flex-col w-full gap-5 sm:gap-6">
@if (session('sukses'))
<div class="flex items-start gap-2 p-3.5 rounded-[0.75rem] bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold">
<span class="material-symbols-outlined text-[18px]">check_circle</span>
<span>{{ session('sukses') }}</span>
</div>
@endif
@if ($errors->any())
<div class="p-3.5 rounded-[0.75rem] bg-error-container/50 border border-error/20 text-on-error-container text-xs sm:text-sm">
<div class="flex items-center gap-2 font-bold mb-1"><span class="material-symbols-outlined text-[18px]">error</span>Data belum tersimpan</div>
<ul class="list-disc pl-6">
@foreach ($errors->all() as $e)
<li>{{ $e }}</li>
@endforeach
</ul>
</div>
@endif
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
<div class="w-full sm:w-auto flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 shrink-0 relative">
<button type="button" class="w-full sm:w-auto h-11 px-5 bg-amber-600 hover:bg-amber-700 active:scale-[0.98] text-on-primary rounded-[0.75rem] font-semibold text-sm flex items-center justify-center gap-2 shadow-sm transition-all cursor-pointer" onclick="openModal('stok')">
<span class="material-symbols-outlined text-[20px]">inventory</span>
<span>Tambah Stok Barang</span>
</button>
<!-- Button Tambah Data: Full width on mobile, auto on tablet/desktop -->
<button class="w-full sm:w-auto h-11 px-5 bg-secondary hover:bg-secondary-container active:scale-[0.98] text-on-primary rounded-[0.75rem] font-semibold text-sm flex items-center justify-center gap-2 shadow-sm shadow-secondary/20 transition-all cursor-pointer" onclick="openModal('add')">
<span class="material-symbols-outlined text-[20px]">add_circle</span>
<span class="">Tambah Barang Baru</span>
</button>
</div>
</div>
<!-- Ringkasan Stok Cards Overview (Desktop 4-cols, Tablet/Mobile 2-cols) -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
<!-- 1. Total SKU -->
<div class="bg-surface-container-lowest p-3.5 sm:p-4 rounded-[0.75rem] border border-outline-variant/40 shadow-sm flex items-center justify-between hover:border-outline-variant transition-colors">
<div class="flex flex-col">
<span class="text-[11px] uppercase tracking-wider text-on-surface-variant font-semibold">Total SKU</span>
<span class="text-xl sm:text-2xl text-on-surface font-extrabold tabular-nums mt-0.5">{{ $ringkas->total }}</span>
</div>
</div>
<!-- 2. Stok Tersedia -->
<div class="bg-surface-container-lowest p-3.5 sm:p-4 rounded-[0.75rem] border border-outline-variant/40 shadow-sm flex items-center justify-between hover:border-outline-variant transition-colors">
<div class="flex flex-col">
<span class="text-[11px] uppercase tracking-wider text-emerald-700 font-semibold">Stok Tersedia</span>
<span class="text-xl sm:text-2xl text-on-surface font-extrabold tabular-nums mt-0.5">{{ $ringkas->tersedia }}</span>
</div>
</div>
<!-- 3. Stok Menipis -->
<div class="bg-surface-container-lowest p-3.5 sm:p-4 rounded-[0.75rem] border border-outline-variant/40 shadow-sm flex items-center justify-between hover:border-outline-variant transition-colors">
<div class="flex flex-col">
<span class="text-[11px] uppercase tracking-wider text-amber-700 font-semibold">Stok Menipis</span>
<span class="text-xl sm:text-2xl text-on-surface font-extrabold tabular-nums mt-0.5">{{ $ringkas->kritis }}</span>
</div>
</div>
<!-- 4. Stok Habis -->
<div class="bg-surface-container-lowest p-3.5 sm:p-4 rounded-[0.75rem] border border-outline-variant/40 shadow-sm flex items-center justify-between hover:border-outline-variant transition-colors">
<div class="flex flex-col">
<span class="text-[11px] uppercase tracking-wider text-error font-semibold">Stok Habis</span>
<span class="text-xl sm:text-2xl text-on-surface font-extrabold tabular-nums mt-0.5">{{ $ringkas->habis }}</span>
</div>
</div>
</div>
<!-- Main Container: Filter + Search + (Table / Mobile Cards) -->
<div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/40 shadow-sm p-4 sm:p-5 flex flex-col gap-4">
<!-- Quick Category Chips (Smooth Horizontal Scroll on mobile/tablet) -->
<div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none whitespace-nowrap -mx-1 px-1">
@php
  $chipAktif = 'category-tab px-3.5 py-1.5 rounded-[0.375rem] bg-primary-container text-on-primary font-semibold text-xs shrink-0 shadow-sm transition-transform active:scale-95';
  $chipIdle  = 'category-tab px-3.5 py-1.5 rounded-[0.375rem] bg-surface-container hover:bg-surface-container-high text-on-surface font-medium text-xs shrink-0 transition-colors';
@endphp
<a href="{{ route('admin.laporanstok', array_filter(['q' => $q, 'per' => $per])) }}" class="{{ $kategoriId ? $chipIdle : $chipAktif }}">Semua ({{ $ringkas->total }})</a>
@foreach ($kategori as $k)
<a href="{{ route('admin.laporanstok', array_filter(['kategori' => $k->id, 'q' => $q, 'per' => $per])) }}" class="{{ (int) $k->id === $kategoriId ? $chipAktif : $chipIdle }}">{{ $k->nama }} ({{ $k->jumlah }})</a>
@endforeach
</div>
<!-- Controls Row: Entries Dropdown & Search Input (Stack on mobile, row on tablet/desktop) -->
<form method="GET" action="{{ route('admin.laporanstok') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 pt-1 border-t border-surface-container">
@if ($kategoriId)
<input type="hidden" name="kategori" value="{{ $kategoriId }}">
@endif
<div class="flex items-center gap-2 text-on-surface-variant text-xs sm:text-sm">
<span>Tampilkan</span>
<div class="relative inline-block">
<select name="per" onchange="this.form.submit()" class="appearance-none bg-surface-container hover:bg-surface-container-high/80 text-on-surface font-semibold text-xs sm:text-sm rounded-[0.375rem] pl-3 pr-8 py-2 border border-outline-variant/40 focus:outline-none focus:ring-1 focus:ring-secondary cursor-pointer transition-colors">
@foreach ([10, 25, 50, 100] as $n)
<option value="{{ $n }}" @selected($per === $n)>{{ $n }}</option>
@endforeach
</select>
<span class="material-symbols-outlined absolute right-2 top-2.5 text-on-surface-variant text-[18px] pointer-events-none">expand_more</span>
</div>
<span>data per halaman</span>
</div>
<div class="relative w-full sm:w-72 lg:w-80">
<span class="material-symbols-outlined absolute left-3 top-2.5 text-outline text-[20px]">search</span>
<input name="q" value="{{ $q }}" type="text" placeholder="Cari kode, nama, atau brand..." class="w-full pl-10 pr-9 py-2 bg-surface-container-low hover:bg-surface-container focus:bg-surface-container-lowest text-on-surface border border-outline-variant/40 rounded-[0.375rem] text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/40 focus:border-secondary transition-all placeholder:text-outline">
@if ($q !== '')
<a href="{{ route('admin.laporanstok', array_filter(['kategori' => $kategoriId, 'per' => $per])) }}" class="material-symbols-outlined absolute right-2.5 top-2.5 text-outline hover:text-on-surface-variant text-[18px]" title="Hapus pencarian">close</a>
@endif
</div>
</form>
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
<tbody class="divide-y divide-surface-container text-on-surface">
@forelse ($produk as $p)
@php
  $status = $p->stok <= 0 ? 'habis' : ($p->stok <= $p->stok_minimum ? 'kritis' : 'aman');
  $d = [
    'id' => $p->id, 'sku' => $p->sku, 'nama' => $p->nama, 'brand' => $p->brand,
    'kategori_id' => $p->kategori_id, 'satuan_id' => $p->satuan_id, 'satuan' => $p->satuan,
    'stok' => (float) $p->stok, 'stok_minimum' => (float) $p->stok_minimum,
    'beli' => (float) $p->harga_beli, 'jual' => (float) $p->harga_jual,
  ];
  $badge = [
    'aman'   => ['Tersedia',     'bg-emerald-50 text-emerald-700 border-emerald-200', 'bg-emerald-500'],
    'kritis' => ['Stok Menipis', 'bg-amber-50 text-amber-700 border-amber-200',       'bg-amber-500'],
    'habis'  => ['Habis',        'bg-error-container/50 text-on-error-container border-error/20', 'bg-error'],
  ][$status];
  $warnaStok = ['aman' => 'text-on-surface', 'kritis' => 'text-amber-600', 'habis' => 'text-error'][$status];
@endphp
<tr class="inventory-row hover:bg-surface-container-low/80 transition-colors">
<td class="py-3.5 px-3 text-center text-on-surface-variant font-semibold">{{ $produk->firstItem() + $loop->index }}</td>
<td class="py-3.5 px-3"><span class="font-mono text-xs font-bold text-on-surface bg-surface-container px-2 py-1 rounded border border-outline-variant/40">{{ $p->sku }}</span></td>
<td class="py-3.5 px-4 font-bold text-on-surface">{{ $p->nama }}</td>
<td class="py-3.5 px-3"><span class="px-2 py-0.5 rounded-md bg-tertiary-fixed text-on-tertiary-fixed-variant font-semibold text-xs border border-tertiary-fixed-dim/40">{{ $p->kategori }}</span></td>
<td class="py-3.5 px-3 text-on-surface-variant">{{ $p->brand ?: '-' }}</td>
<td class="py-3.5 px-4 text-right font-bold {{ $warnaStok }}">{{ $fmt($p->stok) }} <span class="font-normal text-xs text-on-surface-variant">{{ $p->satuan }}</span></td>
<td class="py-3.5 px-4 text-right text-on-surface-variant tabular-nums font-mono text-xs">{{ $rp($p->harga_beli) }}</td>
<td class="py-3.5 px-4 text-right font-bold text-on-surface tabular-nums font-mono text-xs">{{ $rp($p->harga_jual) }}</td>
<td class="py-3.5 px-3 text-center"><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full {{ $badge[1] }} font-bold text-xs border"><span class="w-1.5 h-1.5 rounded-full {{ $badge[2] }}"></span>{{ $badge[0] }}</span></td>
<td class="py-3.5 px-3 text-center"><div class="flex items-center justify-center gap-1.5"><button class="p-1.5 rounded-[0.375rem] bg-surface-container hover:bg-surface-container-high text-on-surface transition-colors cursor-pointer" onclick="openModal('edit', @js($d))" title="Edit Data"><span class="material-symbols-outlined text-[18px]">edit</span></button><button class="p-1.5 rounded-[0.375rem] bg-amber-50 hover:bg-amber-100 text-amber-700 transition-colors cursor-pointer" onclick="openModal('adjust', @js($d))" title="Penyesuaian Stok"><span class="material-symbols-outlined text-[18px]">swap_vert</span></button><button class="p-1.5 rounded-[0.375rem] bg-error-container/50 hover:bg-error-container text-error transition-colors cursor-pointer" onclick="openModal('delete', @js($d))" title="Hapus Data"><span class="material-symbols-outlined text-[18px]">delete</span></button></div></td>
</tr>
@empty
<tr><td class="py-8 text-center text-on-surface-variant" colspan="10"><div class="flex flex-col items-center justify-center gap-2"><span class="material-symbols-outlined text-outline text-[32px]">inventory_2</span><span class="font-medium text-sm">Tidak ada data barang yang sesuai</span></div></td></tr>
@endforelse
</tbody>
</table>
</div>
<!-- TAMPILAN 2: DAFTAR KARTU MOBILE (Mobile < md: block, Tablet & Desktop md+: hidden) -->
<div class="block md:hidden flex flex-col gap-3.5 w-full">
@forelse ($produk as $p)
@php
  $status = $p->stok <= 0 ? 'habis' : ($p->stok <= $p->stok_minimum ? 'kritis' : 'aman');
  $d = [
    'id' => $p->id, 'sku' => $p->sku, 'nama' => $p->nama, 'brand' => $p->brand,
    'kategori_id' => $p->kategori_id, 'satuan_id' => $p->satuan_id, 'satuan' => $p->satuan,
    'stok' => (float) $p->stok, 'stok_minimum' => (float) $p->stok_minimum,
    'beli' => (float) $p->harga_beli, 'jual' => (float) $p->harga_jual,
  ];
  $badge = [
    'aman'   => ['Tersedia',     'bg-emerald-50 text-emerald-700 border-emerald-200', 'bg-emerald-500'],
    'kritis' => ['Stok Menipis', 'bg-amber-50 text-amber-700 border-amber-200',       'bg-amber-500'],
    'habis'  => ['Habis',        'bg-error-container/50 text-on-error-container border-error/20', 'bg-error'],
  ][$status];
  $warnaStok = ['aman' => 'text-on-surface', 'kritis' => 'text-amber-600', 'habis' => 'text-error'][$status];
@endphp
<div class="inventory-card-mobile bg-surface-container-lowest border {{ $status === 'aman' ? 'border-outline-variant/40' : ($status === 'kritis' ? 'border-amber-200/80' : 'border-error/20') }} rounded-[0.75rem] p-3.5 shadow-sm flex flex-col gap-3">
<div class="flex items-start justify-between gap-2">
<div>
<h3 class="font-bold text-on-surface text-sm leading-snug">{{ $p->nama }}</h3>
<div class="flex items-center gap-2 mt-0.5"><span class="font-mono text-[11px] font-bold bg-surface-container px-1.5 py-0.5 rounded text-on-surface">{{ $p->sku }}</span><span class="text-xs text-on-surface-variant">{{ $p->kategori }}{{ $p->brand ? ' • ' . $p->brand : '' }}</span></div>
</div>
<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full {{ $badge[1] }} font-bold text-[11px] shrink-0 border"><span class="w-1.5 h-1.5 rounded-full {{ $badge[2] }}"></span>{{ $badge[0] }}</span>
</div>
<div class="grid grid-cols-3 gap-2 bg-surface-container-low p-2.5 rounded-[0.375rem] text-left border border-surface-container">
<div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-outline">Stok Fisik</span><span class="text-sm font-extrabold {{ $warnaStok }} mt-0.5">{{ $fmt($p->stok) }} <span class="text-xs font-normal text-on-surface-variant">{{ $p->satuan }}</span></span></div>
<div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-outline">Harga Beli</span><span class="text-xs font-semibold text-on-surface-variant mt-0.5 font-mono">{{ $rp($p->harga_beli) }}</span></div>
<div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-outline">Harga Jual</span><span class="text-xs font-bold text-on-surface mt-0.5 font-mono">{{ $rp($p->harga_jual) }}</span></div>
</div>
<div class="grid grid-cols-3 gap-2 pt-1">
<button class="h-10 rounded-[0.375rem] bg-surface-container hover:bg-surface-container-high active:scale-95 text-on-surface font-semibold text-xs flex items-center justify-center gap-1 transition-all" onclick="openModal('edit', @js($d))"><span class="material-symbols-outlined text-[16px]">edit</span><span>Edit</span></button>
<button class="h-10 rounded-[0.375rem] bg-amber-50 hover:bg-amber-100 active:scale-95 text-amber-800 font-semibold text-xs flex items-center justify-center gap-1 transition-all border border-amber-200/50" onclick="openModal('adjust', @js($d))"><span class="material-symbols-outlined text-[16px]">swap_vert</span><span>Sesuaikan</span></button>
<button class="h-10 rounded-[0.375rem] bg-error-container/50 hover:bg-error-container active:scale-95 text-error font-semibold text-xs flex items-center justify-center gap-1 transition-all" onclick="openModal('delete', @js($d))"><span class="material-symbols-outlined text-[16px]">delete</span><span>Hapus</span></button>
</div>
</div>
@empty
<div class="text-center py-6 text-on-surface-variant"><span class="material-symbols-outlined text-outline text-[28px]">inventory_2</span><p class="text-xs font-medium mt-1">Tidak ada data barang yang sesuai</p></div>
@endforelse
</div>
<!-- Responsive Pagination & Footer Table Controls -->
<div class="flex flex-col md:flex-row items-center justify-between gap-3 pt-4 border-t border-surface-container text-on-surface-variant text-xs sm:text-sm">
<div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 text-on-surface-variant">
<span>Menampilkan <span class="font-bold text-on-surface font-mono">{{ $produk->firstItem() ?? 0 }} - {{ $produk->lastItem() ?? 0 }}</span> dari <span class="font-bold text-on-surface font-mono">{{ $produk->total() }}</span> barang</span>
<span class="hidden sm:inline-block text-outline-variant">•</span>
<span class="hidden sm:inline-flex items-center px-2 py-0.5 rounded bg-surface-container font-medium text-[11px] text-on-surface-variant">Halaman <span class="font-bold text-on-surface ml-1">{{ $produk->currentPage() }}</span> / {{ $produk->lastPage() }}</span>
</div>
<nav aria-label="Navigasi Halaman" class="flex items-center flex-wrap justify-center gap-1 sm:gap-1.5 select-none">
@if ($produk->onFirstPage())
<span class="h-8 px-2.5 sm:px-3 rounded-[0.375rem] bg-surface-container text-on-surface-variant font-semibold text-xs flex items-center gap-1 opacity-40"><span class="material-symbols-outlined text-[16px]">chevron_left</span><span class="hidden sm:inline">Sebelumnya</span></span>
@else
<a href="{{ $produk->previousPageUrl() }}" class="h-8 px-2.5 sm:px-3 rounded-[0.375rem] bg-surface-container hover:bg-surface-container-high active:scale-95 text-on-surface font-semibold text-xs flex items-center gap-1 transition-all"><span class="material-symbols-outlined text-[16px]">chevron_left</span><span class="hidden sm:inline">Sebelumnya</span></a>
@endif
<div class="flex items-center gap-1">
@foreach (range(max(1, $produk->currentPage() - 2), min($produk->lastPage(), $produk->currentPage() + 2)) as $i)
<a href="{{ $produk->url($i) }}" class="{{ $i === $produk->currentPage() ? 'bg-primary-container text-on-primary font-bold shadow-sm' : 'hover:bg-surface-container text-on-surface font-medium' }} w-8 h-8 rounded-[0.375rem] text-xs flex items-center justify-center transition-colors">{{ $i }}</a>
@endforeach
</div>
@if ($produk->hasMorePages())
<a href="{{ $produk->nextPageUrl() }}" class="h-8 px-2.5 sm:px-3 rounded-[0.375rem] bg-surface-container hover:bg-surface-container-high active:scale-95 text-on-surface font-semibold text-xs flex items-center gap-1 transition-all"><span class="hidden sm:inline">Selanjutnya</span><span class="material-symbols-outlined text-[16px]">chevron_right</span></a>
@else
<span class="h-8 px-2.5 sm:px-3 rounded-[0.375rem] bg-surface-container text-on-surface-variant font-semibold text-xs flex items-center gap-1 opacity-40"><span class="hidden sm:inline">Selanjutnya</span><span class="material-symbols-outlined text-[16px]">chevron_right</span></span>
@endif
</nav>
</div>
</div>
</div>
</div>
@endsection

@push('modals')
<div class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-3.5 sm:p-4 overflow-y-auto" id="modalAdd">
<div class="bg-surface-container-lowest rounded-2xl shadow-2xl max-w-lg w-full max-h-[92vh] flex flex-col my-auto border border-outline-variant/40">
<div class="p-4 sm:p-5 border-b border-surface-container flex items-center justify-between sticky top-0 bg-surface-container-lowest rounded-t-2xl z-10">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-[0.75rem] bg-secondary-fixed border border-secondary-fixed-dim/50 text-secondary flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-[24px]">add_box</span></div>
<div><h3 class="font-bold text-on-surface text-base sm:text-lg">Tambah Data Barang</h3><p class="text-xs text-on-surface-variant" id="modalAddSub">Detail spesifikasi SKU material baru</p></div>
</div>
<button type="button" class="text-outline hover:text-on-surface p-1.5 rounded-[0.375rem] hover:bg-surface-container transition-colors" onclick="closeModal('add')"><span class="material-symbols-outlined text-[20px]">close</span></button>
</div>
<form method="POST" action="{{ route('admin.produk.store') }}" class="p-4 sm:p-5 flex flex-col gap-4 overflow-y-auto">
@csrf
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4"><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Kode Barang (SKU)</label><input name="sku" id="addSku" type="text" class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-2 rounded-[0.375rem] text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all font-mono uppercase" required maxlength="30" placeholder="mis. SMN-005"></div><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Kategori</label><select name="kategori_id" id="addKategori" required class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-2 rounded-[0.375rem] text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all cursor-pointer"><option value="">Pilih Kategori</option>@foreach ($kategori as $k)<option value="{{ $k->id }}">{{ $k->nama }}</option>@endforeach</select></div></div>
<div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Nama Barang Lengkap</label><input name="nama" id="addNama" type="text" class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-2 rounded-[0.375rem] text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all " required maxlength="150" placeholder="mis. Semen Tiga Roda 50kg"></div>
<div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Brand / Pabrikan (opsional)</label><input name="brand" id="addBrand" type="text" class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-2 rounded-[0.375rem] text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all " maxlength="100" placeholder="mis. Indocement"></div>
<div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4"><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Jumlah Stok Awal</label><input name="stok" id="addStok" type="number" class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-2 rounded-[0.375rem] text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all font-mono" required min="0" step="any" placeholder="0"></div><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Satuan Unit</label><select name="satuan_id" id="addSatuan" required class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-2 rounded-[0.375rem] text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all cursor-pointer">@foreach ($satuan as $s)<option value="{{ $s->id }}">{{ $s->nama }}</option>@endforeach</select></div><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Stok Minimum</label><input name="stok_minimum" id="addStokMin" type="number" class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-2 rounded-[0.375rem] text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all font-mono" min="0" step="any" placeholder="10"></div></div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4"><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Harga Beli / Modal (Rp)</label><input name="harga_beli" id="addBeli" type="number" class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-2 rounded-[0.375rem] text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all font-mono" required min="0" step="any" placeholder="50000"></div><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Harga Jual Normal (Rp)</label><input name="harga_jual" id="addJual" type="number" class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-2 rounded-[0.375rem] text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all font-mono" required min="0" step="any" placeholder="60000"></div></div>
<div class="flex items-center justify-end gap-2.5 pt-3 border-t border-surface-container">
<button type="button" class="px-4 py-2.5 rounded-[0.75rem] bg-surface-container hover:bg-surface-container-high text-on-surface font-semibold text-xs sm:text-sm transition-colors cursor-pointer" onclick="closeModal('add')">Batal</button>
<button type="submit" class="px-5 py-2.5 rounded-[0.75rem] bg-secondary hover:bg-secondary-container text-on-primary font-semibold text-xs sm:text-sm shadow-sm shadow-secondary/30 transition-all cursor-pointer">Simpan Barang</button>
</div>
</form>
</div>
</div>
<div class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-3.5 sm:p-4 overflow-y-auto" id="modalEdit">
<div class="bg-surface-container-lowest rounded-2xl shadow-2xl max-w-lg w-full max-h-[92vh] flex flex-col my-auto border border-outline-variant/40">
<div class="p-4 sm:p-5 border-b border-surface-container flex items-center justify-between sticky top-0 bg-surface-container-lowest rounded-t-2xl z-10">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-[0.75rem] bg-tertiary-fixed border border-tertiary-fixed-dim/40 text-on-tertiary-fixed-variant flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-[24px]">edit_note</span></div>
<div><h3 class="font-bold text-on-surface text-base sm:text-lg">Edit Data Barang</h3><p class="text-xs text-on-surface-variant" id="modalEditSub">Perbarui spesifikasi, harga beli, atau harga jual</p></div>
</div>
<button type="button" class="text-outline hover:text-on-surface p-1.5 rounded-[0.375rem] hover:bg-surface-container transition-colors" onclick="closeModal('edit')"><span class="material-symbols-outlined text-[20px]">close</span></button>
</div>
<form method="POST" id="formEdit" data-base="{{ url('/admin/produk') }}" class="p-4 sm:p-5 flex flex-col gap-4 overflow-y-auto">
@csrf
@method('PUT')
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4"><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Kode Barang (SKU)</label><input id="editSku" type="text" readonly class="bg-surface-container border border-outline-variant/40 text-on-surface-variant px-3 py-2 rounded-[0.375rem] font-mono text-sm cursor-not-allowed"></div><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Kategori</label><select name="kategori_id" id="editKategori" required class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-2 rounded-[0.375rem] text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all cursor-pointer"><option value="">Pilih Kategori</option>@foreach ($kategori as $k)<option value="{{ $k->id }}">{{ $k->nama }}</option>@endforeach</select></div></div>
<div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Nama Barang Lengkap</label><input name="nama" id="editNama" type="text" class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-2 rounded-[0.375rem] text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all " required maxlength="150" placeholder="mis. Semen Tiga Roda 50kg"></div>
<div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Brand / Pabrikan (opsional)</label><input name="brand" id="editBrand" type="text" class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-2 rounded-[0.375rem] text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all " maxlength="100" placeholder="mis. Indocement"></div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4"><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Satuan Unit</label><select name="satuan_id" id="editSatuan" required class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-2 rounded-[0.375rem] text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all cursor-pointer">@foreach ($satuan as $s)<option value="{{ $s->id }}">{{ $s->nama }}</option>@endforeach</select></div><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Stok Minimum</label><input name="stok_minimum" id="editStokMin" type="number" class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-2 rounded-[0.375rem] text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all font-mono" min="0" step="any" placeholder="10"></div></div>
<div class="bg-surface-container-low p-3 rounded-[0.75rem] border border-outline-variant/40 flex items-center justify-between"><span class="text-xs text-on-surface-variant font-medium">Stok saat ini (ubah lewat tombol Penyesuaian Stok):</span><span class="text-sm font-bold text-on-surface font-mono" id="editStokInfo">-</span></div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4"><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Harga Beli / Modal (Rp)</label><input name="harga_beli" id="editBeli" type="number" class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-2 rounded-[0.375rem] text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all font-mono" required min="0" step="any" placeholder="50000"></div><div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Harga Jual Normal (Rp)</label><input name="harga_jual" id="editJual" type="number" class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-2 rounded-[0.375rem] text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all font-mono" required min="0" step="any" placeholder="60000"></div></div>
<div class="flex items-center justify-end gap-2.5 pt-3 border-t border-surface-container">
<button type="button" class="px-4 py-2.5 rounded-[0.75rem] bg-surface-container hover:bg-surface-container-high text-on-surface font-semibold text-xs sm:text-sm transition-colors cursor-pointer" onclick="closeModal('edit')">Batal</button>
<button type="submit" class="px-5 py-2.5 rounded-[0.75rem] bg-primary-container hover:bg-primary-container text-on-primary font-semibold text-xs sm:text-sm shadow-sm transition-all cursor-pointer">Perbarui Data</button>
</div>
</form>
</div>
</div>
<div class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-3.5 sm:p-4 overflow-y-auto" id="modalAdjust">
<div class="bg-surface-container-lowest rounded-2xl shadow-2xl max-w-lg w-full max-h-[92vh] flex flex-col my-auto border border-outline-variant/40">
<div class="p-4 sm:p-5 border-b border-surface-container flex items-center justify-between sticky top-0 bg-surface-container-lowest rounded-t-2xl z-10">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-[0.75rem] bg-amber-50 border border-amber-200/80 text-amber-700 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-[24px]">swap_vert</span></div>
<div><h3 class="font-bold text-on-surface text-base sm:text-lg">Penyesuaian Stok Fisik</h3><p class="text-xs text-on-surface-variant" id="modalAdjustSub">-</p></div>
</div>
<button type="button" class="text-outline hover:text-on-surface p-1.5 rounded-[0.375rem] hover:bg-surface-container transition-colors" onclick="closeModal('adjust')"><span class="material-symbols-outlined text-[20px]">close</span></button>
</div>
<form method="POST" id="formAdjust" data-base="{{ url('/admin/produk') }}" class="p-4 sm:p-5 flex flex-col gap-4 overflow-y-auto">
@csrf
<div class="bg-surface-container-low p-3 rounded-[0.75rem] border border-outline-variant/40 flex items-center justify-between">
<span class="text-xs text-on-surface-variant font-medium">Stok Sistem Saat Ini:</span>
<span class="text-sm font-bold text-on-surface font-mono" id="adjustCurrentStock">-</span>
</div>
<div class="flex flex-col gap-1.5">
<label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Jenis Penyesuaian</label>
<div class="grid grid-cols-2 gap-2">
<label class="flex items-center gap-2 p-2.5 rounded-[0.375rem] border border-outline-variant/40 hover:bg-surface-container-low cursor-pointer"><input checked class="text-secondary focus:ring-secondary" name="jenis" type="radio" value="masuk"><span class="text-xs font-semibold text-on-surface">Barang Masuk (+)</span></label>
<label class="flex items-center gap-2 p-2.5 rounded-[0.375rem] border border-outline-variant/40 hover:bg-surface-container-low cursor-pointer"><input class="text-secondary focus:ring-secondary" name="jenis" type="radio" value="keluar"><span class="text-xs font-semibold text-on-surface">Koreksi / Rusak (-)</span></label>
</div>
</div>
<div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Jumlah Perubahan</label><input name="qty" id="adjustQty" type="number" class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-2 rounded-[0.375rem] text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all font-mono" required min="0.01" step="any" placeholder="Contoh: 10"></div>
<div class="flex flex-col gap-1"><label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Catatan / Alasan (opsional)</label><textarea name="catatan" maxlength="200" rows="2" class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-2 rounded-[0.375rem] text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all text-xs" placeholder="Restok supplier, salah hitung opname..."></textarea></div>
<div class="flex items-center justify-end gap-2.5 pt-3 border-t border-surface-container">
<button type="button" class="px-4 py-2.5 rounded-[0.75rem] bg-surface-container hover:bg-surface-container-high text-on-surface font-semibold text-xs sm:text-sm transition-colors cursor-pointer" onclick="closeModal('adjust')">Batal</button>
<button type="submit" class="px-5 py-2.5 rounded-[0.75rem] bg-amber-600 hover:bg-amber-700 text-on-primary font-semibold text-xs shadow-sm transition-all cursor-pointer">Simpan Penyesuaian</button>
</div>
</form>
</div>
</div>
<div class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-3.5 sm:p-4" id="modalDelete">
<div class="bg-surface-container-lowest rounded-2xl shadow-2xl max-w-md w-full p-5 sm:p-6 my-auto border border-outline-variant/40">
<div class="flex items-center gap-3 mb-3">
<div class="w-10 h-10 rounded-[0.75rem] bg-error-container flex items-center justify-center text-error shrink-0"><span class="material-symbols-outlined text-[24px]">warning</span></div>
<div><h3 class="font-bold text-on-surface text-base sm:text-lg">Hapus Data Barang?</h3><p class="text-xs font-semibold text-on-surface-variant" id="deleteTargetText">-</p></div>
</div>
<p class="text-xs sm:text-sm text-on-surface-variant mb-5 leading-relaxed">Barang akan hilang dari daftar stok dan katalog kasir, serta dikeluarkan dari keranjang kasir. Nota lama yang memuat barang ini tetap utuh.</p>
<form method="POST" id="formDelete" data-base="{{ url('/admin/produk') }}" class="flex items-center justify-end gap-2.5">
@csrf
@method('DELETE')
<button type="button" class="px-4 py-2.5 rounded-[0.75rem] bg-surface-container hover:bg-surface-container-high text-on-surface font-semibold text-xs sm:text-sm transition-colors cursor-pointer" onclick="closeModal('delete')">Batal</button>
<button type="submit" class="px-5 py-2.5 rounded-[0.75rem] bg-error hover:bg-error/90 text-on-primary font-semibold text-xs sm:text-sm transition-all shadow-sm shadow-error/30 cursor-pointer">Ya, Hapus Data</button>
</form>
</div>
</div>
<div class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-3.5 sm:p-4 overflow-y-auto" id="modalStok">
<div class="bg-surface-container-lowest rounded-2xl shadow-2xl max-w-lg w-full max-h-[92vh] flex flex-col my-auto border border-outline-variant/40">
<div class="p-4 sm:p-5 border-b border-surface-container flex items-center justify-between sticky top-0 bg-surface-container-lowest rounded-t-2xl z-10">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-[0.75rem] bg-amber-50 border border-amber-200/80 text-amber-700 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-[24px]">inventory</span></div>
<div><h3 class="font-bold text-on-surface text-base sm:text-lg">Tambah Stok Barang</h3><p class="text-xs text-on-surface-variant">Cari barang, lalu isi jumlah yang masuk</p></div>
</div>
<button type="button" class="text-outline hover:text-on-surface p-1.5 rounded-[0.375rem] hover:bg-surface-container transition-colors" onclick="closeModal('stok')"><span class="material-symbols-outlined text-[20px]">close</span></button>
</div>
<form method="POST" id="formStok" data-base="{{ url('/admin/produk') }}" class="p-4 sm:p-5 flex flex-col gap-4 overflow-y-auto">
@csrf
<input type="hidden" name="jenis" value="masuk">
<div class="flex flex-col gap-1">
<label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Cari Barang (kode atau nama)</label>
<input id="stokCari" type="text" list="daftarProduk" autocomplete="off" required placeholder="Ketik kode atau nama barang..." class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-2 rounded-[0.375rem] text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all">
<datalist id="daftarProduk">
@foreach ($pilihanProduk as $pp)
<option value="{{ $pp->sku }} - {{ $pp->nama }}" data-id="{{ $pp->id }}" data-stok="{{ $pp->stok }}" data-satuan="{{ $pp->satuan }}"></option>
@endforeach
</datalist>
</div>
<div class="bg-surface-container-low p-3 rounded-[0.75rem] border border-outline-variant/40 flex items-center justify-between">
<span class="text-xs text-on-surface-variant font-medium">Stok Sistem Saat Ini:</span>
<span class="text-sm font-bold text-on-surface font-mono" id="stokInfo">-</span>
</div>
<div class="flex flex-col gap-1">
<label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Jumlah Masuk</label>
<input name="qty" type="number" required min="0.01" step="any" placeholder="Contoh: 10" class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-2 rounded-[0.375rem] text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all font-mono">
</div>
<div class="flex flex-col gap-1">
<label class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Catatan (opsional)</label>
<textarea name="catatan" maxlength="200" rows="2" placeholder="Restok supplier, nomor surat jalan..." class="bg-surface-container-low focus:bg-surface-container-lowest border border-outline-variant/40 focus:border-secondary px-3 py-2 rounded-[0.375rem] text-xs text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/20 transition-all"></textarea>
</div>
<div class="flex items-center justify-end gap-2.5 pt-3 border-t border-surface-container">
<button type="button" class="px-4 py-2.5 rounded-[0.75rem] bg-surface-container hover:bg-surface-container-high text-on-surface font-semibold text-xs sm:text-sm transition-colors cursor-pointer" onclick="closeModal('stok')">Batal</button>
<button type="submit" class="px-5 py-2.5 rounded-[0.75rem] bg-amber-600 hover:bg-amber-700 text-on-primary font-semibold text-xs shadow-sm transition-all cursor-pointer">Simpan Stok</button>
</div>
</form>
</div>
</div>
@endpush