<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
   public function up(): void
    {
        $bulan = DB::getDriverName() === 'pgsql'
            ? "DATE_TRUNC('month', tanggal)::date"
            : "DATE_FORMAT(tanggal, '%Y-%m-01')";

        DB::statement("
            CREATE VIEW v_laporan_bulanan AS
            SELECT {$bulan}          AS bulan,
                COUNT(*)             AS jumlah_nota,
                SUM(total)           AS total_penjualan,
                SUM(dibayar)         AS total_dibayar,
                SUM(total - dibayar) AS sisa_tagihan
            FROM nota
            GROUP BY {$bulan}
            ORDER BY bulan DESC
        ");
    }
};
