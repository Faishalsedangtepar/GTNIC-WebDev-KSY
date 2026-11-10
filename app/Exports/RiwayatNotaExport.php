<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

// Export Riwayat Nota (admin). Kolom: A No, B No. Nota, C Tanggal, D Pembeli, E No. HP,
// F Kasir, G Pengiriman, H Barang, I Total, J Dibayar, K Sisa, L Status.
class RiwayatNotaExport extends DefaultValueBinder implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithColumnFormatting, WithStyles, WithCustomValueBinder
{
    public function __construct(private array $rows) {}

    public function array(): array { return $this->rows; }

    public function title(): string { return 'Riwayat Nota'; }

    public function headings(): array
    {
        return ['No', 'No. Nota', 'Tanggal', 'Pembeli', 'No. HP', 'Kasir', 'Pengiriman', 'Barang', 'Total (Rp)', 'Dibayar (Rp)', 'Sisa (Rp)', 'Status'];
    }

    public function columnFormats(): array
    {
        return ['I' => '#,##0', 'J' => '#,##0', 'K' => '#,##0'];
    }

    // Kolom E (No. HP) dipaksa teks supaya angka 0 di depan tidak hilang.
    public function bindValue(Cell $cell, $value): bool
    {
        if ($cell->getColumn() === 'E' && $value !== null && $value !== '') {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function styles(Worksheet $sheet): array
    {
        $last = count($this->rows) + 1;   // baris TOTAL
        return [
            1     => ['font' => ['bold' => true]],
            $last => ['font' => ['bold' => true]],
        ];
    }
}