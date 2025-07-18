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

class InventoriController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->input('search');
        $sortBy = $request->input('sort_by', 'tanggal_kadaluarsa'); 
        $sortDirection = $request->input('sort_direction', 'asc'); 

        $sortDirection = in_array(strtolower($sortDirection), ['asc', 'desc']) ? strtolower($sortDirection) : 'asc';

        $validSortColumnsBarang = ['nama_barang', 'total_stok', 'satuan'];
        $sortByBarang = in_array($sortBy, $validSortColumnsBarang) ? $sortBy : 'nama_barang';

        $queryBarang = DB::table('barang as b')
            ->leftJoin(DB::raw('(SELECT barang_id, SUM(stok) as total_stok FROM batch_barang GROUP BY barang_id) as s'), 'b.id', '=', 's.barang_id')
            ->select(
                'b.id',
                'b.nama_barang',
                'b.satuan',
                DB::raw('COALESCE(s.total_stok, 0) as total_stok')
            );

        if ($searchTerm) {
            $queryBarang->where('b.nama_barang', 'like', '%' . $searchTerm . '%');
        }

        if ($sortByBarang === 'total_stok') {
            $queryBarang->orderBy(DB::raw('COALESCE(s.total_stok, 0)'), $sortDirection);
        } else {
            $queryBarang->orderBy('b.' . $sortByBarang, $sortDirection);
        }

        $daftarBarang = $queryBarang->paginate(10, ['*'], 'barangPage');


        // Data untuk "Status Kadaluarsa"
        $validSortColumnsBatch = ['nama_barang', 'stok', 'tanggal_kadaluarsa', 'hari_menuju_kadaluarsa'];
        $sortByBatch = in_array($sortBy, $validSortColumnsBatch) ? $sortBy : 'tanggal_kadaluarsa';

        $queryBatch = DB::table('batch_barang as bg')
            ->join('barang as b', 'bg.barang_id', '=', 'b.id')
            ->select(
                'bg.id',
                'bg.barang_id',
                'b.nama_barang',
                'bg.stok',
                'b.satuan',
                'bg.tanggal_kadaluarsa',
                DB::raw("DATE_PART('day', bg.tanggal_kadaluarsa::timestamp - CURRENT_DATE) AS hari_menuju_kadaluarsa")
            );

        if ($searchTerm) {
            $queryBatch->where('b.nama_barang', 'like', '%' . $searchTerm . '%');
        }

        if ($sortByBatch === 'nama_barang') {
            $queryBatch->orderBy('b.nama_barang', $sortDirection);
        } elseif ($sortByBatch === 'hari_menuju_kadaluarsa') {
            $queryBatch->orderBy(DB::raw("DATE_PART('day', bg.tanggal_kadaluarsa::timestamp - CURRENT_DATE)"), $sortDirection);
        } elseif (in_array($sortByBatch, ['stok', 'tanggal_kadaluarsa'])) {
             $queryBatch->orderBy('bg.' . $sortByBatch, $sortDirection);
        } else {
            $queryBatch->orderBy('bg.tanggal_kadaluarsa', 'asc');
        }

        $statusKadaluarsa = $queryBatch->orderBy(
            $sortByBatch === 'nama_barang'
                ? 'b.nama_barang'
                : ($sortByBatch === 'hari_menuju_kadaluarsa'
                    ? DB::raw("DATE_PART('day', bg.tanggal_kadaluarsa::timestamp - CURRENT_DATE)")
                    : 'bg.' . $sortByBatch),
            $sortDirection
        )
            ->paginate(10, ['*'], 'batchPage')
            ->through(function ($item) {
                $item->tanggal_kadaluarsa_formatted = Carbon::parse($item->tanggal_kadaluarsa)->isoFormat('DD/MM/YYYY');
                return $item;
            });


        return view('inventori.index', compact(
            'daftarBarang',
            'statusKadaluarsa',
            'searchTerm',
            'sortBy',
            'sortDirection'
        ));
    }

    public function create()
    {
        $barangList = Barang::select('nama_barang')->get();
        return view('registrasi.registrasi', compact('barangList'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required|string|unique:barang,nama_barang|max:255',
            'satuan' => 'required|string|max:50',
        ], [
            'nama_barang.unique' => 'Nama Barang sudah terdaftar. Silakan gunakan nama lain.',
            'nama_barang.required' => 'Nama Barang tidak boleh kosong.',
            'satuan.required' => 'Satuan tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('registrasi.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $barang = Barang::create([
            'nama_barang' => $request->nama_barang,
            'satuan' => $request->satuan,
        ]);

        Transaction::create([
            'type' => 'tambah',
            'item' => $barang->nama_barang,
            'stock' => 0,
            'actor' => Auth::user()->username,
        ]);

        return redirect()->route('registrasi.create')->with('success', 'Barang berhasil diregistrasi!');
    }

    public function update(Request $request, Barang $barang)
    {
        $rules = [
            'nama_barang' => [
                'required',
                'string',
                Rule::unique('barang', 'nama_barang')->ignore($barang->id),
            ],
            'satuan' => 'required|string',  
        ];

        $messages = ['nama_barang.unique' => 'Nama Barang telah digunakan.'];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Perubahan gagal dilakukan. Periksa input Anda.',
                    'errors' => $validator->errors()
                ], 422); 
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $namaBarangLama = $barang->nama_barang;

        $barang->update([
            'nama_barang' => $request->nama_barang,
            'satuan' => $request->satuan,
        ]);

        Transaction::create([
            'type' => 'edit',
            'item' => "{$namaBarangLama} -> {$barang->nama_barang}",
            'stock' => 0,
            'actor' => Auth::user()->username
        ]);

        $successMessage = 'Barang berhasil diperbarui.';

        if ($request->expectsJson()) {
            session()->flash('success', $successMessage);
            return response()->json([
                'message' => $successMessage,
                'barang' => $barang 
            ]);
        }

        return redirect()->route('inventori.index')
            ->with('success', $successMessage);
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
