<?php

use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Halaman awal diarahkan ke login.
Route::get('/', fn () => redirect()->route('login'));

// Autentikasi: hanya pengguna yang belum login.
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Semua halaman setelah login.
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Arahkan pengguna sesuai role setelah login.
    Route::get('/dashboard', function (Request $request) {
        return match ($request->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'kasir' => redirect()->route('kasir.dashboard'),
            default => abort(403, 'Role tidak dikenali.'),
        };
    })->name('dashboard');

    // Halaman khusus admin.
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            // admin.admin adalah view dashboard admin.
            Route::view('/dashboard', 'admin.admin')->name('dashboard');

            // Halaman laporan stok barang.
            Route::view('/laporanstok', 'admin.laporanstok')->name('laporanstok');

            // Halaman riwayat nota bulanan.
            Route::view('/riwayatbulanan', 'admin.riwayatbulanan')->name('riwayatbulanan');
        });

    // Halaman khusus kasir.
    Route::middleware('role:kasir')
        ->prefix('kasir')
        ->name('kasir.')
        ->group(function () {
            Route::view('/dashboard', 'kasir.kasir')->name('dashboard');
            Route::view('/riwayat', 'kasir.riwayat')->name('riwayat');
            Route::view('/pembayaran', 'kasir.pembayaran')->name('pembayaran');
        });
});
