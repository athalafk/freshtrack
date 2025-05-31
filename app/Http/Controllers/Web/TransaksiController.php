<?php

namespace App\Http\Controllers\Web;

use Carbon\Carbon;
use App\Models\Barang;
use App\Models\BatchBarang;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade as PDF;

class TransaksiController extends Controller
{
    public function showBarangMasuk()
    {   
    $barangList = Barang::all();
    return view('transaksi.barang-masuk', compact('barangList'));
    }




    public function storeBarangMasuk(Request $request)
    {
    $request->validate([
        'nama_barang' => 'required|string',
        'stok' => 'required|integer|min:1',
        'tanggal_kadaluarsa' => 'required|date|after_or_equal:today',
    ]);

    $barang = Barang::where('nama_barang', $request->nama_barang)->first();

    if (!$barang) {
        return back()->with('error', 'Barang tidak ditemukan.');
    }

    BatchBarang::create([
        'barang_id' => $barang->id,
        'stok' => $request->stok,
        'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
    ]);

    Transaction::create([
        'type' => 'masuk',
        'item' => $barang->nama_barang,
        'stock' => $request->stok,
        'actor' => Auth::user()->username,
    ]);

    return back()->with('success', 'Barang masuk berhasil disimpan.');
    }
}





