<?php

namespace App\Http\Controllers\Web;

use App\Models\Barang;
use App\Models\BatchBarang;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;


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

    public function showBarangKeluar()
    {
        $barangList = Barang::all();
        return view('transaksi.barang-keluar', compact('barangList'));
    }

    public function storeBarangKeluar(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|exists:barang,nama_barang',
            'stok_keluar' => 'required|integer|min:1'
        ]);

        $barang = Barang::where('nama_barang', $request->nama_barang)->first();

        if (!$barang) {
            return back()->with('error', 'Barang tidak ditemukan.')->withInput();
        }

        $totalStokValid = BatchBarang::where('barang_id', $barang->id)
                                ->whereDate('tanggal_kadaluarsa', '>=', now()->toDateString())
                                ->sum('stok');

        if ($request->stok_keluar > $totalStokValid) {
            return back()->with('error', 'Stok tidak mencukupi atau batch yang tersedia sudah kadaluwarsa. Stok tersedia: ' . $totalStokValid)->withInput();
        }

        $stokDiminta = $request->stok_keluar;

        try {
            DB::transaction(function () use ($barang, $stokDiminta, $request) {
                $batches = BatchBarang::where('barang_id', $barang->id)
                                     ->whereDate('tanggal_kadaluarsa', '>=', now()->toDateString())
                                     ->orderBy('tanggal_kadaluarsa', 'asc')
                                     ->get();

                $stokTerkeluarkandariBatch = 0;

                foreach ($batches as $batch) {
                    if ($stokDiminta <= 0) {
                        break;
                    }

                    $stokDapatDiambilDariBatchIni = min($stokDiminta, $batch->stok);
                    
                    $batch->stok -= $stokDapatDiambilDariBatchIni;
                    $stokDiminta -= $stokDapatDiambilDariBatchIni;
                    $stokTerkeluarkandariBatch += $stokDapatDiambilDariBatchIni;

                    if ($batch->stok == 0) {
                        $batch->delete();
                    } else {
                        $batch->save();
                    }
                }
                
                if ($stokTerkeluarkandariBatch != $request->stok_keluar) {
                    throw new Exception('Terjadi ketidaksesuaian stok saat proses barang keluar. Silakan coba lagi.');
                }

                Transaction::create([
                    'type' => 'keluar',
                    'item' => $barang->nama_barang,
                    'stock' => $request->stok_keluar,
                    'actor' => Auth::user()->username,
                ]);
            });

            return back()->with('success', 'Barang berhasil dikeluarkan.');

        } catch (Exception $e) {
            return back()->with('error', 'Gagal mengeluarkan barang: ' . $e->getMessage())->withInput();
        }
    }
}
