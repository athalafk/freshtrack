<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Barang;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $userAdmin = User::where('username', 'admin')->first();
        
        $beras = Barang::where('nama_barang', 'Beras Pandan Wangi')->first();
        $daging = Barang::where('nama_barang', 'Daging Sapi')->first();
        $minyak = Barang::where('nama_barang', 'Minyak Goreng')->first();

        if ($userAdmin && $beras) {
            Transaction::create([
                'type' => 'masuk',
                'item' => $beras->nama_barang,
                'stock' => 50,
                'actor' => $userAdmin->username,
            ]);
        }

        if ($userAdmin && $daging) {
            Transaction::create([
                'type' => 'keluar',
                'item' => $daging->nama_barang,
                'stock' => 10,
                'actor' => $userAdmin->username,
            ]);
        }
        
        if ($userAdmin && $minyak) {
            Transaction::create([
                'type' => 'edit',
                'item' => $minyak->nama_barang,
                'stock' => 0,
                'actor' => $userAdmin->username,
            ]);
        }
    }
}