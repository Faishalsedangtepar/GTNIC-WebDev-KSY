<?php

namespace App\Http\Controllers;

use App\Models\KeranjangItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    // GET /kasir/dashboard : halaman kasir
    public function index(Request $request)
    {
        return view('kasir.kasir', [
            'kasir'    => $request->user()->username,
            'kategori' => DB::table('kategori_produk')->orderBy('id')->get(['id', 'nama']),
            'produk'   => $this->produkList($request),
            'keranjang'=> $this->keranjangPayload($request->user()->id),
        ]);
    }

    // GET /kasir/checkout : halaman pembayaran
    public function checkout(Request $request)
    {
        $keranjang = $this->keranjangPayload($request->user()->id);

        if ($keranjang['items']->isEmpty()) {
            return redirect('/kasir/dashboard');
        }

        return view('kasir.pembayaran', ['keranjang' => $keranjang]);
    }

    // GET /kasir/produk?kategori=ID&q=kata : katalog (JSON)
    public function produk(Request $request): JsonResponse
    {
        return response()->json($this->produkList($request));
    }

    // GET /kasir/keranjang : isi keranjang kasir yang login (JSON)
    public function keranjang(Request $request): JsonResponse
    {
        return response()->json($this->keranjangPayload($request->user()->id));
    }

    // POST /kasir/keranjang  {produk_id, qty?} : tambah produk (produk sama = qty ditambah)
    public function tambah(Request $request): JsonResponse
    {
        $data = $request->validate([
            'produk_id' => ['required', 'integer', 'exists:produk,id'],
            'qty'       => ['nullable', 'numeric', 'gt:0', 'max:99999'],
        ]);

        $kasirId = $request->user()->id;
        $qty     = (float) ($data['qty'] ?? 1);

        $gagal = DB::transaction(function () use ($data, $kasirId, $qty) {
            $stok = (float) DB::table('produk')
                ->where('id', $data['produk_id'])->lockForUpdate()->value('stok');

            $item = KeranjangItem::where('kasir_id', $kasirId)
                ->where('produk_id', $data['produk_id'])->first();

            $baru = ($item ? (float) $item->qty : 0.0) + $qty;

            if ($baru > $stok) {
                return 'Stok tidak cukup. Sisa stok: ' . $this->angka($stok);
            }

            KeranjangItem::updateOrCreate(
                ['kasir_id' => $kasirId, 'produk_id' => $data['produk_id']],
                ['qty' => $baru],
            );

            return null;
        });

        if ($gagal) {
            return response()->json(['message' => $gagal], 422);
        }

        return response()->json($this->keranjangPayload($kasirId), 201);
    }

    // POST /kasir/keranjang/custom  {nama_item, harga, qty?} : ongkir, kuli muat, dll
    public function tambahCustom(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nama_item' => ['required', 'string', 'max:150'],
            'harga'     => ['required', 'numeric', 'gt:0', 'max:999999999999'],
            'qty'       => ['nullable', 'numeric', 'gt:0', 'max:99999'],
        ]);

        KeranjangItem::create([
            'kasir_id'     => $request->user()->id,
            'produk_id'    => null,
            'nama_item'    => $data['nama_item'],
            'harga_custom' => $data['harga'],
            'qty'          => $data['qty'] ?? 1,
        ]);

        return response()->json($this->keranjangPayload($request->user()->id), 201);
    }

    // PATCH /kasir/keranjang/{id}  {qty} : ubah jumlah
    public function ubah(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'qty' => ['required', 'numeric', 'gt:0', 'max:99999'],
        ]);

        $kasirId = $request->user()->id;
        $item    = KeranjangItem::where('kasir_id', $kasirId)->findOrFail($id);

        if ($item->produk_id) {
            $stok = (float) DB::table('produk')->where('id', $item->produk_id)->value('stok');
            if ((float) $data['qty'] > $stok) {
                return response()->json(
                    ['message' => 'Stok tidak cukup. Sisa stok: ' . $this->angka($stok)], 422
                );
            }
        }

        $item->update(['qty' => $data['qty']]);

        return response()->json($this->keranjangPayload($kasirId));
    }

    // DELETE /kasir/keranjang/{id} : hapus satu item
    public function hapus(Request $request, int $id): JsonResponse
    {
        $kasirId = $request->user()->id;

        KeranjangItem::where('kasir_id', $kasirId)->where('id', $id)->delete();

        return response()->json($this->keranjangPayload($kasirId));
    }

    // ---------------------------------------------------------------- helper

    private function produkList(Request $request)
    {
        return DB::table('v_produk')
            ->when($request->filled('kategori'), fn ($q) => $q->where('kategori_id', (int) $request->query('kategori')))
            ->when($request->filled('q'), function ($q) use ($request) {
                $kata = '%' . trim((string) $request->query('q')) . '%';
                $q->where(fn ($w) => $w->whereLike('nama', $kata)->orWhereLike('sku', $kata));
            })
            ->orderBy('nama')
            ->get()
            ->map(fn ($p) => [
                'id'           => $p->id,
                'sku'          => $p->sku,
                'nama'         => $p->nama,
                'kategori_id'  => $p->kategori_id,
                'kategori'     => $p->kategori,
                'satuan'       => $p->satuan,
                'harga'        => (float) $p->harga_jual,
                'stok'         => (float) $p->stok,
                'stok_minimum' => (float) $p->stok_minimum,
                'stok_status'  => $p->stok_status,   // habis | kritis | aman
            ])
            ->values();
    }

    private function keranjangPayload(int $kasirId): array
    {
        $items = DB::table('v_keranjang')
            ->where('kasir_id', $kasirId)
            ->orderBy('item_id')
            ->get()
            ->map(fn ($i) => [
                'id'        => $i->item_id,
                'produk_id' => $i->produk_id,
                'nama'      => $i->nama,
                'satuan'    => $i->satuan,
                'harga'     => (float) $i->harga_jual,
                'qty'       => (float) $i->qty,
                'subtotal'  => (float) $i->total_harga,
                'custom'    => (bool) $i->is_custom,
            ])
            ->values();

        return [
            'items'       => $items,
            'jumlah_item' => $items->sum('qty'),       // "7 Item Material" di UI
            'total'       => $items->sum('subtotal'),
        ];
    }

    private function angka(float $n): string
    {
        return rtrim(rtrim(number_format($n, 2, '.', ''), '0'), '.');
    }

    
}