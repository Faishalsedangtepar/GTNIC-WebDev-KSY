<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanKeuanganExport implements Export, WithMultipleSheets
{
    public function __construct(private array $data, private Carbon $bulan) {}

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

        if ($this->data['ongkir_proteksi'] > 0) {
            $kategori[] = [
                'Ongkir & Proteksi',
                $this->data['ongkir_proteksi'],
                $total > 0 ? round($this->data['ongkir_proteksi'] / $total * 100, 1) : 0,
                0,
            ];
        }
        $kategori[] = ['TOTAL', $total, 100, $this->data['laba']];

        return [
            new LaporanSheet('Rekap Harian', ['Tanggal', 'Jumlah Nota', 'Pendapatan Kotor (Rp)', 'Status Setoran'], $harian, ['C']),
            new LaporanSheet('Per Kategori', ['Kategori', 'Omzet (Rp)', 'Kontribusi (%)', 'Estimasi Laba Kotor (Rp)'], $kategori, ['B', 'D']),
        ];
    }
}