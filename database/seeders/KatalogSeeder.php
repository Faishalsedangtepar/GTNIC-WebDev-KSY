<?php
 
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
 
class KatalogSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            'Semen & Pasir', 'Besi & Baja', 'Cat & Thinner',
            'Pipa & Fitting', 'Alat Tukang', 'Atap & Seng', 'Kayu & Triplek',
        ];
        foreach ($kategori as $nama) {
            DB::table('kategori_produk')->insertOrIgnore(['nama' => $nama]);
        }
 
        $kat    = DB::table('kategori_produk')->pluck('id', 'nama');
        $satuan = DB::table('satuan')->pluck('id', 'nama');
 
        // [sku, nama, kategori, satuan, harga_jual, stok]
        $produk = [
            ['SMN-TR-50', 'Semen Tiga Roda 50kg',            'Semen & Pasir',  'Sak',    65000, 120],
            ['SMN-GR-50', 'Semen Gresik 50kg',               'Semen & Pasir',  'Sak',    64000, 80],
            ['PSR-BK-01', 'Pasir Bangka Super',              'Semen & Pasir',  'Kubik',  320000, 15],
            ['BTA-HB-75', 'Bata Ringan Hebel 7.5cm',         'Semen & Pasir',  'Kubik',  650000, 30],
            ['BSI-BP-10', 'Besi Beton Polos 10mm SNI',       'Besi & Baja',    'Batang', 72000, 45],
            ['CAT-DX-5P', 'Cat Tembok Dulux Catylac 5kg Putih', 'Cat & Thinner', 'Pail', 135000, 18],
            ['CAT-AS-01', 'Catylac Dasar Alkali Sealer',     'Cat & Thinner',  'Pail',   185000, 12],
            ['PPA-WV-34', 'Pipa PVC Wavin AW 3/4 inch 4m',   'Pipa & Fitting', 'Batang', 38000, 85],
            ['ATP-SG-20', 'Seng Gelombang BJLS 0.20',        'Atap & Seng',    'Lembar', 58000, 8],
            ['KYU-TP-09', 'Triplek Meranti 9mm',             'Kayu & Triplek', 'Lembar', 115000, 40],
            ['ALT-PK-05', 'Paku Kayu 5cm Campur',            'Alat Tukang',    'Kg',     22000, 50],
        ];
 
        foreach ($produk as [$sku, $nama, $k, $s, $harga, $stok]) {
            DB::table('produk')->updateOrInsert(
                ['sku' => $sku],
                [
                    'nama'        => $nama,
                    'kategori_id' => $kat[$k],
                    'satuan_id'   => $satuan[$s],
                    'harga_jual'  => $harga,
                    'stok'        => $stok,
                ],
            );
        }
    }
}
 
