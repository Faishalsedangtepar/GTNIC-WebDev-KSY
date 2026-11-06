<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nota', function (Blueprint $t) {
            $t->string('nama_pembeli', 100)->nullable();
            $t->string('no_hp', 20)->nullable();
            $t->text('alamat_pengiriman')->nullable();
            $t->string('catatan_lokasi', 200)->nullable();
            $t->string('armada', 15)->default('ambil_sendiri');   // truk | pickup | ambil_sendiri
            $t->decimal('ongkir', 14, 2)->default(0);
            $t->decimal('biaya_proteksi', 14, 2)->default(0);
            $t->string('catatan_sopir', 200)->nullable();
        });

        DB::statement("ALTER TABLE nota ADD CONSTRAINT nota_armada_valid CHECK (armada IN ('truk', 'pickup', 'ambil_sendiri'))");
        DB::statement('ALTER TABLE nota ADD CONSTRAINT nota_biaya_nonneg CHECK (ongkir >= 0 AND biaya_proteksi >= 0)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE nota DROP CONSTRAINT nota_biaya_nonneg');
        DB::statement('ALTER TABLE nota DROP CONSTRAINT nota_armada_valid');

        Schema::table('nota', function (Blueprint $t) {
            $t->dropColumn([
                'nama_pembeli', 'no_hp', 'alamat_pengiriman', 'catatan_lokasi',
                'armada', 'ongkir', 'biaya_proteksi', 'catatan_sopir',
            ]);
        });
    }
};