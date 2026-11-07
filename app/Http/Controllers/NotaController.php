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
    private const DP_MINIMAL     = 0.30;   // DP minimal 30% dari total
    private const BIAYA_PROTEKSI = 5000;   // Proteksi terpal (opsional di UI)
    private const TARIF_ONGKIR   = ['truk' => 110000, 'pickup' => 65000, 'ambil_sendiri' => 0];

    // POST /kasir/nota : checkout (keranjang -> nota)
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'armada'            => ['required', 'in:truk,pickup,ambil_sendiri'],
            'proteksi'          => ['nullable', 'boolean'],
            'metode'            => ['required', 'in:lunas,dp'],
            'dibayar'           => ['required_if:metode,dp', 'nullable', 'numeric', 'gt:0'],
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
                $dibayar = (float) $data['dibayar'];
                $minimal = ceil($total * self::DP_MINIMAL);

                if ($dibayar < $minimal) {
                    throw ValidationException::withMessages([
                        'dibayar' => 'DP minimal Rp ' . number_format($minimal, 0, ',', '.') . ' (30% dari total).',
                    ]);
                }
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

    // GET /kasir/nota?status=lunas|dp|tempo|belum_dibayar&q=&dari=&sampai= : riwayat
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'status' => ['nullable', 'in:lunas,dp,tempo,belum_dibayar'],
            'dari'   => ['nullable', 'date'],
            'sampai' => ['nullable', 'date'],
        ]);

        $status = $request->query('status') === 'tempo' ? 'dp' : $request->query('status');

        $hal = DB::table('nota')
            ->select('nota.*')
            ->selectSub(
                fn ($s) => $s->from('nota_item')->selectRaw('COALESCE(SUM(qty), 0)')->whereColumn('nota_item.nota_id', 'nota.id'),
                'jumlah_item'
            )
            ->where('kasir_id', $request->user()->id)
            ->when($status, fn ($q) => $q->where('status_bayar', $status))
            ->when($request->filled('q'), function ($q) use ($request) {
                $kata = trim((string) $request->query('q'));
                $q->where(function ($w) use ($kata) {
                    $w->whereLike('nama_pembeli', "%{$kata}%");
                    if (ctype_digit($kata)) {
                        $w->orWhere('id', (int) $kata);
                    }
                });
            })
            ->when($request->filled('dari'), fn ($q) => $q->whereDate('tanggal', '>=', $request->query('dari')))
            ->when($request->filled('sampai'), fn ($q) => $q->whereDate('tanggal', '<=', $request->query('sampai')))
            ->orderByDesc('id')
            ->paginate(30)
            ->through(fn ($n) => $this->ringkas($n) + [
                'tanggal'      => $n->tanggal,
                'nama_pembeli' => $n->nama_pembeli,
                'armada'       => $n->armada,
                'jumlah_item'  => (float) $n->jumlah_item,
            ]);

        return response()->json($hal);
    }

    // GET /kasir/nota/{id} : detail untuk modal / cetak
    public function show(Request $request, int $id): JsonResponse
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
            ]);

        return response()->json($this->ringkas($nota) + [
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
        });

        return response()->json($this->ringkas(DB::table('nota')->where('id', $id)->first()));
    }

    // ---------------------------------------------------------------- helper

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