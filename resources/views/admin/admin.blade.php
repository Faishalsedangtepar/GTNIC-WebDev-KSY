@extends('layouts.admin')

@section('title', 'Laporan Keuangan - UD.Tentrem')
@section('body_class', 'bg-[#f8f9ff] text-[#0b1c30]')
@section('show_date', 'ya')

@push('head')
  <style>@layer base{html,body{margin:0;padding:0;}body{overscroll-behavior:none;}main>:first-child{margin-top:0!important;}main>:last-child{margin-bottom:0!important;}}::-webkit-scrollbar{display:none;}
  @media print{aside,nav,header,button,select,a[href*="export"]{display:none!important;}}</style>
@endpush

@php
  $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.');
  $warna = ['#a73a00', '#131b2e', '#fd651e', '#188ace', '#76777d'];
  $namaBulan = $bulan->copy()->locale('id')->translatedFormat('F Y');
  $adaExtra  = $data['ongkir_proteksi'] > 0;
@endphp

@section('content')
<div class="bg-[#f8f9ff] min-h-[calc(100vh-4rem)] p-[1rem] lg:p-[1.5rem] pb-24">
<div class="flex flex-col w-full space-y-[2rem]">

<!-- SECTION 1: HEADER & FILTER PERIODE -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-[1.25rem] bg-[#ffffff] p-[2rem] rounded-[0.5rem] shadow-sm">
<div class="flex flex-col space-y-[0.25rem]">
<h1 class="text-[28px] leading-[36px] text-[#0b1c30] font-bold tracking-tight">Laporan Pendapatan &amp; Laba Kotor</h1>
<p class="text-[13px] leading-[18px] font-normal text-[#45464d] max-w-2xl">
Rekapitulasi total omzet dan estimasi laba kotor penjualan material selama satu bulan berjalan tanpa potongan operasional atau pengeluaran.
</p>
</div>
<div class="flex flex-wrap items-center gap-[0.5rem]">
<div class="relative inline-flex items-center bg-[#eff4ff] rounded-[0.25rem] px-[0.75rem] py-[0.5rem]">
<span class="material-symbols-outlined text-[#45464d] text-[18px] mr-[0.25rem]">calendar_month</span>
<select data-url="{{ route('admin.dashboard') }}"
        onchange="window.location = this.dataset.url + '?bulan=' + this.value"
        class="bg-transparent text-[15px] leading-[20px] text-[#0b1c30] font-semibold focus:outline-none cursor-pointer pr-[0.75rem]">
@foreach ($opsiBulan as $b)
<option value="{{ $b->format('Y-m') }}" @selected($b->isSameMonth($bulan))>
{{ $b->isSameMonth(now()) ? 'Bulan Ini (' . $b->copy()->locale('id')->translatedFormat('F Y') . ')' : $b->copy()->locale('id')->translatedFormat('F Y') }}
</option>
@endforeach
</select>
</div>
<a href="{{ route('admin.laporan.cetak', ['bulan' => $bulan->format('Y-m')]) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-[0.25rem] bg-[#eff4ff] hover:bg-[#e5eeff] text-[#0b1c30] text-[15px] leading-[20px] font-semibold px-[0.75rem] py-[0.5rem] rounded-[0.25rem] transition-colors shadow-sm">
<span class="material-symbols-outlined text-[18px]">print</span>
<span>Cetak Ringkasan</span>
</a>
<a href="{{ route('admin.laporan.export', ['bulan' => $bulan->format('Y-m')]) }}" class="inline-flex items-center gap-[0.25rem] bg-[#131b2e] hover:bg-[#001d31] text-[#ffffff] text-[15px] leading-[20px] font-semibold px-[0.75rem] py-[0.5rem] rounded-[0.25rem] transition-colors shadow-sm">
<span class="material-symbols-outlined text-[18px]">file_download</span>
<span>Ekspor Excel</span>
</a>
</div>
</div>

<!-- SECTION 2: KARTU RINGKASAN -->
<div class="grid grid-cols-1 gap-[1.25rem] lg:grid-cols-2">
<div class="flex flex-col justify-between bg-[#ffffff] p-[2rem] rounded-[0.5rem] shadow-sm relative overflow-hidden">
<div class="space-y-[0.25rem]">
<span class="text-[13px] leading-[16px] font-semibold text-[#45464d] uppercase tracking-wider">Omzet Per Bulan</span>
<div class="pt-[0.25rem]">
<span class="text-[28px] leading-[36px] font-bold text-[#0b1c30] tracking-tight block">{{ $rp($data['omzet']) }}</span>
</div>
</div>
<div class="mt-[1.25rem] pt-[0.75rem] bg-[#eff4ff]/60 -mx-[2rem] -mb-[2rem] px-[2rem] pb-[0.75rem] flex items-center justify-between">
<div class="flex items-center gap-[0.25rem] text-[#45464d]">
<span class="material-symbols-outlined text-[16px]">receipt_long</span>
<span class="text-[13px] leading-[18px] font-normal">{{ number_format($data['jumlah_nota'], 0, ',', '.') }} Transaksi / Nota</span>
</div>
</div>
</div>

<div class="flex flex-col justify-between bg-[#ffffff] p-[2rem] rounded-[0.5rem] shadow-sm relative overflow-hidden">
<div class="space-y-[0.25rem]">
<div class="flex items-center justify-between">
<span class="text-[13px] leading-[16px] font-semibold text-[#45464d] uppercase tracking-wider">Rata-Rata Harian</span>
<span class="material-symbols-outlined text-[#45464d] text-[20px]">calendar_today</span>
</div>
<div class="pt-[0.25rem]">
<span class="text-[28px] leading-[36px] font-bold text-[#0b1c30] tracking-tight block">{{ $rp($data['rata_omzet']) }} <span class="text-[13px] leading-[18px] text-[#45464d] font-normal">/ hari</span></span>
</div>
</div>
<div class="mt-[1.25rem] pt-[0.75rem] bg-[#eff4ff]/60 -mx-[2rem] -mb-[2rem] px-[2rem] pb-[0.75rem] flex items-center justify-between">
<div class="flex items-center gap-[0.25rem] text-[#45464d]">
<span class="material-symbols-outlined text-[16px]">pace</span>
<span class="text-[13px] leading-[18px] font-normal">Rata-rata {{ $data['rata_nota'] }} transaksi per hari</span>
</div>
</div>
</div>
</div>

<!-- SECTION 3: KONTRIBUSI PER KATEGORI -->
<div class="bg-[#ffffff] p-[2rem] rounded-[0.5rem] shadow-sm flex flex-col space-y-[1.25rem]">
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-[0.25rem]">
<div class="flex flex-col">
<h2 class="text-[17px] leading-[24px] font-bold text-[#0b1c30]">Kontribusi Omzet per Kategori</h2>
<p class="text-[13px] leading-[18px] font-normal text-[#45464d]">Distribusi pendapatan penjualan menurut kelompok barang.</p>
</div>
<span class="text-[11px] leading-[14px] text-[#45464d] uppercase tracking-wider font-semibold">{{ $data['kategori']->count() }} Kategori Material</span>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-x-[2rem] gap-y-[1.25rem] pt-[0.25rem]">
@forelse ($data['kategori'] as $k)
@php
  $warnaK = $warna[$loop->index % count($warna)];
  $persen = $data['omzet'] > 0 ? $k->omzet / $data['omzet'] * 100 : 0;
  $lebar  = number_format($persen, 1, '.', '');
@endphp
<div class="flex flex-col space-y-[0.25rem] {{ $loop->last && $loop->count % 2 === 1 && ! $adaExtra ? 'lg:col-span-2' : '' }}">
<div class="flex items-center justify-between">
<div class="flex items-center gap-[0.25rem]">
<span class="w-2.5 h-2.5 rounded-full" @style(["background-color: $warnaK"])></span>
<span class="text-[15px] leading-[20px] font-bold text-[#0b1c30]">{{ $k->nama }}</span>
</div>
<div class="flex items-center gap-[0.5rem]">
<span class="text-[15px] leading-[20px] tracking-[-0.01em] text-[#0b1c30] font-bold">{{ $rp($k->omzet) }}</span>
<span class="text-[11px] leading-[14px] tracking-[0.04em] font-bold text-[#45464d]">{{ $lebar }}%</span>
</div>
</div>
<div class="w-full bg-[#dce9ff] h-2 rounded-full overflow-hidden">
<div class="h-full rounded-full" @style(["width: {$lebar}%", "background-color: $warnaK"])></div>
</div>
</div>
@empty
<p class="text-[13px] text-[#45464d] lg:col-span-2">Belum ada penjualan pada {{ $namaBulan }}.</p>
@endforelse
@if ($adaExtra)
@php $persenX = $data['omzet'] > 0 ? $data['ongkir_proteksi'] / $data['omzet'] * 100 : 0; @endphp
<div class="flex flex-col space-y-[0.25rem] {{ $data['kategori']->count() % 2 === 0 ? 'lg:col-span-2' : '' }}">
<div class="flex items-center justify-between">
<div class="flex items-center gap-[0.25rem]">
<span class="w-2.5 h-2.5 rounded-full" @style(["background-color: #c6c6cd"])></span>
<span class="text-[15px] leading-[20px] font-bold text-[#0b1c30]">Ongkir &amp; Proteksi</span>
</div>
<div class="flex items-center gap-[0.5rem]">
<span class="text-[15px] leading-[20px] tracking-[-0.01em] text-[#0b1c30] font-bold">{{ $rp($data['ongkir_proteksi']) }}</span>
<span class="text-[11px] leading-[14px] tracking-[0.04em] font-bold text-[#45464d]">{{ number_format($persenX, 1, '.', '') }}%</span>
</div>
</div>
<div class="w-full bg-[#dce9ff] h-2 rounded-full overflow-hidden">
<div class="h-full rounded-full" @style(["width: " . number_format($persenX, 1, '.', '') . "%", "background-color: #c6c6cd"])></div>
</div>
</div>
@endif
</div>
</div>

<!-- SECTION 4: REKAP HARIAN -->
<div class="bg-[#ffffff] rounded-[0.5rem] shadow-sm flex flex-col overflow-hidden">
<div class="p-[2rem]">
<h2 class="text-[17px] leading-[24px] font-bold text-[#0b1c30]">Rekapan Harian Penjualan ({{ $namaBulan }})</h2>
<p class="text-[13px] leading-[18px] font-normal text-[#45464d]">Rincian pendapatan kotor dan pencatatan transaksi per hari.</p>
</div>
<div class="w-full overflow-x-auto">
<table class="w-full text-left whitespace-nowrap">
<thead><tr class="bg-[#eff4ff] text-[#45464d] text-[11px] leading-[14px] font-bold uppercase tracking-wider">
<th class="py-[0.75rem] px-[2rem]">Tanggal</th>
<th class="py-[0.75rem] px-[1.25rem] text-center">Jumlah Transaksi</th>
<th class="py-[0.75rem] px-[2rem] text-right">Pendapatan Kotor</th>
<th class="py-[0.75rem] px-[2rem] text-center">Status Setoran</th>
</tr></thead>
<tbody class="divide-y divide-[#e5eeff] text-[#0b1c30] text-[13px] leading-[18px] font-normal">
@forelse ($data['harian'] as $h)
<tr class="hover:bg-[#eff4ff]/40 transition-colors">
<td class="py-[0.75rem] px-[2rem] text-[15px] leading-[20px] font-bold">{{ $h['tanggal']->copy()->locale('id')->translatedFormat('d F Y') }}</td>
<td class="py-[0.75rem] px-[1.25rem] text-center text-[15px] leading-[20px] tracking-[-0.01em] font-bold">{{ $h['jumlah_nota'] }} Nota</td>
<td class="py-[0.75rem] px-[2rem] text-right text-[15px] leading-[20px] tracking-[-0.01em] font-semibold">{{ $rp($h['omzet']) }}</td>
<td class="py-[0.75rem] px-[2rem] text-center">
@if ($h['belum_lunas'] === 0)
<span class="inline-flex items-center gap-1 bg-[#dce9ff] text-[#0b1c30] text-[11px] leading-[14px] tracking-[0.04em] px-[0.5rem] py-0.5 rounded-full font-bold"><span class="material-symbols-outlined text-[14px] text-[#a73a00]">check_circle</span> Masuk Kasir</span>
@else
<span class="inline-flex items-center gap-1 bg-[#ffdbcf] text-[#a73a00] text-[11px] leading-[14px] tracking-[0.04em] px-[0.5rem] py-0.5 rounded-full font-bold"><span class="material-symbols-outlined text-[14px]">pending</span> {{ $h['belum_lunas'] }} Belum Lunas</span>
@endif
</td>
</tr>
@empty
<tr><td colspan="4" class="py-[2rem] px-[2rem] text-center text-[#45464d]">Belum ada transaksi pada {{ $namaBulan }}.</td></tr>
@endforelse
</tbody>
<tfoot><tr class="bg-[#131b2e] text-[#ffffff] font-bold">
<td class="py-[1.25rem] px-[2rem] text-[15px] leading-[20px]">TOTAL AKUMULASI (1 BULAN)</td>
<td class="py-[1.25rem] px-[1.25rem] text-center text-[15px] leading-[20px] text-[#dae2fd]">{{ number_format($data['jumlah_nota'], 0, ',', '.') }} Nota</td>
<td class="py-[1.25rem] px-[2rem] text-right text-[15px] leading-[20px]">{{ $rp($data['omzet']) }}</td>
<td class="py-[1.25rem] px-[2rem] text-center">
<span class="inline-flex items-center bg-[#dce9ff] text-[#0b1c30] text-[11px] leading-[14px] tracking-[0.04em] font-bold px-[0.75rem] py-1 rounded-full uppercase">
{{ $data['belum_lunas'] === 0 ? 'Semua Lunas' : $data['belum_lunas'] . ' Nota Belum Lunas' }}
</span>
</td>
</tr></tfoot>
</table>
</div>
</div>

<!-- SECTION 5: CATATAN -->
<div class="p-[1.25rem] bg-[#eff4ff] rounded-[0.5rem] flex items-start gap-[0.75rem]">
<span class="material-symbols-outlined text-[#a73a00] text-[22px] flex-shrink-0 mt-0.5">info</span>
<div class="flex flex-col space-y-[0.25rem] text-[#45464d] text-[13px] leading-[18px] font-normal">
<span class="text-[15px] leading-[20px] font-bold text-[#0b1c30]">Catatan Finansial Penjualan</span>
<p>Omzet dihitung dari total tagihan nota (harga barang ditambah ongkir dan biaya proteksi). Estimasi laba kotor dihitung dari selisih harga jual dengan harga beli produk saat ini, dan tidak memperhitungkan beban operasional listrik, gaji pegawai, maupun bahan bakar armada kirim.</p>
</div>
</div>

</div>
</div>
@endsection