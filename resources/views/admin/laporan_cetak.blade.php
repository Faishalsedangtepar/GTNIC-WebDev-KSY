<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Laporan Keuangan {{ $namaBulan }} - UD. Tentrem</title>
@php $rp = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.'); @endphp
<style>
  @page { size: A4 portrait; margin: 12mm; }
  * { box-sizing: border-box; }
  body { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #111; margin: 0; padding: 16px; }
  .bar { display: flex; gap: 8px; justify-content: flex-end; margin-bottom: 12px; }
  .bar button { padding: 8px 14px; border: 1px solid #999; border-radius: 6px; background: #fff; cursor: pointer; font-size: 13px; }
  .bar button.utama { background: #131b2e; color: #fff; border-color: #131b2e; }
  h1 { font-size: 18px; margin: 0; }
  h2 { font-size: 14px; margin: 2px 0 0; font-weight: normal; }
  h3 { font-size: 12px; margin: 16px 0 6px; text-transform: uppercase; letter-spacing: .04em; }
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
  .catatan { margin-top: 14px; border: 1px solid #bbb; border-radius: 4px; padding: 8px 10px; color: #444; font-size: 10px; line-height: 1.5; }
  .ttd { margin-top: 24px; text-align: right; page-break-inside: avoid; }
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
  <h2>Laporan Pendapatan &amp; Laba Kotor Bulan {{ $namaBulan }}</h2>
  <div class="meta">Dicetak: {{ $dicetak }}</div>
</div>

<div class="ringkas">
  <div><small>Omzet Per Bulan</small><b>{{ $rp($data['omzet']) }}</b></div>
  <div><small>Jumlah Nota</small><b>{{ number_format($data['jumlah_nota'], 0, ',', '.') }}</b></div>
  <div><small>Rata-Rata Harian</small><b>{{ $rp($data['rata_omzet']) }}</b> <span>/ hari</span></div>
  <div><small>Rata-Rata Transaksi</small><b>{{ $data['rata_nota'] }}</b> <span>nota / hari</span></div>
</div>

<h3>Kontribusi Omzet per Kategori</h3>
<table>
  <thead>
    <tr>
      <th class="c" style="width:32px">No</th>
      <th>Kategori</th>
      <th class="r">Omzet</th>
      <th class="r" style="width:80px">Kontribusi</th>
    </tr>
  </thead>
  <tbody>
    @php $no = 0; @endphp
    @forelse ($data['kategori'] as $k)
    <tr>
      <td class="c">{{ ++$no }}</td>
      <td>{{ $k->nama }}</td>
      <td class="r">{{ $rp($k->omzet) }}</td>
      <td class="r">{{ number_format($data['omzet'] > 0 ? $k->omzet / $data['omzet'] * 100 : 0, 1, '.', '') }}%</td>
    </tr>
    @empty
    <tr><td colspan="4" class="c">Belum ada penjualan pada {{ $namaBulan }}.</td></tr>
    @endforelse
    @if ($data['ongkir_proteksi'] > 0)
    <tr>
      <td class="c">{{ ++$no }}</td>
      <td>Ongkir &amp; Proteksi</td>
      <td class="r">{{ $rp($data['ongkir_proteksi']) }}</td>
      <td class="r">{{ number_format($data['omzet'] > 0 ? $data['ongkir_proteksi'] / $data['omzet'] * 100 : 0, 1, '.', '') }}%</td>
    </tr>
    @endif
  </tbody>
  <tfoot>
    <tr>
      <td colspan="2">TOTAL</td>
      <td class="r">{{ $rp($data['omzet']) }}</td>
      <td class="r">{{ $data['omzet'] > 0 ? '100.0%' : '-' }}</td>
    </tr>
  </tfoot>
</table>

<h3>Rekapan Harian Penjualan ({{ $namaBulan }})</h3>
<table>
  <thead>
    <tr>
      <th class="c" style="width:32px">No</th>
      <th>Tanggal</th>
      <th class="c">Jumlah Transaksi</th>
      <th class="r">Pendapatan Kotor</th>
      <th class="c">Status Setoran</th>
    </tr>
  </thead>
  <tbody>
    @forelse ($data['harian'] as $h)
    <tr>
      <td class="c">{{ $loop->iteration }}</td>
      <td>{{ $h['tanggal']->copy()->locale('id')->translatedFormat('d F Y') }}</td>
      <td class="c">{{ $h['jumlah_nota'] }} Nota</td>
      <td class="r">{{ $rp($h['omzet']) }}</td>
      <td class="c">{{ $h['belum_lunas'] === 0 ? 'Masuk Kasir' : $h['belum_lunas'] . ' Belum Lunas' }}</td>
    </tr>
    @empty
    <tr><td colspan="5" class="c">Belum ada transaksi pada {{ $namaBulan }}.</td></tr>
    @endforelse
  </tbody>
  <tfoot>
    <tr>
      <td colspan="2">TOTAL AKUMULASI (1 BULAN)</td>
      <td class="c">{{ number_format($data['jumlah_nota'], 0, ',', '.') }} Nota</td>
      <td class="r">{{ $rp($data['omzet']) }}</td>
      <td class="c">{{ $data['belum_lunas'] === 0 ? 'Semua Lunas' : $data['belum_lunas'] . ' Nota Belum Lunas' }}</td>
    </tr>
  </tfoot>
</table>

<div class="catatan">
  <b>Catatan Finansial Penjualan.</b>
  Omzet dihitung dari total tagihan nota (harga barang ditambah ongkir dan biaya proteksi). Estimasi laba kotor dihitung dari selisih harga jual dengan harga beli produk saat ini, dan tidak memperhitungkan beban operasional listrik, gaji pegawai, maupun bahan bakar armada kirim.
</div>

<div class="ttd">Mengetahui,<br><br><br><br>( ____________________ )</div>

<script>
  window.addEventListener('load', function () { setTimeout(function () { window.print(); }, 300); });
</script>
</body>
</html>