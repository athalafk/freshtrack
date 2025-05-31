<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\InventoriController;
use App\Http\Controllers\Web\HistoryController;
use App\Http\Controllers\Web\TransaksiController;


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

    Route::put('/inventori/{barang}', [InventoriController::class, 'update'])->name('inventori.update');
    Route::delete('/inventori/{barang}', [InventoriController::class, 'delete'])->name('inventori.delete');

    Route::get('/transaksi/barang-masuk', [TransaksiController::class, 'showBarangMasuk'])->name('transaksi.barang-masuk');
    Route::post('/transaksi/barang-masuk', [TransaksiController::class, 'storeBarangMasuk'])->name('transaksi.barang-masuk.store');

    Route::get('/transaksi', function () {
        return redirect()->route('inventori.index')->with('info', 'Halaman Transaksi belum tersedia.');
    })->name('transaksi.index');

    Route::get('/registrasi', [InventoriController::class, 'create'])->name('registrasi.create'); //
    Route::post('/registrasi', [InventoriController::class, 'store'])->name('registrasi.store'); //

    Route::get('/riwayat', [HistoryController::class, 'indexHistory'])->name('riwayat.index');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])
        ->name('login');
    Route::post('/login', [AuthController::class, 'store']);
});