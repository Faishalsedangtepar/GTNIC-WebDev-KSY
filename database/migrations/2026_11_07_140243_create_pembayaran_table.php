<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Satu baris = satu kali uang masuk untuk sebuah nota.
//   lunas     : bayar penuh saat checkout
//   dp        : DP saat checkout
//   pelunasan : pelunasan sisa tagihan DP (bisa di hari berbeda dari nota)
// Z-Report dan Total Omzet dihitung dari tabel ini, bukan dari nota.dibayar,
// supaya pelunasan hari ini untuk nota kemarin masuk ke kas hari ini.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $t) {
            $t->id();
            $t->foreignId('nota_id')->constrained('nota')->cascadeOnDelete();
            $t->foreignId('kasir_id')->constrained('pengguna');
            $t->string('jenis', 10);                       // lunas | dp | pelunasan
            $t->decimal('jumlah', 14, 2);
            $t->timestampTz('dibayar_pada')->useCurrent();

            $t->index(['kasir_id', 'dibayar_pada']);
            $t->index('nota_id');
        });

        DB::statement("ALTER TABLE pembayaran ADD CONSTRAINT pembayaran_jenis_valid CHECK (jenis IN ('lunas', 'dp', 'pelunasan'))");
        DB::statement('ALTER TABLE pembayaran ADD CONSTRAINT pembayaran_jumlah_positif CHECK (jumlah > 0)');

        // Nota lama: satu baris per nota dari nilai dibayar saat ini.
        DB::statement("
            INSERT INTO pembayaran (nota_id, kasir_id, jenis, jumlah, dibayar_pada)
            SELECT id, kasir_id,
                   CASE WHEN dibayar >= total THEN 'lunas' ELSE 'dp' END,
                   dibayar, tanggal
            FROM nota
            WHERE dibayar > 0
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }

};