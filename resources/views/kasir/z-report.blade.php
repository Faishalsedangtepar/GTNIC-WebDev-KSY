@php
    $rp = fn ($x) => 'Rp ' . number_format((float) $x, 0, ',', '.');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Z-Report {{ $z['periode_label'] }} - {{ $toko['nama'] }}</title>
  @include('partials.favicon')
  <style>
    * { box-sizing: border-box; }
    body { font-family: "Courier New", monospace; font-size: 12px; color: #000; width: 80mm; margin: 0 auto; padding: 6mm 4mm; }
    h1 { font-size: 15px; text-align: center; margin: 0 0 2px; }
    .c { text-align: center; }
    .r { text-align: right; }
    .b { font-weight: bold; }
    hr { border: 0; border-top: 1px dashed #000; margin: 6px 0; }
    table { width: 100%; border-collapse: collapse; }
    td { vertical-align: top; padding: 2px 0; }
    .muted { color: #444; font-size: 11px; }
    @media print { body { width: auto; padding: 0; } @page { margin: 4mm; } }
  </style>
</head>
<body onload="window.print()">
  <h1>{{ $toko['nama'] }}</h1>
  <div class="c muted">{{ $toko['alamat'] }}</div>
  <div class="c b" style="margin-top:4px">Z-REPORT KASIR</div>
  <hr>

  <table>
    <tr><td>Periode</td><td class="r">{{ $z['periode_label'] }}</td></tr>
    <tr><td>Kasir</td><td class="r">{{ $z['kasir'] }}</td></tr>
    <tr><td>Nota dibuat</td><td class="r">{{ $z['jumlah_nota'] }}</td></tr>
  </table>
  <hr>

  <div class="b">RINCIAN OMZET</div>
  <table>
    <tr><td>Lunas ({{ $z['lunas']['trx'] }} trx)</td><td class="r">{{ $rp($z['lunas']['total']) }}</td></tr>
    <tr><td>DP ({{ $z['dp']['trx'] }} trx)</td><td class="r">{{ $rp($z['dp']['total']) }}</td></tr>
    <tr class="b"><td>TOTAL OMZET</td><td class="r">{{ $rp($z['total_omzet']) }}</td></tr>
  </table>
  <hr>

  <div class="c muted">Dicetak {{ $z['dicetak'] }}</div>
</body>
</html>