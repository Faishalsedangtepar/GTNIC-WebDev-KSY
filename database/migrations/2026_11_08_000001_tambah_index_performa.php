<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Index untuk query yang paling sering dipakai (riwayat, ringkasan, Z-Report):
//   WHERE kasir_id = ? AND tanggal BETWEEN ? AND ?
// Aman dijalankan di MySQL maupun PostgreSQL, dan aman diulang (dicek dulu sebelum dibuat).
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasIndex('nota', 'nota_kasir_tanggal_index')) {
            Schema::table('nota', function (Blueprint $t) {
                $t->index(['kasir_id', 'tanggal'], 'nota_kasir_tanggal_index');
            });
        }

        // MySQL otomatis membuat index untuk foreign key; PostgreSQL TIDAK.
        // Tanpa ini, ringkasan item dan jumlah item per nota di riwayat memindai seluruh nota_item.
        if (DB::getDriverName() === 'pgsql' && ! Schema::hasIndex('nota_item', 'nota_item_nota_id_index')) {
            Schema::table('nota_item', function (Blueprint $t) {
                $t->index('nota_id', 'nota_item_nota_id_index');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasIndex('nota', 'nota_kasir_tanggal_index')) {
            Schema::table('nota', function (Blueprint $t) {
                $t->dropIndex('nota_kasir_tanggal_index');
            });
        }

        if (DB::getDriverName() === 'pgsql' && Schema::hasIndex('nota_item', 'nota_item_nota_id_index')) {
            Schema::table('nota_item', function (Blueprint $t) {
                $t->dropIndex('nota_item_nota_id_index');
            });
        }
    }
};
