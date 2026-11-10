@extends('layouts.admin')

@section('title', 'Riwayat Nota - Aura Material')
@section('body_class', 'bg-[#f8f9ff] text-[#0b1c30]')

@push('head')
  @vite(['resources/js/riwayatbulanan.js'])
  <style>@layer base{html,body{margin:0;padding:0;}body{overscroll-behavior:none;}main>:first-child{margin-top:0!important;}main>:last-child{margin-bottom:0!important;}}::-webkit-scrollbar{display:none;}</style>
@endpush

@section('content')
@php
  $rp  = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
  $tz  = config('app.timezone');

  // Bangun URL halaman ini dengan sebagian parameter diganti (null = dibuang)
  $link = fn (array $ubah = []) => route('admin.riwayatbulanan', array_filter(
      array_merge(['bulan' => $bulan, 'status' => $status, 'q' => $q, 'per' => $per, 'minggu' => $minggu], $ubah),
      fn ($v) => $v !== null && $v !== ''
  ));

  // Filter aktif ikut dibawa ke tombol ekspor dan cetak
  $paramLaporan = array_filter(
      ['bulan' => $bulan, 'status' => $status, 'q' => $q, 'minggu' => $minggu],
      fn ($v) => $v !== null && $v !== ''
  );

  $chipOn  = 'status-chip px-3 py-1.5 rounded-md text-[11px] leading-[14px] tracking-[0.04em] bg-[#ffffff] text-[#0b1c30] font-bold shadow-sm transition-all whitespace-nowrap';
  $chipOff = 'status-chip px-3 py-1.5 rounded-md text-[11px] leading-[14px] tracking-[0.04em] font-bold text-[#76777d] hover:text-[#0b1c30] transition-all whitespace-nowrap';
@endphp
<div class="px-4 md:px-[1.25rem]">
<div class="flex flex-col w-full pb-[2rem]">

<!-- Header, tombol ekspor/cetak & pilih bulan -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-[0.75rem] py-[1.25rem]">
<div class="flex flex-col">
<div class="flex flex-wrap items-center gap-[0.5rem]">
<span class="text-[28px] leading-[36px] tracking-[-0.02em] font-bold text-[#0b1c30]">Riwayat Nota Penjualan</span>
<span class="px-2 py-0.5 rounded bg-[#dce9ff] text-[#45464d] text-[11px] leading-[14px] font-bold uppercase tracking-wider">Arsip Bulanan</span>
</div>
<p class="text-[13px] leading-[18px] font-normal text-[#76777d] mt-0.5">Daftar arsip nota semua kasir, status pelunasan, dan rekapan penjualan per bulan.</p>
</div>
<div class="flex flex-wrap items-center gap-[0.5rem]">
<a href="{{ route('admin.riwayatbulanan.export', $paramLaporan) }}" class="h-10 px-4 inline-flex items-center gap-2 rounded-[0.5rem] bg-[#ffffff] hover:bg-[#eff4ff] shadow-sm text-[#0b1c30] text-[13px] leading-[18px] font-semibold transition-colors whitespace-nowrap">
<span class="material-symbols-outlined text-[20px] text-[#76777d]">table_view</span>
<span>Ekspor CSV / Excel</span>
</a>
<a href="{{ route('admin.riwayatbulanan.cetak', $paramLaporan) }}" target="_blank" rel="noopener" class="h-10 px-4 inline-flex items-center gap-2 rounded-[0.5rem] bg-[#131b2e] hover:bg-[#001d31] shadow-sm text-[#ffffff] text-[13px] leading-[18px] font-semibold transition-colors whitespace-nowrap">
<span class="material-symbols-outlined text-[20px]">print</span>
<span>Cetak Rekap Bulanan</span>
</a>
<div class="flex items-center bg-[#ffffff] rounded-[0.25rem] shadow-sm">
<a href="{{ $link(['bulan' => $bulanSebelum]) }}" class="h-10 w-9 flex items-center justify-center text-[#76777d] hover:text-[#0b1c30] hover:bg-[#eff4ff] transition-colors" title="Bulan Sebelumnya"><span class="material-symbols-outlined text-[18px]">chevron_left</span></a>
<form method="GET" action="{{ route('admin.riwayatbulanan') }}" class="relative">
@if ($status)<input type="hidden" name="status" value="{{ $status }}">@endif
@if ($q !== '')<input type="hidden" name="q" value="{{ $q }}">@endif
<input type="hidden" name="per" value="{{ $per }}">
@if ($minggu)<input type="hidden" name="minggu" value="{{ $minggu }}">@endif
<select name="bulan" onchange="this.form.submit()" class="h-10 px-[0.75rem] bg-transparent text-[15px] leading-[20px] font-semibold text-[#0b1c30] focus:outline-none cursor-pointer appearance-none pr-8">
@foreach ($daftarBulan as $b)
<option value="{{ $b['nilai'] }}" @selected($b['nilai'] === $bulan)>{{ $b['label'] }}</option>
@endforeach
</select>
<span class="material-symbols-outlined pointer-events-none absolute right-2 top-2.5 text-[18px] text-[#76777d]">expand_more</span>
</form>
<a href="{{ $link(['bulan' => $bulanBerikut]) }}" class="h-10 w-9 flex items-center justify-center text-[#76777d] hover:text-[#0b1c30] hover:bg-[#eff4ff] transition-colors" title="Bulan Berikutnya"><span class="material-symbols-outlined text-[18px]">chevron_right</span></a>
</div>
</div>
</div>

