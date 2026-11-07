<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $nota['nomor'] }}</title>
  <style>
    @page { size: 80mm auto; margin: 4mm; }
    * { box-sizing: border-box; }
    body { font: 12px/1.45 "Courier New", monospace; color: #000; width: 72mm; margin: 0 auto; padding: 4px 0; }
    h1 { font-size: 15px; text-align: center; margin: 0; }
    .c { text-align: center; }
    .r { text-align: right; }
    .b { font-weight: bold; }
    hr { border: 0; border-top: 1px dashed #000; margin: 6px 0; }
    table { width: 100%; border-collapse: collapse; }
    td { vertical-align: top; padding: 1px 0; }
    p { margin: 2px 0; }
    .lunas { text-align: center; font-weight: bold; border: 1px solid #000; padding: 3px; margin-top: 6px; }
    @media screen { body { padding: 16px; background: #fff; } }
  </style>
</head>
<body>
@php
  $rp  = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
  $qty = fn ($n) => rtrim(rtrim(number_format((float) $n, 2, ',', '.'), '0'), ',');
  $tgl = fn ($t) => \Illuminate\Support\Carbon::parse($t)->timezone(config('app.timezone'))->format('d/m/Y H:i');
  $armada = ['truk' => 'Truk Engkel', 'pickup' => 'Pick-up L300', 'ambil_sendiri' => 'Ambil sendiri'];
  $jenis  = ['lunas' => 'Pembayaran lunas', 'dp' => 'DP', 'pelunasan' => 'Pelunasan'];
@endphp

  <h1>{{ $toko['nama'] }}</h1>
  <p class="c">{{ $toko['alamat'] }}</p>
  <p class="c">Telp {{ $toko['telp'] }}</p>
  <hr>

  <p>No    : {{ $nota['nomor'] }}</p>
  <p>Tgl   : {{ $tgl($nota['tanggal']) }}</p>
  <p>Kasir : {{ $kasir }}</p>
  @if ($nota['nama_pembeli'])<p>Pembeli: {{ $nota['nama_pembeli'] }}</p>@endif
  @if ($nota['no_hp'])<p>HP    : {{ $nota['no_hp'] }}</p>@endif
  @if ($nota['alamat_pengiriman'])<p>Kirim : {{ $nota['alamat_pengiriman'] }}</p>@endif
  @if ($nota['catatan_lokasi'])<p>Lokasi: {{ $nota['catatan_lokasi'] }}</p>@endif
  <hr>

  <table>
    @foreach ($nota['items'] as $i)
      <tr><td colspan="2">{{ $i['nama'] }}</td></tr>
      <tr>
        <td>{{ $qty($i['qty']) }} {{ $i['satuan'] }} x {{ $rp($i['harga']) }}</td>
        <td class="r">{{ $rp($i['subtotal']) }}</td>
      </tr>
    @endforeach
  </table>
  <hr>

  <table>
    @if ($nota['ongkir'] > 0)
      <tr><td>Ongkir ({{ $armada[$nota['armada']] ?? $nota['armada'] }})</td><td class="r">{{ $rp($nota['ongkir']) }}</td></tr>
    @elseif ($nota['armada'])
      <tr><td>Pengiriman</td><td class="r">{{ $armada[$nota['armada']] ?? $nota['armada'] }}</td></tr>
    @endif
    @if ($nota['biaya_proteksi'] > 0)
      <tr><td>Proteksi terpal</td><td class="r">{{ $rp($nota['biaya_proteksi']) }}</td></tr>
    @endif
    <tr class="b"><td>TOTAL</td><td class="r">{{ $rp($nota['total']) }}</td></tr>
  </table>
  <hr>

  <table>
    @foreach ($nota['pembayaran'] as $p)
      <tr><td>{{ $jenis[$p['jenis']] ?? $p['jenis'] }} <span style="font-size:10px">{{ $tgl($p['dibayar_pada']) }}</span></td><td class="r">{{ $rp($p['jumlah']) }}</td></tr>
    @endforeach
    <tr class="b"><td>Dibayar</td><td class="r">{{ $rp($nota['dibayar']) }}</td></tr>
    @if ($nota['sisa'] > 0)
      <tr class="b"><td>SISA TAGIHAN</td><td class="r">{{ $rp($nota['sisa']) }}</td></tr>
    @endif
  </table>

  <div class="lunas">{{ $nota['sisa'] > 0 ? 'BELUM LUNAS (DP)' : 'LUNAS' }}</div>

  @if ($nota['catatan_sopir'])
    <hr>
    <p>Catatan sopir: {{ $nota['catatan_sopir'] }}</p>
  @endif

  <hr>
  <p class="c">Terima kasih</p>

  <script>
    window.addEventListener('load', function () { window.print(); });
  </script>
</body>
</html>