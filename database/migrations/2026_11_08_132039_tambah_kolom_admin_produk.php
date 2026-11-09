<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Kolom baru di produk
        Schema::table('produk', function (Blueprint $t) {
            $t->string('brand', 100)->nullable();
            $t->decimal('harga_beli', 14, 2)->default(0);
            $t->boolean('aktif')->default(true);
        });
        DB::statement('ALTER TABLE produk ADD CONSTRAINT produk_harga_beli_nonneg CHECK (harga_beli >= 0)');

        // 2. Kartu stok
        Schema::create('stok_mutasi', function (Blueprint $t) {
            $t->id();
            $t->foreignId('produk_id')->constrained('produk');
            $t->foreignId('pengguna_id')->constrained('pengguna');
            $t->string('jenis', 10);                 // masuk | keluar
            $t->decimal('qty', 12, 2);
            $t->decimal('stok_sebelum', 12, 2);
            $t->decimal('stok_sesudah', 12, 2);
            $t->string('catatan', 200)->nullable();
            $t->timestampTz('dibuat_pada')->useCurrent();

            $t->index(['produk_id', 'dibuat_pada']);
        });
        DB::statement("ALTER TABLE stok_mutasi ADD CONSTRAINT stok_mutasi_jenis_valid CHECK (jenis IN ('masuk', 'keluar'))");
        DB::statement('ALTER TABLE stok_mutasi ADD CONSTRAINT stok_mutasi_qty_positif CHECK (qty > 0)');

        // 3. v_produk (dipakai kasir) hanya menampilkan produk aktif
        DB::statement("
            CREATE OR REPLACE VIEW v_produk AS
            SELECT p.id, p.sku, p.nama, p.kategori_id, k.nama AS kategori,
                s.nama AS satuan, p.deskripsi, p.harga_jual, p.stok, p.stok_minimum,
                CASE WHEN p.stok <= 0 THEN 'habis'
                        WHEN p.stok <= p.stok_minimum THEN 'kritis'
                        ELSE 'aman' END AS stok_status
            FROM produk p
            JOIN kategori_produk k ON k.id = p.kategori_id
            JOIN satuan s          ON s.id = p.satuan_id
            WHERE p.aktif = true
        ");
    }


    /**
     * Reverse the migrations.
     */
  
    public function down(): void
    {
        // View dikembalikan dulu, karena view lama tidak boleh bergantung pada kolom aktif
        DB::statement("
            CREATE OR REPLACE VIEW v_produk AS
            SELECT p.id, p.sku, p.nama, p.kategori_id, k.nama AS kategori,
                s.nama AS satuan, p.deskripsi, p.harga_jual, p.stok, p.stok_minimum,
                CASE WHEN p.stok <= 0 THEN 'habis'
                        WHEN p.stok <= p.stok_minimum THEN 'kritis'
                        ELSE 'aman' END AS stok_status
            FROM produk p
            JOIN kategori_produk k ON k.id = p.kategori_id
            JOIN satuan s          ON s.id = p.satuan_id
        ");

        Schema::dropIfExists('stok_mutasi');

        DB::statement('ALTER TABLE produk DROP CONSTRAINT IF EXISTS produk_harga_beli_nonneg');
        Schema::table('produk', function (Blueprint $t) {
            $t->dropColumn(['brand', 'harga_beli', 'aktif']);
        });
    }
};