<!-- Ringkasan bulan -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-[0.75rem] mb-[0.75rem]">
<div class="bg-[#ffffff] rounded-[0.5rem] shadow-sm p-4"><div class="text-[11px] leading-[14px] font-bold uppercase tracking-wider text-[#76777d]">Jumlah Nota</div><div class="text-[22px] leading-[28px] font-bold text-[#0b1c30] mt-1">{{ $ringkas['semua'] }}</div></div>
<div class="bg-[#ffffff] rounded-[0.5rem] shadow-sm p-4"><div class="text-[11px] leading-[14px] font-bold uppercase tracking-wider text-[#76777d]">Nilai Penjualan</div><div class="text-[22px] leading-[28px] font-bold text-[#0b1c30] mt-1">{{ $rp($ringkas['penjualan']) }}</div></div>
<div class="bg-[#ffffff] rounded-[0.5rem] shadow-sm p-4"><div class="text-[11px] leading-[14px] font-bold uppercase tracking-wider text-[#76777d]">Uang Masuk</div><div class="text-[22px] leading-[28px] font-bold text-[#0b1c30] mt-1">{{ $rp($ringkas['uang']) }}</div></div>
<div class="bg-[#ffffff] rounded-[0.5rem] shadow-sm p-4"><div class="text-[11px] leading-[14px] font-bold uppercase tracking-wider text-[#a73a00]">Sisa Tagihan</div><div class="text-[22px] leading-[28px] font-bold text-[#a73a00] mt-1">{{ $rp($ringkas['sisa']) }}</div></div>
</div>

