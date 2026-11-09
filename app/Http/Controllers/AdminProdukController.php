<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminProdukController extends Controller
{
    // GET /admin/laporanstok?kategori=ID&q=kata&per=10&page=1 : halaman data stok barang
    public function index(Request $request)
    {
        $per        = in_array((int) $request->query('per'), [10, 25, 50, 100], true) ? (int) $request->query('per') : 10;
        $kategoriId = $request->filled('kategori') ? (int) $request->query('kategori') : null;
        $q          = trim((string) $request->query('q', ''));

        $produk = DB::table('produk as p')
            ->join('kategori_produk as k', 'k.id', '=', 'p.kategori_id')
            ->join('satuan as s', 's.id', '=', 'p.satuan_id')
            ->where('p.aktif', true)
            ->when($kategoriId, fn ($x) => $x->where('p.kategori_id', $kategoriId))
            ->when($q !== '', function ($x) use ($q) {
                $kata = '%' . $q . '%';
                $x->where(fn ($w) => $w->whereLike('p.nama', $kata)
                    ->orWhereLike('p.sku', $kata)
                    ->orWhereLike('p.brand', $kata));
            })
            ->orderBy('p.nama')
            ->select('p.*', 'k.nama as kategori', 's.nama as satuan')
            ->paginate($per)
            ->withQueryString();

        // Kartu ringkasan di atas tabel (selalu semua produk aktif, tidak ikut filter)
        $ringkas = DB::table('produk')
            ->where('aktif', true)
            ->selectRaw('COUNT(*) AS total,
                COALESCE(SUM(CASE WHEN stok <= 0 THEN 1 ELSE 0 END), 0) AS habis,
                COALESCE(SUM(CASE WHEN stok > 0 AND stok <= stok_minimum THEN 1 ELSE 0 END), 0) AS kritis')
            ->first();
        $ringkas->tersedia = $ringkas->total - $ringkas->habis - $ringkas->kritis;

        // Chip kategori + jumlah produk aktif per kategori
        $kategori = DB::table('kategori_produk as k')
            ->leftJoin('produk as p', function ($j) {
                $j->on('p.kategori_id', '=', 'k.id')->where('p.aktif', '=', true);
            })
            ->groupBy('k.id', 'k.nama')
            ->orderBy('k.id')
            ->select('k.id', 'k.nama', DB::raw('COUNT(p.id) AS jumlah'))
            ->get();

        return view('admin.laporanstok', [
            'produk'     => $produk,
            'ringkas'    => $ringkas,
            'kategori'   => $kategori,
            'satuan'     => DB::table('satuan')->orderBy('nama')->get(['id', 'nama']),
            // Daftar semua barang aktif untuk kotak pencarian di modal "Tambah Stok Barang"
            'pilihanProduk' => DB::table('produk as p')
                ->join('satuan as s', 's.id', '=', 'p.satuan_id')
                ->where('p.aktif', true)
                ->orderBy('p.nama')
                ->get(['p.id', 'p.sku', 'p.nama', 'p.stok', 's.nama as satuan']),
            'q'          => $q,
            'per'        => $per,
            'kategoriId' => $kategoriId,
        ]);
    }

    // POST /admin/produk : tambah produk baru
    public function store(Request $request): RedirectResponse
    {
        // SKU selalu huruf besar supaya "smn-001" dan "SMN-001" dianggap sama.
        $request->merge(['sku' => strtoupper(trim((string) $request->input('sku')))]);

        $data = $request->validate(
            $this->aturan() + [
                'sku'  => ['required', 'string', 'max:30', Rule::unique('produk', 'sku')],
                'stok' => ['required', 'numeric', 'min:0', 'max:9999999'],
            ],
            ['sku.unique' => 'Kode barang ini sudah dipakai (termasuk barang yang pernah dihapus).']
        );

        $produk = DB::transaction(function () use ($data, $request) {
            $data['stok_minimum'] = $data['stok_minimum'] ?? 10;
            $produk = Produk::create($data);

            // Stok awal ikut dicatat di kartu stok
            if ((float) $data['stok'] > 0) {
                DB::table('stok_mutasi')->insert([
                    'produk_id'    => $produk->id,
                    'pengguna_id'  => $request->user()->id,
                    'jenis'        => 'masuk',
                    'qty'          => $data['stok'],
                    'stok_sebelum' => 0,
                    'stok_sesudah' => $data['stok'],
                    'catatan'      => 'Stok awal',
                ]);
            }

            return $produk;
        });

        return back()->with('sukses', "Barang \"{$produk->nama}\" berhasil ditambahkan.");
    }

    // PUT /admin/produk/{id} : ubah data produk (stok TIDAK diubah di sini, pakai penyesuaian stok)
    public function update(Request $request, int $id): RedirectResponse
    {
        $produk = Produk::where('aktif', true)->findOrFail($id);

        $data = $request->validate($this->aturan());
        $data['stok_minimum'] = $data['stok_minimum'] ?? 10;

        $produk->update($data);

        return back()->with('sukses', "Data \"{$produk->nama}\" berhasil diperbarui.");
    }

    // POST /admin/produk/{id}/stok : tambah/kurangi stok + catat ke kartu stok
    public function sesuaikanStok(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'jenis'   => ['required', Rule::in(['masuk', 'keluar'])],
            'qty'     => ['required', 'numeric', 'gt:0', 'max:9999999'],
            'catatan' => ['nullable', 'string', 'max:200'],
        ]);

        $qty = (float) $data['qty'];

        $hasil = DB::transaction(function () use ($id, $data, $qty, $request) {
            // lockForUpdate: kalau kasir sedang checkout produk yang sama, kita antre
            $p = DB::table('produk')->where('id', $id)->where('aktif', true)
                ->lockForUpdate()->first(['id', 'nama', 'stok']);

            if (! $p) {
                return ['gagal' => 'Barang tidak ditemukan.'];
            }

            $sebelum = (float) $p->stok;
            $sesudah = $data['jenis'] === 'masuk' ? $sebelum + $qty : $sebelum - $qty;

            if ($sesudah < 0) {
                return ['gagal' => 'Stok tidak cukup untuk dikurangi. Stok saat ini: ' . $this->angka($sebelum)];
            }

            DB::table('produk')->where('id', $id)->update(['stok' => $sesudah]);

            DB::table('stok_mutasi')->insert([
                'produk_id'    => $id,
                'pengguna_id'  => $request->user()->id,
                'jenis'        => $data['jenis'],
                'qty'          => $qty,
                'stok_sebelum' => $sebelum,
                'stok_sesudah' => $sesudah,
                'catatan'      => $data['catatan'] ?? null,
            ]);

            return ['nama' => $p->nama, 'sesudah' => $sesudah];
        });

        if (isset($hasil['gagal'])) {
            return back()->withErrors(['stok' => $hasil['gagal']]);
        }

        return back()->with('sukses', "Stok \"{$hasil['nama']}\" sekarang " . $this->angka($hasil['sesudah']) . '.');
    }

    // DELETE /admin/produk/{id} : nonaktifkan produk (data nota lama tetap aman)
    public function destroy(int $id): RedirectResponse
    {
        $produk = Produk::where('aktif', true)->findOrFail($id);

        DB::transaction(function () use ($produk) {
            $produk->update(['aktif' => false]);
            // Buang dari keranjang kasir mana pun supaya tidak ikut ter-checkout
            DB::table('keranjang_item')->where('produk_id', $produk->id)->delete();
        });

        return back()->with('sukses', "Barang \"{$produk->nama}\" dihapus dari daftar.");
    }

    // Aturan validasi yang sama untuk tambah dan ubah
    private function aturan(): array
    {
        return [
            'nama'         => ['required', 'string', 'max:150'],
            'brand'        => ['nullable', 'string', 'max:100'],
            'kategori_id'  => ['required', 'integer', 'exists:kategori_produk,id'],
            'satuan_id'    => ['required', 'integer', 'exists:satuan,id'],
            'stok_minimum' => ['nullable', 'numeric', 'min:0', 'max:9999999'],
            'harga_beli'   => ['required', 'numeric', 'min:0', 'max:9999999999'],
            'harga_jual'   => ['required', 'numeric', 'min:0', 'max:9999999999'],
        ];
    }

    private function angka(float $n): string
    {
        return rtrim(rtrim(number_format($n, 2, ',', '.'), '0'), ',');
    }
}