<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanSheet implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithColumnFormatting, WithStyles
{
    public function __construct(
        private string $judul,
        private array $headings,
        private array $rows,
        private array $kolomUang = []
    ) {}

    public function array(): array { return $this->rows; }
    public function headings(): array { return $this->headings; }
    public function title(): string { return $this->judul; }

    public function columnFormats(): array
    {
        return collect($this->kolomUang)->mapWithKeys(fn ($c) => [$c => '#,##0'])->all();
    }

    public function styles(Worksheet $sheet): array
    {
        $last = count($this->rows) + 1;
        return [
            1       => ['font' => ['bold' => true]],
            $last   => ['font' => ['bold' => true]],
        ];
    }
}