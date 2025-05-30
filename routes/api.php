<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController; 
use App\Http\Controllers\Api\BarangController; 
use App\Http\Controllers\Api\TransactionController; 


Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::prefix('barang')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [BarangController::class, 'getBarang']);
        Route::get('/batch-barang', [BarangController::class, 'getBatchBarang']);
        Route::put('/update/{id}', [BarangController::class, 'updateBarang']);
        Route::delete('/delete/{id}', [BarangController::class, 'deleteBarang']);
        Route::post('/masuk', [BarangController::class, 'barangMasuk']);
        Route::post('/keluar', [BarangController::class, 'barangKeluar']);
        Route::post('/create', [BarangController::class, 'createBarang']);
    });
});

Route::prefix('transactions')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [TransactionController::class, 'index']);
    });
});




