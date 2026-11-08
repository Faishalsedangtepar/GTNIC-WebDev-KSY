<?php

namespace App\Http\Controllers;

use App\Models\KeranjangItem;
use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class NotaController extends Controller
{
    private const DP_MINIMAL     = 0.30;   // DP tetap 30% dari total harga barang (tanpa ongkir & proteksi)
    private const BIAYA_PROTEKSI = 5000;   // Proteksi terpal (opsional di UI)
    private const TARIF_ONGKIR   = ['truk' => 110000, 'pickup' => 65000, 'ambil_sendiri' => 0];

    // Identitas toko di header struk (ganti dengan data sebenarnya)
    public const TOKO = ['nama' => 'Aura Material', 'alamat' => 'Jl. Contoh No. 1, Yogyakarta', 'telp' => '0274-000000'];

    // POST /kasir/nota : checkout (keranjang -> nota)
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'armada'            => ['required', 'in:truk,pickup,ambil_sendiri'],
            'proteksi'          => ['nullable', 'boolean'],
            'metode'            => ['required', 'in:lunas,dp'],
            'uang_diterima'     => ['nullable', 'numeric', 'gt:0'],   // uang tunai dari pelanggan (untuk kembalian)
            'nama_pembeli'      => ['nullable', 'required_if:metode,dp', 'required_unless:armada,ambil_sendiri', 'string', 'max:100'],
            'no_hp'             => ['nullable', 'required_if:metode,dp', 'required_unless:armada,ambil_sendiri', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'alamat_pengiriman' => ['nullable', 'required_unless:armada,ambil_sendiri', 'string', 'max:500'],
            'catatan_lokasi'    => ['nullable', 'string', 'max:200'],
            'catatan_sopir'     => ['nullable', 'string', 'max:200'],
        ]);

        $kasirId = $request->user()->id;

        $notaId = DB::transaction(function () use ($data, $kasirId) {
            $items = KeranjangItem::where('kasir_id', $kasirId)->orderBy('id')->lockForUpdate()->get();

            if ($items->isEmpty()) {
                throw ValidationException::withMessages(['keranjang' => 'Keranjang kosong.']);
            }

            // Kunci baris produk (urut id agar tidak saling deadlock antar kasir)
            $produk = DB::table('produk')
                ->whereIn('id', $items->pluck('produk_id')->filter()->unique()->all())
                ->orderBy('id')->lockForUpdate()->get()->keyBy('id');

            $baris    = [];
            $galat    = [];
            $subtotal = 0.0;

            foreach ($items as $it) {
                $qty = (float) $it->qty;

                if ($it->produk_id) {
                    $p = $produk->get($it->produk_id);

                    if (! $p) {
                        $galat[] = 'Ada produk di keranjang yang sudah tidak tersedia.';
                        continue;
                    }
                    if ($qty > (float) $p->stok) {
                        $galat[] = "Stok {$p->nama} tidak cukup (sisa " . $this->angka((float) $p->stok) . ').';
                        continue;
                    }

                    $harga   = (float) $p->harga_jual;   // harga dari DB, bukan dari browser
                    $baris[] = ['produk_id' => $p->id, 'keterangan' => null, 'harga_satuan' => $harga, 'qty' => $qty];
                } else {
                    $harga   = (float) $it->harga_custom;
                    $baris[] = ['produk_id' => null, 'keterangan' => $it->nama_item, 'harga_satuan' => $harga, 'qty' => $qty];
                }

                $subtotal += $harga * $qty;
            }

            if ($galat) {
                throw ValidationException::withMessages(['stok' => $galat]);
            }

            $ongkir   = self::TARIF_ONGKIR[$data['armada']];
            $proteksi = ! empty($data['proteksi']) ? self::BIAYA_PROTEKSI : 0;
            $total    = round($subtotal + $ongkir + $proteksi, 2);

            if ($data['metode'] === 'lunas') {
                $dibayar = $total;
            } else {
                // DP dihitung server: 30% dari total harga barang yang dibeli
                $dibayar = ceil($subtotal * self::DP_MINIMAL);

                if ($dibayar >= $total) {
                    throw ValidationException::withMessages([
                        'dibayar' => 'DP harus kurang dari total. Pilih Lunas untuk bayar penuh.',
                    ]);
                }
            }

            if (isset($data['uang_diterima']) && (float) $data['uang_diterima'] < $dibayar) {
                throw ValidationException::withMessages([
                    'uang_diterima' => 'Uang diterima kurang dari tagihan (Rp ' . number_format($dibayar, 0, ',', '.') . ').',
                ]);
            }

            $notaId = DB::table('nota')->insertGetId([
                'kasir_id'          => $kasirId,
                'total'             => $total,
                'dibayar'           => $dibayar,
                'nama_pembeli'      => $data['nama_pembeli'] ?? null,
                'no_hp'             => $data['no_hp'] ?? null,
                'alamat_pengiriman' => $data['alamat_pengiriman'] ?? null,
                'catatan_lokasi'    => $data['catatan_lokasi'] ?? null,
                'armada'            => $data['armada'],
                'ongkir'            => $ongkir,
                'biaya_proteksi'    => $proteksi,
                'catatan_sopir'     => $data['catatan_sopir'] ?? null,
            ]);

            DB::table('nota_item')->insert(
                array_map(fn ($b) => ['nota_id' => $notaId] + $b, $baris)
            );

            // Catat uang masuk ke tabel pembayaran (dasar Total Omzet & Z-Report)
            DB::table('pembayaran')->insert([
                'nota_id'  => $notaId,
                'kasir_id' => $kasirId,
                'jenis'    => $data['metode'],   // 'lunas' atau 'dp'
                'jumlah'   => $dibayar,
            ]);

            foreach ($baris as $b) {
                if ($b['produk_id']) {
                    DB::table('produk')->where('id', $b['produk_id'])->decrement('stok', $b['qty']);
                }
            }

            KeranjangItem::where('kasir_id', $kasirId)->delete();

            return $notaId;
        });

        $nota = DB::table('nota')->where('id', $notaId)->first();

        $kembalian = isset($data['uang_diterima'])
            ? round((float) $data['uang_diterima'] - (float) $nota->dibayar, 2)
            : 0;

        return response()->json($this->ringkas($nota) + ['kembalian' => $kembalian], 201);
    }

    // GET /kasir/nota?status=lunas|dp|tempo|belum_dibayar&q=&dari=&sampai= : daftar riwayat
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'status' => ['nullable', 'in:lunas,dp,tempo,belum_dibayar'],
            'q'      => ['nullable', 'string', 'max:100'],
            'dari'   => ['nullable', 'date'],
            'sampai' => ['nullable', 'date'],
        ]);

        $status = $request->query('status') === 'tempo' ? 'dp' : $request->query('status');

        $hal = $this->dasar($request)
            ->select('nota.*')
            ->selectSub(
                fn ($s) => $s->from('nota_item')->selectRaw('COALESCE(SUM(qty), 0)')->whereColumn('nota_item.nota_id', 'nota.id'),
                'jumlah_item'
            )
            ->selectRaw("(
                SELECT string_agg(
                    CASE WHEN ni.produk_id IS NULL THEN ni.keterangan
                         ELSE trim_scale(ni.qty)::text || ' ' || p.nama END,
                    ', ' ORDER BY ni.id)
                FROM nota_item ni
                LEFT JOIN produk p ON p.id = ni.produk_id
                WHERE ni.nota_id = nota.id
            ) AS item_ringkas")
            ->when($status, fn ($q) => $q->where('nota.status_bayar', $status))
            ->orderByDesc('nota.id')
            ->paginate(30)
            ->through(fn ($n) => $this->ringkas($n) + [
                'tanggal'      => $n->tanggal,
                'nama_pembeli' => $n->nama_pembeli,
                'armada'       => $n->armada,
                'jumlah_item'  => (float) $n->jumlah_item,
                'item_ringkas' => $n->item_ringkas,
            ]);

        return response()->json($hal);
    }

    // GET /kasir/nota/ringkasan?dari=&sampai=&q= : Total Omzet + badge tab
    public function ringkasan(Request $request): JsonResponse
    {
        $request->validate([
            'q'      => ['nullable', 'string', 'max:100'],
            'dari'   => ['nullable', 'date'],
            'sampai' => ['nullable', 'date'],
        ]);

        // Badge tab: jumlah nota (ikut tanggal & pencarian, TIDAK ikut status)
        $hitung = $this->dasar($request)
            ->selectRaw("COUNT(*) AS semua,
                         COUNT(*) FILTER (WHERE nota.status_bayar = 'lunas') AS lunas,
                         COUNT(*) FILTER (WHERE nota.status_bayar = 'dp') AS dp")
            ->first();

        // Omzet: uang yang MASUK pada rentang tanggal (dari tabel pembayaran)
        [$awal, $akhir] = $this->rentang($request);

        $omzet = DB::table('pembayaran')
            ->where('kasir_id', $request->user()->id)
            ->when($awal,  fn ($q) => $q->where('dibayar_pada', '>=', $awal))
            ->when($akhir, fn ($q) => $q->where('dibayar_pada', '<=', $akhir))
            ->sum('jumlah');

        return response()->json([
            'total_omzet' => (float) $omzet,
            'semua'       => (int) $hitung->semua,
            'lunas'       => (int) $hitung->lunas,
            'dp'          => (int) $hitung->dp,
        ]);
    }

    // GET /kasir/nota/{id} : detail untuk modal "Rincian" (JSON)
    public function show(Request $request, int $id): JsonResponse
    {
        return response()->json($this->detail($request, $id));
    }

    // GET /kasir/nota/{id}/cetak : struk siap print (HTML)
    public function cetak(Request $request, int $id)
    {
        return view('kasir.struk', [
            'nota'  => $this->detail($request, $id),
            'kasir' => $request->user()->username,
            'toko'  => self::TOKO,
        ]);
    }

    // POST /kasir/nota/{id}/lunasi  {jumlah?} : pelunasan sisa tagihan DP
    public function lunasi(Request $request, int $id): JsonResponse
    {
        $data = $request->validate(['jumlah' => ['nullable', 'numeric', 'gt:0']]);

        DB::transaction(function () use ($request, $id, $data) {
            $nota = DB::table('nota')
                ->where('id', $id)->where('kasir_id', $request->user()->id)
                ->lockForUpdate()->first();

            abort_if(! $nota, 404);

            $sisa = round((float) $nota->total - (float) $nota->dibayar, 2);

            if ($sisa <= 0) {
                throw ValidationException::withMessages(['jumlah' => 'Nota ini sudah lunas.']);
            }

            $jumlah = isset($data['jumlah']) ? (float) $data['jumlah'] : $sisa;

            if ($jumlah > $sisa) {
                throw ValidationException::withMessages([
                    'jumlah' => 'Jumlah melebihi sisa tagihan (Rp ' . number_format($sisa, 0, ',', '.') . ').',
                ]);
            }

            DB::table('nota')->where('id', $id)->increment('dibayar', $jumlah);

            // Catat pelunasan ke tabel pembayaran
            DB::table('pembayaran')->insert([
                'nota_id'  => $id,
                'kasir_id' => $request->user()->id,
                'jenis'    => 'pelunasan',
                'jumlah'   => $jumlah,
            ]);
        });

        return response()->json($this->ringkas(DB::table('nota')->where('id', $id)->first()));
    }

    // ---------------------------------------------------------------- helper

    // Data lengkap satu nota milik kasir yang login (dipakai show & cetak)
    private function detail(Request $request, int $id): array
    {
        $nota = DB::table('nota')->where('id', $id)->where('kasir_id', $request->user()->id)->first();
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

        // Riwayat uang masuk nota ini (DP, pelunasan, dst) untuk rincian & struk
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

        return $this->ringkas($nota) + [
            'tanggal'           => $nota->tanggal,
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

    // Query dasar riwayat: milik kasir login + rentang tanggal + pencarian
    private function dasar(Request $request)
    {
        [$awal, $akhir] = $this->rentang($request);

        return DB::table('nota')
            ->where('nota.kasir_id', $request->user()->id)
            ->when($awal,  fn ($q) => $q->where('nota.tanggal', '>=', $awal))
            ->when($akhir, fn ($q) => $q->where('nota.tanggal', '<=', $akhir))
            ->when($request->filled('q'), function ($q) use ($request) {
                $kata = trim((string) $request->query('q'));

                $q->where(function ($w) use ($kata) {
                    $w->whereLike('nota.nama_pembeli', "%{$kata}%")
                      ->orWhereExists(function ($e) use ($kata) {
                          $e->selectRaw('1')
                            ->from('nota_item as ni')
                            ->leftJoin('produk as p', 'p.id', '=', 'ni.produk_id')
                            ->whereColumn('ni.nota_id', 'nota.id')
                            ->where(fn ($m) => $m->whereLike('p.nama', "%{$kata}%")
                                                 ->orWhereLike('ni.keterangan', "%{$kata}%"));
                      });

                    // "18", "NOTA-018", "INV-018" -> cari berdasarkan id nota
                    if (preg_match('/^(?:nota|inv)?[-\s]*(\d+)$/i', $kata, $m)) {
                        $w->orWhere('nota.id', (int) $m[1]);
                    }
                });
            });
    }

    // "2026-11-07" -> [awal hari, akhir hari] di zona waktu aplikasi, format ISO beroffset
    private function rentang(Request $request): array
    {
        $tz = config('app.timezone');

        return [
            $request->filled('dari')
                ? Carbon::parse($request->query('dari'), $tz)->startOfDay()->toIso8601String() : null,
            $request->filled('sampai')
                ? Carbon::parse($request->query('sampai'), $tz)->endOfDay()->toIso8601String() : null,
        ];
    }

    private function ringkas(object $n): array
    {
        return [
            'id'           => $n->id,
            'nomor'        => sprintf('INV-%s-%03d', Carbon::parse($n->tanggal)->timezone(config('app.timezone'))->format('Ymd'), $n->id),
            'total'        => (float) $n->total,
            'dibayar'      => (float) $n->dibayar,
            'sisa'         => round((float) $n->total - (float) $n->dibayar, 2),
            'status_bayar' => $n->status_bayar,   // lunas | dp | belum_dibayar
        ];
    }

    private function angka(float $n): string
    {
        return rtrim(rtrim(number_format($n, 2, '.', ''), '0'), '.');
    }
}