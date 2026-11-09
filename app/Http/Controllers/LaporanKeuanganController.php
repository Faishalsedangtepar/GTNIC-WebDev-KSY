<?php

namespace App\Http\Controllers;

use App\Exports\LaporanKeuanganExport;
use App\Services\LaporanKeuanganService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanKeuanganController extends Controller
{
    public function index(Request $request, LaporanKeuanganService $svc)
    {
        $bulan = $svc->bulan($request->query('bulan'));
        $data  = $svc->ringkasan($bulan);

        // Pilihan bulan: 12 bulan terakhir (+ bulan terpilih jika di luar rentang).
        $opsiBulan = collect(range(0, 11))->map(fn ($i) => now()->startOfMonth()->subMonths($i));
        if (! $opsiBulan->contains(fn ($b) => $b->isSameMonth($bulan))) {
            $opsiBulan->push($bulan->copy());
        }

        return view('admin.admin', compact('bulan', 'data', 'opsiBulan'));
    }

    public function export(Request $request, LaporanKeuanganService $svc)
    {
        $bulan = $svc->bulan($request->query('bulan'));
        $data  = $svc->ringkasan($bulan);

        return Excel::download(
            new LaporanKeuanganExport($data, $bulan),
            'laporan-keuangan-' . $bulan->format('Y-m') . '.xlsx'
        );
    }
}