<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Rekap Nota {{ $labelBulan }} - UD. Tentrem</title>
@php $rp = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.'); @endphp
<style>
  @page { size: A4 landscape; margin: 12mm; }
  * { box-sizing: border-box; }
  body { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #111; margin: 0; padding: 16px; }
  .bar { display: flex; gap: 8px; justify-content: flex-end; margin-bottom: 12px; }
  .bar button { padding: 8px 14px; border: 1px solid #999; border-radius: 6px; background: #fff; cursor: pointer; font-size: 13px; }
  .bar button.utama { background: #131b2e; color: #fff; border-color: #131b2e; }
  h1 { font-size: 18px; margin: 0; }
  h2 { font-size: 14px; margin: 2px 0 0; font-weight: normal; }
  .meta { color: #555; margin-top: 4px; }
  .kepala { border-bottom: 2px solid #111; padding-bottom: 8px; margin-bottom: 10px; }
  .ringkas { display: flex; gap: 8px; margin-bottom: 10px; }
  .ringkas div { flex: 1; border: 1px solid #bbb; border-radius: 4px; padding: 6px 8px; }
  .ringkas small { display: block; color: #555; text-transform: uppercase; font-size: 9px; letter-spacing: .04em; }
  .ringkas b { font-size: 13px; }
  table { width: 100%; border-collapse: collapse; }
  th, td { border: 1px solid #bbb; padding: 4px 6px; text-align: left; }
  th { background: #eee; font-size: 10px; text-transform: uppercase; }
  td.r, th.r { text-align: right; }
  td.c, th.c { text-align: center; }
  tfoot td { font-weight: bold; background: #f3f3f3; }
  tr { page-break-inside: avoid; }
  thead { display: table-header-group; }
  .ttd { margin-top: 24px; text-align: right; }
  @media print { .bar { display: none; } body { padding: 0; } }
</style>
</head>
<body>
<div class="bar">
  <button type="button" onclick="window.close()">Tutup</button>
  <button type="button" class="utama" onclick="window.print()">Cetak</button>
</div>

<div class="kepala">
  <h1>UD. Tentrem — Toko Bangunan</h1>
  <h2>Rekap Nota Penjualan Bulan {{ $labelBulan }}</h2>
  <div class="meta">Dicetak: {{ $dicetak }}@if ($filter) &nbsp;•&nbsp; {{ $filter }}@endif</div>
</div>

<div class="ringkas">
  <div><small>Jumlah Nota</small><b>{{ $ringkas['semua'] }}</b> <span>(Lunas {{ $ringkas['lunas'] }}, DP {{ $ringkas['dp'] }}, Belum {{ $ringkas['belum'] }})</span></div>
  <div><small>Nilai Penjualan</small><b>{{ $rp($ringkas['penjualan']) }}</b></div>
  <div><small>Uang Masuk</small><b>{{ $rp($ringkas['uang']) }}</b></div>
  <div><small>Sisa Tagihan</small><b>{{ $rp($ringkas['sisa']) }}</b></div>
</div>

<table>
  <thead>
    <tr>
      <th class="c" style="width:32px">No</th>
      <th>No. Nota</th>
      <th>Tanggal</th>
      <th>Pembeli</th>
      <th>Kasir</th>
      <th class="r">Total</th>
      <th class="r">Dibayar</th>
      <th class="r">Sisa</th>
      <th class="c">Status</th>
    </tr>
  </thead>
  <tbody>
    @forelse ($daftar as $n)
    <tr>
      <td class="c">{{ $loop->iteration }}</td>
      <td>{{ $n['nomor'] }}</td>
      <td>{{ $n['tanggal'] }}</td>
      <td>{{ $n['pembeli'] }}</td>
      <td>{{ $n['kasir'] }}</td>
      <td class="r">{{ $rp($n['total']) }}</td>
      <td class="r">{{ $rp($n['dibayar']) }}</td>
      <td class="r">{{ $n['sisa'] > 0 ? $rp($n['sisa']) : '-' }}</td>
      <td class="c">{{ $n['status'] }}</td>
    </tr>
    @empty
    <tr><td colspan="9" class="c">Tidak ada nota pada bulan dan filter ini.</td></tr>
    @endforelse
  </tbody>
  <tfoot>
    <tr>
      <td colspan="5">TOTAL ({{ count($daftar) }} nota)</td>
      <td class="r">{{ $rp($totalTabel['total']) }}</td>
      <td class="r">{{ $rp($totalTabel['dibayar']) }}</td>
      <td class="r">{{ $rp($totalTabel['sisa']) }}</td>
      <td></td>
    </tr>
  </tfoot>
</table>

<div class="ttd">Mengetahui,<br><br><br><br>( ____________________ )</div>

<script>
  window.addEventListener('load', function () { setTimeout(function () { window.print(); }, 300); });
</script>
</body>
</html>