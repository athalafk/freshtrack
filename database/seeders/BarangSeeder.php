<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barang;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bahanMentah = [
            ['nama_barang' => 'Beras Pandan Wangi', 'satuan' => 'kg'],
            ['nama_barang' => 'Daging Sapi', 'satuan' => 'kg'],
            ['nama_barang' => 'Ayam Paha Fillet', 'satuan' => 'kg'],
            ['nama_barang' => 'Bawang Merah', 'satuan' => 'kg'],
            ['nama_barang' => 'Bawang Putih', 'satuan' => 'kg'],
            ['nama_barang' => 'Cabai Merah', 'satuan' => 'kg'],
            ['nama_barang' => 'Minyak Goreng', 'satuan' => 'liter'],
            ['nama_barang' => 'Telur Ayam', 'satuan' => 'kg'],
            ['nama_barang' => 'Kecap Manis', 'satuan' => 'liter'],
            ['nama_barang' => 'Gula', 'satuan' => 'kg'],
            ['nama_barang' => 'Garam', 'satuan' => 'kg'],
            ['nama_barang' => 'Tepung Terigu', 'satuan' => 'kg'],
        ];

        foreach ($bahanMentah as $bahan) {
            Barang::create([
                'nama_barang' => $bahan['nama_barang'],
                'satuan' => $bahan['satuan'],
            ]);
        }
    }
}
