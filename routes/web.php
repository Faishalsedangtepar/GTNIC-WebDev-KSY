<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KasirController;


Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});


Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function (Request $request) {
        return match ($request->user()->role) {
            'admin'  => redirect()->route('admin.dashboard'),
            'kasir'  => redirect()->route('kasir.dashboard'),
            default  => abort(403, 'Role tidak dikenali.'),
        };
    })->name('dashboard');

    Route::view('/riwayat', 'kasir.riwayat')->name('riwayat');
    Route::view('/pembayaran', 'kasir.pembayaran')->name('pembayaran');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::view('/dashboard', 'admin.admin')->name('dashboard');
    });

    Route::middleware('role:kasir')->prefix('kasir')->name('kasir.')->group(function () {
        Route::get('/dashboard', [KasirController::class, 'index'])->name('dashboard');
        Route::get('/produk', [KasirController::class, 'produk'])->name('produk');

        Route::get('/keranjang', [KasirController::class, 'keranjang'])->name('keranjang');
        Route::post('/keranjang', [KasirController::class, 'tambah'])->name('keranjang.tambah');
        Route::post('/keranjang/custom', [KasirController::class, 'tambahCustom'])->name('keranjang.custom');
        Route::patch('/keranjang/{id}', [KasirController::class, 'ubah'])->name('keranjang.ubah');
        Route::delete('/keranjang/{id}', [KasirController::class, 'hapus'])->name('keranjang.hapus');
        

        Route::view('/riwayat', 'kasir.riwayat')->name('riwayat');
        Route::view('/pembayaran', 'kasir.pembayaran')->name('pembayaran');
    });

    Route::get('/force-logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
});
});

