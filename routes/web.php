<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotaController;

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
            Route::get('/dashboard', [KasirController::class, 'index'])->name('dashboard');
            Route::get('/produk', [KasirController::class, 'produk'])->name('produk');

            Route::get('/keranjang', [KasirController::class, 'keranjang'])->name('keranjang');
            Route::post('/keranjang', [KasirController::class, 'tambah'])->name('keranjang.tambah');
            Route::post('/keranjang/custom', [KasirController::class, 'tambahCustom'])->name('keranjang.custom');
            Route::patch('/keranjang/{id}', [KasirController::class, 'ubah'])->name('keranjang.ubah');
            Route::delete('/keranjang/{id}', [KasirController::class, 'hapus'])->name('keranjang.hapus');
            Route::delete('keranjang', [KasirController::class, 'kosongkan']);

            Route::get('/nota', [NotaController::class, 'index'])->name('nota.index');
            Route::post('/nota', [NotaController::class, 'store'])->name('nota.store');
            Route::get('/nota/{id}', [NotaController::class, 'show'])->name('nota.show')->whereNumber('id');
            Route::post('/nota/{id}/lunasi', [NotaController::class, 'lunasi'])->name('nota.lunasi')->whereNumber('id');

            Route::view('/riwayat', 'kasir.riwayat')->name('riwayat');
            Route::get('/pembayaran', [KasirController::class, 'checkout'])->name('pembayaran');
        });

    Route::get('/force-logout', function (Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    });
});