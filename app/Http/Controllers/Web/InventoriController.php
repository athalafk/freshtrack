<?php

namespace App\Http\Controllers\Web;

use Carbon\Carbon;
use App\Models\Barang;
use App\Models\BatchBarang;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class InventoriController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->input('search');

        // Data untuk "Daftar Barang"
        $queryBarang = DB::table('barang as b')
            ->leftJoin(DB::raw('(SELECT barang_id, SUM(stok) as total_stok FROM batch_barang GROUP BY barang_id) as s'), 'b.id', '=', 's.barang_id')
            ->select(
                'b.id',
                'b.nama_barang',
                'b.satuan',
                DB::raw('CAST(COALESCE(s.total_stok, 0) AS UNSIGNED) as total_stok')
            );

        if ($searchTerm) {
            $queryBarang->where('b.nama_barang', 'like', '%' . $searchTerm . '%');
        }
        $daftarBarang = $queryBarang->orderBy('b.nama_barang', 'asc')->paginate(10, ['*'], 'barangPage');


        // Data untuk "Status Kadaluarsa"
        $queryBatch = DB::table('batch_barang as bg')
            ->join('barang as b', 'bg.barang_id', '=', 'b.id')
            ->select(
                'bg.id',
                'bg.barang_id',
                'b.nama_barang',
                'bg.stok',
                'b.satuan',
                'bg.tanggal_kadaluarsa',
                DB::raw('DATEDIFF(DATE(bg.tanggal_kadaluarsa), CURDATE()) AS hari_menuju_kadaluarsa')
            );

        if ($searchTerm) {
            $queryBatch->where('b.nama_barang', 'like', '%' . $searchTerm . '%');
        }
        $statusKadaluarsa = $queryBatch->orderBy('bg.tanggal_kadaluarsa', 'asc')
            ->paginate(10, ['*'], 'batchPage')
            ->through(function ($item) {
                $item->tanggal_kadaluarsa_formatted = Carbon::parse($item->tanggal_kadaluarsa)->isoFormat('DD/MM/YYYY');
                return $item;
            });


        return view('inventori.index', compact('daftarBarang', 'statusKadaluarsa', 'searchTerm'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required|string',
            'satuan' => 'required|string',
        ]);

        $barang->update([
            'nama_barang' => $request->nama_barang,
            'satuan' => $request->satuan,
        ]);

        Transaction::create([
            'type' => 'edit',
            'item' => $barang->nama_barang,
            'stock' => 0,
            'actor' => Auth::user()->username
        ]);

        return redirect()->route('inventori.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    public function delete(Barang $barang)
    {
        $namaBarangDiHapus = $barang->nama_barang;
        $totalStokDiHapus = BatchBarang::where('barang_id', $barang->id)->sum('stok');

        BatchBarang::where('barang_id', $barang->id)->delete();
        $barang->delete();

        Transaction::create([
            'type' => 'hapus',
            'item' => $namaBarangDiHapus,
            'stock' => $totalStokDiHapus,
            'actor' => Auth::user()->username
        ]);

        return redirect()->route('inventori.index')
            ->with('success', 'Barang berhasil dihapus.');
    }
}