<!-- Pencarian & filter -->
<div class="bg-[#ffffff] p-[0.75rem] rounded-[0.5rem] shadow-sm mb-[0.75rem] flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-[0.75rem]">
<form method="GET" action="{{ route('admin.riwayatbulanan') }}" class="relative flex-1">
<input type="hidden" name="bulan" value="{{ $bulan }}">
@if ($status)<input type="hidden" name="status" value="{{ $status }}">@endif
<input type="hidden" name="per" value="{{ $per }}">
@if ($minggu)<input type="hidden" name="minggu" value="{{ $minggu }}">@endif
<span class="material-symbols-outlined absolute left-3 top-2.5 text-[18px] text-[#76777d]">search</span>
<input name="q" value="{{ $q }}" type="text" placeholder="Cari nama pembeli, no. HP, atau jenis material..." class="h-10 w-full pl-9 pr-9 rounded-[0.25rem] bg-[#eff4ff] text-[#0b1c30] text-[13px] leading-[18px] font-normal placeholder:text-[#76777d] focus:outline-none focus:bg-[#ffffff] shadow-inner transition-colors">
@if ($q !== '')
<a href="{{ $link(['q' => null]) }}" class="absolute right-2.5 top-2.5 text-[#76777d] hover:text-[#0b1c30] p-0.5 rounded-full" title="Hapus pencarian"><span class="material-symbols-outlined text-[18px]">close</span></a>
@endif
</form>
<div class="flex items-center flex-wrap gap-[0.5rem]">
<div class="flex items-center bg-[#eff4ff] p-1 rounded-[0.25rem] overflow-x-auto whitespace-nowrap">
<a href="{{ $link(['status' => null]) }}" class="{{ $status === null ? $chipOn : $chipOff }}">Semua ({{ $ringkas['semua'] }})</a>
<a href="{{ $link(['status' => 'lunas']) }}" class="{{ $status === 'lunas' ? $chipOn : $chipOff }}">Lunas ({{ $ringkas['lunas'] }})</a>
<a href="{{ $link(['status' => 'dp']) }}" class="{{ $status === 'dp' ? $chipOn : $chipOff }}">DP ({{ $ringkas['dp'] }})</a>
<a href="{{ $link(['status' => 'belum_dibayar']) }}" class="{{ $status === 'belum_dibayar' ? $chipOn : $chipOff }}">Belum Dibayar ({{ $ringkas['belum'] }})</a>
</div>
<form method="GET" action="{{ route('admin.riwayatbulanan') }}" class="relative inline-flex items-center bg-[#eff4ff] rounded-[0.25rem] px-[0.75rem] h-10">
<input type="hidden" name="bulan" value="{{ $bulan }}">
@if ($status)<input type="hidden" name="status" value="{{ $status }}">@endif
@if ($q !== '')<input type="hidden" name="q" value="{{ $q }}">@endif
<input type="hidden" name="per" value="{{ $per }}">
<span class="material-symbols-outlined text-[#76777d] text-[20px] mr-2">calendar_month</span>
<select name="minggu" onchange="this.form.submit()" class="bg-transparent text-[13px] leading-[18px] font-semibold text-[#0b1c30] focus:outline-none cursor-pointer appearance-none pr-6">
@foreach ($opsiMinggu as $o)
<option value="{{ $o['nilai'] }}" @selected((string) $minggu === $o['nilai'])>{{ $o['label'] }}</option>
@endforeach
</select>
<span class="material-symbols-outlined pointer-events-none absolute right-2 text-[18px] text-[#76777d]">expand_more</span>
</form>
<form method="GET" action="{{ route('admin.riwayatbulanan') }}" class="flex items-center gap-1.5 text-[#76777d] text-[11px] leading-[14px] tracking-[0.04em] font-bold pl-2">
<input type="hidden" name="bulan" value="{{ $bulan }}">
@if ($status)<input type="hidden" name="status" value="{{ $status }}">@endif
@if ($q !== '')<input type="hidden" name="q" value="{{ $q }}">@endif
@if ($minggu)<input type="hidden" name="minggu" value="{{ $minggu }}">@endif
<span>Baris:</span>
<select name="per" onchange="this.form.submit()" class="bg-[#eff4ff] px-2 py-1 rounded text-[#0b1c30] font-semibold focus:outline-none cursor-pointer">
@foreach ([10, 25, 50, 100] as $n)
<option value="{{ $n }}" @selected($per === $n)>{{ $n }}</option>
@endforeach
</select>
</form>
</div>
</div>

