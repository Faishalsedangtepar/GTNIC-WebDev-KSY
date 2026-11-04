<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('DROP VIEW IF EXISTS v_keranjang');   
        DB::statement('DROP VIEW IF EXISTS v_produk');      

        // 1. Satuan tambahan sesuai UI kasir (Sak & Kubik sudah ada)
        foreach (['Batang', 'Pail', 'Lembar', 'Kg', 'Rit', 'Buah'] as $nama) {
            DB::table('satuan')->insertOrIgnore(['nama' => $nama]);
        }

        // 2. produk: SKU untuk pencarian + batas stok kritis
        Schema::table('produk', function (Blueprint $t) {
            $t->string('sku', 30)->nullable()->unique()->after('id');
            $t->decimal('stok_minimum', 12, 2)->default(10)->after('stok');
        });

        // 3. keranjang_item: per kasir + mendukung item custom (ongkir, kuli muat, dll)
        //    Keranjang bersifat sementara, jadi aman dikosongkan.
        DB::table('keranjang_item')->delete();

        Schema::table('keranjang_item', function (Blueprint $t) {
            $t->dropForeign(['produk_id']);
            $t->dropUnique(['produk_id']);
        });
        Schema::table('keranjang_item', function (Blueprint $t) {
            $t->unsignedBigInteger('produk_id')->nullable()->change();
        });
        Schema::table('keranjang_item', function (Blueprint $t) {
            $t->foreign('produk_id')->references('id')->on('produk');
            $t->foreignId('kasir_id')->after('id')->constrained('pengguna')->cascadeOnDelete();
            $t->string('nama_item', 150)->nullable();          // khusus item custom
            $t->decimal('harga_custom', 14, 2)->nullable();    // khusus item custom
            $t->unique(['kasir_id', 'produk_id']);             // produk sama = update qty (per kasir)
        });
        DB::statement(
            'ALTER TABLE keranjang_item ADD CONSTRAINT keranjang_item_jenis CHECK (' .
            'produk_id IS NOT NULL OR (nama_item IS NOT NULL AND harga_custom IS NOT NULL AND harga_custom >= 0))'
        );

        // 4. nota_item: produk_id boleh kosong untuk item custom
        Schema::table('nota_item', function (Blueprint $t) {
            $t->dropForeign(['produk_id']);
        });
        Schema::table('nota_item', function (Blueprint $t) {
            $t->unsignedBigInteger('produk_id')->nullable()->change();
        });
        Schema::table('nota_item', function (Blueprint $t) {
            $t->foreign('produk_id')->references('id')->on('produk');
            $t->string('keterangan', 150)->nullable()->after('produk_id');   // nama item custom
        });
        DB::statement(
            'ALTER TABLE nota_item ADD CONSTRAINT nota_item_jenis CHECK (' .
            'produk_id IS NOT NULL OR keterangan IS NOT NULL)'
        );

        // 5. View dibuat ulang
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

        DB::statement("
            CREATE OR REPLACE VIEW v_keranjang AS
            SELECT ki.id AS item_id, ki.kasir_id, ki.produk_id,
                   COALESCE(p.nama, ki.nama_item)          AS nama,
                   COALESCE(s.nama, '')                    AS satuan,
                   COALESCE(p.harga_jual, ki.harga_custom) AS harga_jual,
                   ki.qty,
                   (COALESCE(p.harga_jual, ki.harga_custom) * ki.qty) AS total_harga,
                   (ki.produk_id IS NULL)                  AS is_custom
            FROM keranjang_item ki
            LEFT JOIN produk p ON p.id = ki.produk_id
            LEFT JOIN satuan s ON s.id = p.satuan_id
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS v_keranjang');   
        DB::statement('DROP VIEW IF EXISTS v_produk');      


        DB::statement("
            CREATE OR REPLACE VIEW v_keranjang AS
            SELECT ki.id AS item_id, p.id AS produk_id, p.nama,
                   s.nama AS satuan, p.harga_jual, ki.qty,
                   (p.harga_jual * ki.qty) AS total_harga
            FROM keranjang_item ki
            JOIN produk p ON p.id = ki.produk_id
            JOIN satuan s ON s.id = p.satuan_id
        ");
        DB::statement("
            CREATE OR REPLACE VIEW v_produk AS
            SELECT p.id, p.nama, k.nama AS kategori, s.nama AS satuan,
                   p.deskripsi, p.harga_jual, p.stok
            FROM produk p
            JOIN kategori_produk k ON k.id = p.kategori_id
            JOIN satuan s          ON s.id = p.satuan_id
        ");

        DB::statement('ALTER TABLE nota_item DROP CONSTRAINT nota_item_jenis');
        DB::table('nota_item')->whereNull('produk_id')->delete();
        Schema::table('nota_item', function (Blueprint $t) {
            $t->dropForeign(['produk_id']);
            $t->dropColumn('keterangan');
        });
        Schema::table('nota_item', function (Blueprint $t) {
            $t->unsignedBigInteger('produk_id')->nullable(false)->change();
        });
        Schema::table('nota_item', function (Blueprint $t) {
            $t->foreign('produk_id')->references('id')->on('produk');
        });

        DB::statement('ALTER TABLE keranjang_item DROP CONSTRAINT keranjang_item_jenis');
        DB::table('keranjang_item')->delete();
        Schema::table('keranjang_item', function (Blueprint $t) {
            $t->dropForeign(['kasir_id']);
            $t->dropForeign(['produk_id']);
            $t->dropUnique(['kasir_id', 'produk_id']);
            $t->dropColumn(['kasir_id', 'nama_item', 'harga_custom']);
        });
        Schema::table('keranjang_item', function (Blueprint $t) {
            $t->unsignedBigInteger('produk_id')->nullable(false)->change();
        });
        Schema::table('keranjang_item', function (Blueprint $t) {
            $t->foreign('produk_id')->references('id')->on('produk');
            $t->unique('produk_id');
        });

        Schema::table('produk', function (Blueprint $t) {
            $t->dropUnique(['sku']);
            $t->dropColumn(['sku', 'stok_minimum']);
        });

        DB::table('satuan')->whereIn('nama', ['Batang', 'Pail', 'Lembar', 'Kg', 'Rit', 'Buah'])->delete();
    }
};