<?php

namespace App\Http\Controllers;

use App\Exports\RiwayatNotaExport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class AdminRiwayatController extends Controller
{
    private const STATUS = ['lunas' => 'Lunas', 'dp' => 'DP', 'belum_dibayar' => 'Belum Dibayar'];
    private const ARMADA = ['ambil_sendiri' => 'Ambil Sendiri', 'pickup' => 'Kirim Pickup', 'truk' => 'Kirim Truk'];

    // GET /admin/riwayatbulanan?bulan=2026-11&status=lunas|dp|belum_dibayar&q=kata&per=10&page=1
    public function index(Request $request)
    {
        [$tz, $bulan, $awal, $akhir, $status, $q, $minggu] = $this->parameter($request);
        $per = in_array((int) $request->query('per'), [10, 25, 50, 100], true) ? (int) $request->query('per') : 10;

        // Query dasar: semua nota (semua kasir) pada bulan itu + pencarian
        $dasar = fn () => $this->dasarQuery($awal, $akhir, $q);

        // Daftar nota (ikut filter status), terbaru di atas
        $nota = $this->daftarQuery($dasar(), $status)
            ->orderByDesc('nota.id')
            ->paginate($per)
            ->withQueryString()
            ->through(fn ($n) => [
                'id'           => $n->id,
                'nomor'        => $this->nomor($n),
                'tanggal'      => $n->tanggal,
                'nama_pembeli' => $n->nama_pembeli,
                'armada'       => $n->armada,
                'kasir'        => $n->kasir,
                'item_ringkas' => $n->item_ringkas,
                'total'        => (float) $n->total,
                'dibayar'      => (float) $n->dibayar,
                'sisa'         => round((float) $n->total - (float) $n->dibayar, 2),
                'status_bayar' => $n->status_bayar,
            ]);

        // Ringkasan bulan itu (tidak ikut filter status; ikut pencarian)
        $r = $this->hitungRingkas($dasar());

        // Uang yang benar-benar masuk pada bulan itu (sama dasarnya dengan Z report kasir)
        $uangMasuk = $this->uangMasuk($awal, $akhir);

        // Daftar bulan untuk dropdown: semua bulan yang punya nota + bulan yang sedang dilihat
        $tzSql = preg_replace('/[^A-Za-z0-9_\/+\-]/', '', $tz);
        $bulanList = DB::table('nota')
            ->selectRaw("DISTINCT to_char(tanggal AT TIME ZONE '{$tzSql}', 'YYYY-MM') AS b")
            ->orderByDesc('b')
            ->pluck('b')
            ->all();
        if (! in_array($bulan, $bulanList, true)) {
            $bulanList[] = $bulan;
            rsort($bulanList);
        }
        $daftarBulan = collect($bulanList)->map(fn ($b) => [
            'nilai' => $b,
            'label' => Carbon::createFromFormat('Y-m-d', $b . '-01', $tz)->locale('id')->translatedFormat('F Y'),
        ])->all();

        return view('admin.riwayatbulanan', [
            'nota'   => $nota,
            'ringkas' => [
                'semua'     => (int) $r->semua,
                'lunas'     => (int) $r->lunas,
                'dp'        => (int) $r->dp,
                'belum'     => (int) $r->belum,
                'penjualan' => (float) $r->penjualan,
                'sisa'      => (float) $r->sisa,
                'uang'      => $uangMasuk,
            ],
            'bulan'        => $bulan,
            'labelBulan'   => $awal->copy()->locale('id')->translatedFormat('F Y'),
            'daftarBulan'  => $daftarBulan,
            'bulanSebelum' => $awal->copy()->subMonth()->format('Y-m'),
            'bulanBerikut' => $awal->copy()->addMonth()->format('Y-m'),
            'status'    => $status,
            'q'         => $q,
            'per'       => $per,
            'minggu'     => $minggu,
            'opsiMinggu' => $this->opsiMinggu($bulan, $tz),
        ]);
    }

    // GET /admin/riwayatbulanan/export : unduh Excel semua nota (ikut filter bulan/status/pencarian)
    public function exportBulanan(Request $request)
    {
        [$tz, $bulan, $awal, $akhir, $status, $q, $minggu] = $this->parameter($request);

        $daftar = $this->daftarQuery($this->dasarQuery($awal, $akhir, $q), $status)
            ->orderBy('nota.tanggal')->orderBy('nota.id')
            ->get();

        $baris = [];
        $totalSemua = 0.0;
        $totalBayar = 0.0;

        foreach ($daftar as $i => $n) {
            $total   = (float) $n->total;
            $dibayar = (float) $n->dibayar;
            $totalSemua += $total;
            $totalBayar += $dibayar;

            $baris[] = [
                $i + 1,
                $this->nomor($n),
                Carbon::parse($n->tanggal)->timezone($tz)->format('d-m-Y H:i'),
                $n->nama_pembeli ?: 'Pembeli Umum',
                (string) $n->no_hp,
                $n->kasir,
                self::ARMADA[$n->armada] ?? (string) $n->armada,
                $n->item_ringkas,
                $total,
                $dibayar,
                round($total - $dibayar, 2),
                self::STATUS[$n->status_bayar] ?? $n->status_bayar,
            ];
        }
        $baris[] = ['TOTAL', '', '', '', '', '', '', '', $totalSemua, $totalBayar, round($totalSemua - $totalBayar, 2), ''];

        return Excel::download(new RiwayatNotaExport($baris), 'riwayat-nota-' . $bulan . ($minggu ? '-minggu-' . $minggu : '') . '.xlsx');
    }

    // GET /admin/riwayatbulanan/cetak : halaman rekap bulanan siap cetak
    public function cetakBulanan(Request $request)
    {
        [$tz, $bulan, $awal, $akhir, $status, $q, $minggu] = $this->parameter($request);

        $daftar = $this->daftarQuery($this->dasarQuery($awal, $akhir, $q), $status)
            ->orderBy('nota.tanggal')->orderBy('nota.id')
            ->get()
            ->map(fn ($n) => [
                'nomor'   => $this->nomor($n),
                'tanggal' => Carbon::parse($n->tanggal)->timezone($tz)->format('d/m/Y H:i'),
                'pembeli' => $n->nama_pembeli ?: 'Pembeli Umum',
                'kasir'   => $n->kasir,
                'total'   => (float) $n->total,
                'dibayar' => (float) $n->dibayar,
                'sisa'    => round((float) $n->total - (float) $n->dibayar, 2),
                'status'  => self::STATUS[$n->status_bayar] ?? $n->status_bayar,
            ])
            ->all();

        $r = $this->hitungRingkas($this->dasarQuery($awal, $akhir, $q));

        $filter = [];
        if ($minggu) $filter[] = 'Periode: ' . collect($this->opsiMinggu($bulan, $tz))->firstWhere('nilai', (string) $minggu)['label'];
        if ($status) $filter[] = 'Status: ' . self::STATUS[$status];
        if ($q !== '') $filter[] = 'Pencarian: "' . $q . '"';

        return view('admin.riwayatbulanan_cetak', [
            'daftar'     => $daftar,
            'labelBulan' => $awal->copy()->locale('id')->translatedFormat('F Y'),
            'filter'     => implode(' • ', $filter),
            'ringkas'    => [
                'semua'     => (int) $r->semua,
                'lunas'     => (int) $r->lunas,
                'dp'        => (int) $r->dp,
                'belum'     => (int) $r->belum,
                'penjualan' => (float) $r->penjualan,
                'sisa'      => (float) $r->sisa,
                'uang'      => $this->uangMasuk($awal, $akhir),
            ],
            'totalTabel' => [
                'total'   => array_sum(array_column($daftar, 'total')),
                'dibayar' => array_sum(array_column($daftar, 'dibayar')),
                'sisa'    => array_sum(array_column($daftar, 'sisa')),
            ],
            'dicetak' => Carbon::now($tz)->locale('id')->translatedFormat('d F Y, H:i'),
        ]);
    }

    // GET /admin/nota/{id} : detail untuk modal rincian (JSON, bentuknya sama dengan kasir)
    public function show(int $id): JsonResponse
    {
        return response()->json($this->detail($id));
    }

    // GET /admin/nota/{id}/cetak : struk cetak ulang (memakai tampilan struk kasir)
    public function cetak(int $id)
    {
        $nota = $this->detail($id);

        return view('kasir.struk', [
            'nota'  => $nota,
            'kasir' => $nota['kasir'],
            'toko'  => NotaController::TOKO,
        ]);
    }

    // POST /admin/nota/{id}/lunasi : admin melunasi SISA TAGIHAN nota DP (sekali bayar penuh)
    public function lunasi(Request $request, int $id): JsonResponse
    {
        DB::transaction(function () use ($request, $id) {
            // lockForUpdate: kalau kasir/admin lain melunasi nota yang sama, salah satu menunggu giliran
            $nota = DB::table('nota')->where('id', $id)->lockForUpdate()->first();
            abort_if(! $nota, 404);

            $sisa = round((float) $nota->total - (float) $nota->dibayar, 2);

            if ($sisa <= 0) {
                throw ValidationException::withMessages(['jumlah' => 'Nota ini sudah lunas.']);
            }

            DB::table('nota')->where('id', $id)->increment('dibayar', $sisa);

            // Catat ke tabel pembayaran atas nama admin yang melunasi
            DB::table('pembayaran')->insert([
                'nota_id'  => $id,
                'kasir_id' => $request->user()->id,
                'jenis'    => 'pelunasan',
                'jumlah'   => $sisa,
            ]);
        });

        return response()->json($this->detail($id));
    }

    // ---------------------------------------------------------------- helper

    // Baca bulan, status, dan pencarian dari query string (dipakai tabel, ekspor, dan cetak)
    private function parameter(Request $request): array
    {
        $tz = config('app.timezone');

        // Format "YYYY-MM"; kalau kosong/rusak, pakai bulan ini.
        $bulan = preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', (string) $request->query('bulan'))
            ? $request->query('bulan')
            : Carbon::now($tz)->format('Y-m');

        $awalBulan  = Carbon::createFromFormat('Y-m-d', $bulan . '-01', $tz)->startOfMonth();
        $akhirBulan = $awalBulan->copy()->endOfMonth();

        // Minggu 1-4 (kosong = seluruh bulan). $awal/$akhir = rentang yang dipakai semua query.
        $minggu = in_array((int) $request->query('minggu'), [1, 2, 3, 4], true) ? (int) $request->query('minggu') : null;
        [$awal, $akhir] = $this->rentang($awalBulan, $akhirBulan, $minggu);

        $status = in_array($request->query('status'), ['lunas', 'dp', 'belum_dibayar'], true)
            ? $request->query('status') : null;
        $q = trim((string) $request->query('q', ''));

        return [$tz, $bulan, $awal, $akhir, $status, $q, $minggu];
    }

    // Minggu 1: tgl 1-7, 2: 8-14, 3: 15-21, 4: 22 sampai akhir bulan. null = seluruh bulan.
    private function rentang(Carbon $awalBulan, Carbon $akhirBulan, ?int $minggu): array
    {
        if (! $minggu) {
            return [$awalBulan->copy(), $akhirBulan->copy()];
        }

        $awal  = $awalBulan->copy()->day(($minggu - 1) * 7 + 1)->startOfDay();
        $akhir = $minggu === 4 ? $akhirBulan->copy() : $awal->copy()->addDays(6)->endOfDay();

        return [$awal, $akhir];
    }

    // Pilihan dropdown periode: "Seluruh Bulan (1 - 31 Mei)", "Minggu 1 (1 - 7 Mei)", dst.
    private function opsiMinggu(string $bulan, string $tz): array
    {
        $awalBulan  = Carbon::createFromFormat('Y-m-d', $bulan . '-01', $tz)->startOfMonth()->locale('id');
        $akhirBulan = $awalBulan->copy()->endOfMonth();
        $nama       = $awalBulan->translatedFormat('F');

        $opsi = [['nilai' => '', 'label' => "Seluruh Bulan (1 - {$akhirBulan->day} {$nama})"]];
        foreach ([1, 2, 3, 4] as $m) {
            [$a, $b] = $this->rentang($awalBulan, $akhirBulan, $m);
            $opsi[] = ['nilai' => (string) $m, 'label' => "Minggu {$m} ({$a->day} - {$b->day} {$nama})"];
        }

        return $opsi;
    }

    // Semua nota (semua kasir) pada bulan itu + pencarian
    private function dasarQuery(Carbon $awal, Carbon $akhir, string $q)
    {
        return DB::table('nota')
            ->join('pengguna as u', 'u.id', '=', 'nota.kasir_id')
            ->where('nota.tanggal', '>=', $awal->toIso8601String())
            ->where('nota.tanggal', '<=', $akhir->toIso8601String())
            ->when($q !== '', function ($x) use ($q) {
                $kata = '%' . $q . '%';
                $x->where(function ($w) use ($kata) {
                    $w->whereLike('nota.nama_pembeli', $kata)
                      ->orWhereLike('nota.no_hp', $kata)
                      ->orWhereExists(function ($e) use ($kata) {
                          $e->selectRaw('1')
                            ->from('nota_item as ni')
                            ->leftJoin('produk as p', 'p.id', '=', 'ni.produk_id')
                            ->whereColumn('ni.nota_id', 'nota.id')
                            ->where(fn ($m) => $m->whereLike('p.nama', $kata)
                                                 ->orWhereLike('ni.keterangan', $kata));
                      });
                });
            });
    }

    // Kolom daftar nota + ringkasan barang, ikut filter status
    private function daftarQuery($query, ?string $status)
    {
        return $query
            ->select('nota.*', 'u.username as kasir')
            ->selectRaw("(
                SELECT string_agg(
                    CASE WHEN ni.produk_id IS NULL THEN ni.keterangan
                         ELSE trim_scale(ni.qty)::text || ' ' || p.nama END,
                    ', ' ORDER BY ni.id)
                FROM nota_item ni
                LEFT JOIN produk p ON p.id = ni.produk_id
                WHERE ni.nota_id = nota.id
            ) AS item_ringkas")
            ->when($status, fn ($x) => $x->where('nota.status_bayar', $status));
    }

    private function hitungRingkas($query): object
    {
        return $query->selectRaw("COUNT(*) AS semua,
                COALESCE(SUM(CASE WHEN nota.status_bayar = 'lunas' THEN 1 ELSE 0 END), 0) AS lunas,
                COALESCE(SUM(CASE WHEN nota.status_bayar = 'dp' THEN 1 ELSE 0 END), 0) AS dp,
                COALESCE(SUM(CASE WHEN nota.status_bayar = 'belum_dibayar' THEN 1 ELSE 0 END), 0) AS belum,
                COALESCE(SUM(nota.total), 0) AS penjualan,
                COALESCE(SUM(nota.total - nota.dibayar), 0) AS sisa")
            ->first();
    }

    private function uangMasuk(Carbon $awal, Carbon $akhir): float
    {
        return (float) DB::table('pembayaran')
            ->where('dibayar_pada', '>=', $awal->toIso8601String())
            ->where('dibayar_pada', '<=', $akhir->toIso8601String())
            ->sum('jumlah');
    }

    private function nomor(object $n): string
    {
        return sprintf('INV-%s-%03d', Carbon::parse($n->tanggal)->timezone(config('app.timezone'))->format('Ymd'), $n->id);
    }

    // Data lengkap satu nota (siapa pun kasirnya)
    private function detail(int $id): array
    {
        $nota = DB::table('nota')
            ->join('pengguna as u', 'u.id', '=', 'nota.kasir_id')
            ->where('nota.id', $id)
            ->first(['nota.*', 'u.username as kasir']);
        abort_if(! $nota, 404);

        $items = DB::table('nota_item as ni')
            ->leftJoin('produk as p', 'p.id', '=', 'ni.produk_id')
            ->leftJoin('satuan as s', 's.id', '=', 'p.satuan_id')
            ->where('ni.nota_id', $id)
            ->orderBy('ni.id')
            ->get([
                'ni.id',
                DB::raw('COALESCE(p.nama, ni.keterangan) AS nama'),
                DB::raw("COALESCE(s.nama, '') AS satuan"),
                'ni.harga_satuan', 'ni.qty', 'ni.subtotal',
            ])
            ->map(fn ($i) => [
                'id'       => $i->id,
                'nama'     => $i->nama,
                'satuan'   => $i->satuan,
                'harga'    => (float) $i->harga_satuan,
                'qty'      => (float) $i->qty,
                'subtotal' => (float) $i->subtotal,
            ])
            ->values()
            ->all();

        $pembayaran = DB::table('pembayaran')
            ->where('nota_id', $id)
            ->orderBy('id')
            ->get(['jenis', 'jumlah', 'dibayar_pada'])
            ->map(fn ($p) => [
                'jenis'        => $p->jenis,
                'jumlah'       => (float) $p->jumlah,
                'dibayar_pada' => $p->dibayar_pada,
            ])
            ->values()
            ->all();

        return [
            'id'                => $nota->id,
            'nomor'             => $this->nomor($nota),
            'kasir'             => $nota->kasir,
            'tanggal'           => $nota->tanggal,
            'total'             => (float) $nota->total,
            'dibayar'           => (float) $nota->dibayar,
            'sisa'              => round((float) $nota->total - (float) $nota->dibayar, 2),
            'status_bayar'      => $nota->status_bayar,
            'nama_pembeli'      => $nota->nama_pembeli,
            'no_hp'             => $nota->no_hp,
            'alamat_pengiriman' => $nota->alamat_pengiriman,
            'catatan_lokasi'    => $nota->catatan_lokasi,
            'catatan_sopir'     => $nota->catatan_sopir,
            'armada'            => $nota->armada,
            'ongkir'            => (float) $nota->ongkir,
            'biaya_proteksi'    => (float) $nota->biaya_proteksi,
            'items'             => $items,
            'pembayaran'        => $pembayaran,
        ];
    }
}