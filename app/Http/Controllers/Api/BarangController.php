<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\BatchBarang;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    /**
     * Get all barang with total stok.
     */
    public function getBarang()
    {
        $results = DB::table('barang as b')
            ->leftJoin(DB::raw('(SELECT barang_id, SUM(stok) as total_stok FROM batch_barang GROUP BY barang_id) as s'), 'b.id', '=', 's.barang_id')
            ->select(
                'b.id',
                'b.nama_barang',
                'b.satuan',
                DB::raw('CAST(COALESCE(s.total_stok, 0) AS UNSIGNED) as total_stok')
            )
            ->orderBy('b.nama_barang', 'asc')
            ->get();

        return response()->json($results);
    }

    /**
     * Get all batch_barang with barang info and days until expired.
     */
    public function getBatchBarang()
    {
        $results = DB::table('batch_barang as bg')
            ->join('barang as b', 'bg.barang_id', '=', 'b.id')
            ->select(
                'bg.id',
                'bg.barang_id',
                'b.nama_barang',
                'bg.stok',
                'b.satuan',
                'bg.tanggal_kadaluarsa',
                DB::raw('DATEDIFF(DATE(bg.tanggal_kadaluarsa), CURDATE()) AS hari_menuju_kadaluarsa')
            )
            ->orderBy('bg.tanggal_kadaluarsa', 'asc')
            ->get()
            ->map(function ($item) {
                $item->tanggal_kadaluarsa = \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->toISOString();
                return $item;
            });

        return response()->json($results);
    }
    
    /**
     * Update barang data.
     */
    public function updateBarang(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string',
            'satuan' => 'required|string',
        ]);

        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json(['error' => 'Barang tidak ditemukan.'], 404);
        }

        $barang->update([
            'nama_barang' => $request->nama_barang,
            'satuan' => $request->satuan,
        ]);

        Transaction::create([
            'type' => 'edit',
            'item' => $request->nama_barang,
            'stock' => 0,
            'actor' => $request->user()->username,
        ]);

        return response()->json(['message' => 'Barang berhasil diperbarui.']);
    }

    public function deleteBarang($id)
    {
        $barang = Barang::find($id);
        
        if (!$barang)
        {
            return response()->json(['error' => 'Barang tidak ditemukan.'], 404);
        }

        $namabarang = $barang->nama_barang;
        $stok = $barang->stok;

        BatchBarang::where('barang_id', $id)->delete();
        
        $barang->delete();

        Transaction::create([
            'type' => 'hapus',
            'item' => $namabarang,
            'stock' => $stok,
            'actor' => $request->user()->username,
        ]);
        
        return response()->json(['message' => 'Barang berhasil dihapus.']);
    }
    
    /**
     * Menambahkan stok barang.
     */
    public function barangMasuk(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string',
            'stok' => 'required|integer|min:1',
            'tanggal_kadaluarsa' => 'required|date|after_or_equal:today',
        ]);
        $barang = Barang::where('nama_barang', $request->nama_barang)->first();

        if (!$barang) {
            return response()->json(['message' => 'Barang tidak ditemukan'], 404);
        }
        $batch = new BatchBarang();
        $batch->barang_id = $barang->id;
        $batch->stok = $request->stok;
        $batch->tanggal_kadaluarsa = $request->tanggal_kadaluarsa;
        $batch->save();

        // Riwayat
        Transaction::create([
            'type' => 'masuk',
            'item' => $barang->nama_barang,
            'stock' => $request->stok,
            'actor' => $request->user()->username,
        ]);
            
        return response()->json([
            'message' => 'Stok barang berhasil ditambahkan',
            'batch' => $batch,
        ]);
    }

    public function createBarang(Request $request){
        $request->validate([
            'nama_barang' => 'required|string',
            'satuan' => 'required|string'
        ]);
        $barang = Barang::where('nama_barang', $request->nama_barang)->first();

        if ($barang) {
            return response()->json(['message' => 'Barang sudah di registrasi'], 404);
        }
        $barang = new Barang();
        $barang->nama_barang = $request->nama_barang;
        $barang->satuan = $request->satuan;
        $barang->save();

        // Riwayat
        Transaction::create([
            'type' => 'tambah',
            'item' => $barang->nama_barang,
            'stock' => 0,
            'actor' => $request->user()->username,
        ]);
            
        return response()->json([
            'message' => 'Barang berhasil di registrasi'
        ]);
    }
}

