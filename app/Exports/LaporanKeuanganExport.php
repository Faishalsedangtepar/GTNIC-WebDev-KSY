<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanKeuanganExport implements FromArray, WithMultipleSheets
{
    public function __construct(private array $data, private Carbon $bulan) {}

    // Dibutuhkan agar class ini dikenali sebagai export; isi sebenarnya ada di sheets().
    public function array(): array
    {
        return [];
    }

    public function sheets(): array
    {
        $harian = $this->data['harian']->map(fn ($h) => [
            $h['tanggal']->format('d-m-Y'),
            $h['jumlah_nota'],
            $h['omzet'],
            $h['belum_lunas'] > 0 ? $h['belum_lunas'] . ' nota belum lunas' : 'Masuk Kasir',
        ])->all();
        $harian[] = ['TOTAL', $this->data['jumlah_nota'], $this->data['omzet'], ''];

        $total = $this->data['omzet'];
        $kategori = $this->data['kategori']->map(fn ($k) => [
            $k->nama,
            (float) $k->omzet,
            $total > 0 ? round($k->omzet / $total * 100, 1) : 0,
            (float) $k->laba,
        ])->all();
        $kategori[] = ['TOTAL', $total, 100, $this->data['laba']];

        return [
            new LaporanSheet('Rekap Harian', ['Tanggal', 'Jumlah Nota', 'Pendapatan Kotor (Rp)', 'Status Setoran'], $harian, ['C']),
            new LaporanSheet('Per Kategori', ['Kategori', 'Omzet (Rp)', 'Kontribusi (%)', 'Estimasi Laba Kotor (Rp)'], $kategori, ['B', 'D']),
        ];
    }
}