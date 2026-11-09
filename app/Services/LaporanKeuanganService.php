<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanKeuanganService
{
    // Nilai kolom nota.status_bayar yang dianggap lunas (sesuaikan dengan data kamu).
    public const STATUS_LUNAS = 'lunas';

    public function bulan(?string $input): Carbon
    {
        try {
            return Carbon::createFromFormat('!Y-m', $input ?? now()->format('Y-m'))->startOfMonth();
        } catch (\Throwable) {
            return now()->startOfMonth();
        }
    }

    public function ringkasan(Carbon $bulan): array
    {
        $awal  = $bulan->copy()->startOfMonth();
        $akhir = $bulan->copy()->endOfMonth();

        // Omzet = jumlah subtotal item barang (tidak termasuk ongkir / biaya proteksi).
        $subtotal = 'COALESCE(nota_item.subtotal, nota_item.harga_satuan * nota_item.qty)';

        // 1. Omzet per nota -> dikelompokkan per hari di PHP (aman untuk MySQL/SQLite/Postgres).
        $notas = DB::table('nota')
            ->leftJoin('nota_item', 'nota_item.nota_id', '=', 'nota.id')
            ->whereBetween('nota.tanggal', [$awal, $akhir])
            ->groupBy('nota.id', 'nota.tanggal', 'nota.status_bayar')
            ->select('nota.id', 'nota.tanggal', 'nota.status_bayar', DB::raw("COALESCE(SUM($subtotal), 0) as omzet"))
            ->get();

        $harian = $notas
            ->groupBy(fn ($n) => Carbon::parse($n->tanggal)->toDateString())
            ->sortKeys()
            ->map(function ($grup, $tgl) {
                return [
                    'tanggal'     => Carbon::parse($tgl),
                    'jumlah_nota' => $grup->count(),
                    'omzet'       => (float) $grup->sum('omzet'),
                    'belum_lunas' => $grup->filter(
                        fn ($n) => strtolower((string) $n->status_bayar) !== self::STATUS_LUNAS
                    )->count(),
                ];
            })
            ->values();

        // 2. Omzet & laba per kategori (item custom tanpa produk masuk "Lainnya", tanpa hitungan laba).
        $kat = "COALESCE(kategori_produk.nama, 'Lainnya')";
        $kategori = DB::table('nota_item')
            ->join('nota', 'nota.id', '=', 'nota_item.nota_id')
            ->leftJoin('produk', 'produk.id', '=', 'nota_item.produk_id')
            ->leftJoin('kategori_produk', 'kategori_produk.id', '=', 'produk.kategori_id')
            ->whereBetween('nota.tanggal', [$awal, $akhir])
            ->groupBy(DB::raw($kat))
            ->select(
                DB::raw("$kat as nama"),
                DB::raw("SUM($subtotal) as omzet"),
                DB::raw("SUM(CASE WHEN produk.id IS NULL THEN 0 ELSE $subtotal - COALESCE(produk.harga_beli, 0) * nota_item.qty END) as laba")
            )
            ->orderByDesc('omzet')
            ->get();

        $totalOmzet = (float) $harian->sum('omzet');
        $jumlahNota = $notas->count();
        $hari       = $bulan->isSameMonth(now()) ? now()->day : $bulan->daysInMonth;

        return [
            'omzet'          => $totalOmzet,
            'laba'           => (float) $kategori->sum('laba'),
            'jumlah_nota'    => $jumlahNota,
            'belum_lunas'    => (int) $harian->sum('belum_lunas'),
            'hari'           => $hari,
            'rata_omzet'     => $hari > 0 ? $totalOmzet / $hari : 0,
            'rata_nota'      => $hari > 0 ? (int) round($jumlahNota / $hari) : 0,
            'kategori'       => $kategori,
            'harian'         => $harian,
        ];
    }
}