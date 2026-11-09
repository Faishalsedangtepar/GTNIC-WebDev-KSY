<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    public $timestamps = false;
    protected $fillable = [
        'sku', 'nama', 'brand', 'kategori_id', 'satuan_id', 'deskripsi',
        'harga_jual', 'harga_beli', 'stok', 'stok_minimum', 'aktif',
    ];

    protected $casts = ['aktif' => 'boolean'];
}