<!-- Tabel nota -->
<div class="bg-[#ffffff] rounded-[0.5rem] shadow-sm overflow-hidden flex flex-col">
<div class="overflow-x-auto">
<table class="w-full text-left text-[13px] leading-[18px] font-normal whitespace-nowrap">
<thead>
<tr class="bg-[#eff4ff] text-[#45464d] text-[11px] leading-[14px] font-bold uppercase tracking-wider select-none">
<th class="py-2.5 px-4 w-12 text-center">NO</th>
<th class="py-2.5 px-4">NO. NOTA / TANGGAL</th>
<th class="py-2.5 px-4">PEMBELI / PROYEK</th>
<th class="py-2.5 px-4">KASIR</th>
<th class="py-2.5 px-4 text-right">TOTAL</th>
<th class="py-2.5 px-4 text-center">STATUS</th>
<th class="py-2.5 px-4 text-center w-28">AKSI</th>
</tr>
</thead>
<tbody class="text-[#0b1c30]">
@forelse ($nota as $n)
@php
  $badge = [
    'lunas'         => ['Lunas',         'bg-[#e5eeff] text-[#0b1c30]'],
    'dp'            => ['DP',            'bg-[#ffdbce] text-[#370e00]'],
    'belum_dibayar' => ['Belum Dibayar', 'bg-[#ffdad6] text-[#93000a]'],
  ][$n['status_bayar']] ?? [$n['status_bayar'], 'bg-[#e5eeff] text-[#0b1c30]'];
  $tgl = \Illuminate\Support\Carbon::parse($n['tanggal'])->timezone($tz)->locale('id')->translatedFormat('d F Y • H:i');
@endphp
<tr class="hover:bg-[#eff4ff]/60 transition-colors {{ $loop->even ? 'bg-[#eff4ff]/20' : 'bg-[#ffffff]' }} border-b border-[#c6c6cd]/30">
<td class="py-3 px-4 text-center text-[#76777d]">{{ $nota->firstItem() + $loop->index }}</td>
<td class="py-3 px-4">
<div class="font-bold text-[#0b1c30]">{{ $n['nomor'] }}</div>
<div class="text-[11px] leading-[14px] tracking-[0.04em] font-bold text-[#76777d]">{{ $tgl }}</div>
</td>
<td class="py-3 px-4">
<div class="font-semibold text-[#0b1c30]">{{ $n['nama_pembeli'] ?: 'Pembeli Umum' }}</div>
<div class="text-[11px] leading-[14px] tracking-[0.04em] font-bold text-[#76777d] max-w-[22rem] truncate">{{ $n['item_ringkas'] }}</div>
</td>
<td class="py-3 px-4 text-[#45464d]">{{ $n['kasir'] }}</td>
<td class="py-3 px-4 text-right">
<div class="font-bold text-[#0b1c30] text-[16px]">{{ $rp($n['total']) }}</div>
@if ($n['sisa'] > 0)<div class="text-[11px] leading-[14px] font-bold text-[#a73a00]">Sisa {{ $rp($n['sisa']) }}</div>@endif
</td>
<td class="py-3 px-4 text-center"><span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full {{ $badge[1] }} text-[11px] leading-[14px] font-bold uppercase tracking-wider">{{ $badge[0] }}</span></td>
<td class="py-3 px-4 text-center">
<button type="button" class="h-8 px-3 rounded-[0.25rem] bg-[#dce9ff] hover:bg-[#d3e4fe] text-[#0b1c30] text-[11px] leading-[14px] tracking-[0.04em] font-semibold inline-flex items-center gap-1 transition-colors" onclick="bukaDetail({{ $n['id'] }})">
<span class="material-symbols-outlined text-[16px]">visibility</span>
<span>Detail</span>
</button>
</td>
</tr>
@empty
<tr><td colspan="7" class="py-10 text-center text-[#76777d]"><div class="flex flex-col items-center gap-2"><span class="material-symbols-outlined text-[32px]">receipt_long</span><span class="font-medium text-[13px]">Tidak ada nota pada bulan dan filter ini</span></div></td></tr>
@endforelse
</tbody>
</table>
</div>

