<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ZReportController extends Controller
{
    // GET /kasir/z-report?dari=Y-m-d&sampai=Y-m-d : rekap uang masuk kasir (JSON untuk modal)
    public function show(Request $request): JsonResponse
    {
        return response()->json($this->data($request));
    }

    // GET /kasir/z-report/cetak?dari=&sampai= : halaman siap print
    public function cetak(Request $request)
    {
        return view('kasir.z-report', [
            'z'    => $this->data($request),
            'toko' => NotaController::TOKO,
        ]);
    }

    private function data(Request $request): array
    {
        $request->validate([
            'dari'   => ['nullable', 'date'],
            'sampai' => ['nullable', 'date'],
        ]);

        $tz      = config('app.timezone');
        $kasirId = $request->user()->id;

        // Tanpa parameter = hari ini. "sampai" kosong = sama dengan "dari".
        $dari   = $request->filled('dari') ? Carbon::parse($request->query('dari'), $tz) : Carbon::now($tz);
        $sampai = $request->filled('sampai') ? Carbon::parse($request->query('sampai'), $tz) : $dari->copy();

        $awal  = $dari->copy()->startOfDay()->toIso8601String();
        $akhir = $sampai->copy()->endOfDay()->toIso8601String();

        // Uang yang MASUK pada rentang itu, per jenis (lunas / dp / pelunasan)
        $masuk = DB::table('pembayaran')
            ->where('kasir_id', $kasirId)
            ->where('dibayar_pada', '>=', $awal)
            ->where('dibayar_pada', '<=', $akhir)
            ->selectRaw('jenis, COUNT(*) AS trx, COALESCE(SUM(jumlah), 0) AS total')
            ->groupBy('jenis')
            ->get()
            ->keyBy('jenis');

        $baris = fn (string $jenis) => [
            'trx'   => (int) ($masuk[$jenis]->trx ?? 0),
            'total' => (float) ($masuk[$jenis]->total ?? 0),
        ];

        $dp        = $baris('dp');
        $pelunasan = $baris('pelunasan');
        $lunas     = $baris('lunas');

        // Pelunasan DP dihitung sebagai "Lunas" (uang masuk yang melunasi nota)
        $lunas = [
            'trx'   => $lunas['trx'] + $pelunasan['trx'],
            'total' => $lunas['total'] + $pelunasan['total'],
        ];

        // Nota yang dibuat pada rentang itu
        $jumlahNota = DB::table('nota')
            ->where('kasir_id', $kasirId)
            ->where('tanggal', '>=', $awal)
            ->where('tanggal', '<=', $akhir)
            ->count();

        // Sisa tagihan DP yang belum lunas milik kasir ini (semua tanggal)
        $piutang = DB::table('nota')
            ->where('kasir_id', $kasirId)
            ->where('status_bayar', 'dp')
            ->selectRaw('COUNT(*) AS trx, COALESCE(SUM(total - dibayar), 0) AS sisa')
            ->first();

        $label = $dari->isSameDay($sampai)
            ? $dari->copy()->locale('id')->translatedFormat('j F Y')
            : $dari->copy()->locale('id')->translatedFormat('j F Y') . ' - ' . $sampai->copy()->locale('id')->translatedFormat('j F Y');

        return [
            'periode_label' => $label,
            'kasir'         => $request->user()->username,
            'dicetak'       => Carbon::now($tz)->locale('id')->translatedFormat('j F Y, H:i') . ' WIB',
            'jumlah_nota'   => $jumlahNota,
            'lunas'         => $lunas,
            'dp'            => $dp,
            'total_omzet'   => $lunas['total'] + $dp['total'],
            'total_trx'     => $lunas['trx'] + $dp['trx'],
            'piutang_dp'    => [
                'trx'  => (int) $piutang->trx,
                'sisa' => (float) $piutang->sisa,
            ],
        ];
    }
}