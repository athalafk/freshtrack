<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController; 
use App\Http\Controllers\Web\InventoriController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('inventori.index');
    }
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/inventori', [InventoriController::class, 'index'])->name('inventori.index');

    Route::post('/logout', [AuthController::class, 'destroy'])
                ->name('logout');

    Route::get('/barang/create', function() {
        return redirect()->route('inventori.index')->with('info', 'Halaman Buat Barang belum tersedia.');
    })->name('barang.create');

    Route::get('/transaksi', function() {
        return redirect()->route('inventori.index')->with('info', 'Halaman Transaksi belum tersedia.');
    })->name('transaksi.index');

    Route::get('/riwayat', function() {
        return redirect()->route('inventori.index')->with('info', 'Halaman Riwayat belum tersedia.');
    })->name('riwayat.index');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])
                ->name('login');
    Route::post('/login', [AuthController::class, 'store']);
});