<!-- Paginasi -->
<div class="flex flex-col md:flex-row items-center justify-between gap-3 px-4 py-3 border-t border-[#c6c6cd]/30 text-[#76777d] text-[12px]">
<span>Menampilkan <b class="text-[#0b1c30]">{{ $nota->firstItem() ?? 0 }} - {{ $nota->lastItem() ?? 0 }}</b> dari <b class="text-[#0b1c30]">{{ $nota->total() }}</b> nota • Halaman {{ $nota->currentPage() }} / {{ $nota->lastPage() }}</span>
<div class="flex items-center gap-1">
@if ($nota->onFirstPage())
<span class="h-9 px-3 rounded-[0.25rem] flex items-center opacity-40"><span class="material-symbols-outlined text-[18px]">chevron_left</span></span>
@else
<a href="{{ $nota->previousPageUrl() }}" class="h-9 px-3 rounded-[0.25rem] flex items-center hover:text-[#0b1c30] hover:bg-[#eff4ff] transition-colors"><span class="material-symbols-outlined text-[18px]">chevron_left</span></a>
@endif
@foreach (range(max(1, $nota->currentPage() - 2), min($nota->lastPage(), $nota->currentPage() + 2)) as $i)
<a href="{{ $nota->url($i) }}" class="h-9 w-9 rounded-[0.25rem] flex items-center justify-center {{ $i === $nota->currentPage() ? 'bg-[#000000] text-[#ffffff] font-bold shadow-sm' : 'hover:text-[#0b1c30] hover:bg-[#eff4ff] transition-colors' }}">{{ $i }}</a>
@endforeach
@if ($nota->hasMorePages())
<a href="{{ $nota->nextPageUrl() }}" class="h-9 px-3 rounded-[0.25rem] flex items-center hover:text-[#0b1c30] hover:bg-[#eff4ff] transition-colors"><span class="material-symbols-outlined text-[18px]">chevron_right</span></a>
@else
<span class="h-9 px-3 rounded-[0.25rem] flex items-center opacity-40"><span class="material-symbols-outlined text-[18px]">chevron_right</span></span>
@endif
</div>
</div>
</div>

<!-- Modal rincian nota (isinya diisi oleh riwayatbulanan.js dari /admin/nota/{id}) -->
<div class="fixed inset-0 z-50 bg-[#000000]/60 backdrop-blur-sm hidden flex items-center justify-center p-[0.75rem]" id="detailModal" data-base="{{ url('/admin/nota') }}" data-tz="{{ $tz }}" data-csrf="{{ csrf_token() }}" onclick="if (event.target === this) tutupDetail()">
<div class="bg-[#ffffff] rounded-2xl shadow-2xl max-w-lg w-full max-h-[92vh] flex flex-col overflow-hidden">
<div class="p-5 border-b border-[#c6c6cd]/30 flex items-start justify-between gap-3">
<div>
<div class="flex items-center gap-2"><span class="text-[18px] font-bold text-[#0b1c30]" id="dmNomor">-</span><span class="px-2.5 py-0.5 rounded-full bg-[#131b2e] text-[#ffffff] text-[11px] font-bold" id="dmStatus"></span></div>
<div class="text-[12px] text-[#76777d] mt-1" id="dmMeta"></div>
</div>
<button type="button" class="h-9 w-9 rounded-full bg-[#dce9ff] hover:bg-[#d3e4fe] flex items-center justify-center shrink-0" onclick="tutupDetail()"><span class="material-symbols-outlined text-[20px]">close</span></button>
</div>
<div class="hidden mx-5 mt-4 px-3 py-2 rounded-[0.5rem] text-[13px] font-semibold" id="dmNotif"></div>
<div class="p-5 overflow-y-auto flex flex-col gap-4" id="dmBody"></div>
<div class="p-4 border-t border-[#c6c6cd]/30 flex flex-wrap items-center gap-2">
<button type="button" id="dmLunasi" class="hidden basis-full h-11 rounded-[0.5rem] bg-[#a73a00] hover:bg-[#8a3000] disabled:opacity-60 text-[#ffffff] font-semibold text-[14px] items-center justify-center gap-2" onclick="lunasiNota()"><span class="material-symbols-outlined text-[18px]">price_check</span><span id="dmLunasiLabel">Lunaskan Sekarang</span></button>
<button type="button" class="flex-1 h-11 rounded-[0.5rem] border border-[#c6c6cd] bg-[#ffffff] hover:bg-[#eff4ff] font-semibold text-[14px]" onclick="tutupDetail()">Tutup</button>
<a id="dmCetak" href="#" target="_blank" rel="noopener" class="flex-[1.4] h-11 rounded-[0.5rem] bg-[#000000] hover:bg-[#213145] text-[#ffffff] font-semibold text-[14px] flex items-center justify-center gap-2"><span class="material-symbols-outlined text-[18px]">print</span>Cetak Ulang</a>
</div>
</div>
</div>

</div>
</div>
@endsection