<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\BatchBarang;
use Carbon\Carbon;

class BatchBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $semuaBarang = Barang::all();

        if ($semuaBarang->isEmpty()) {
            $this->command->info('Tidak ada data barang untuk dibuatkan batch. Jalankan BarangSeeder terlebih dahulu.');
            return;
        }

        foreach ($semuaBarang as $barang) {
            // Membuat 1-3 batch untuk setiap barang
            $jumlahBatch = rand(1, 3);
            for ($i = 0; $i < $jumlahBatch; $i++) {
                BatchBarang::create([
                    'barang_id' => $barang->id,
                    'tanggal_kadaluarsa' => Carbon::now()->addDays(rand(7, 180))->toDateString(),
                    'stok' => rand(10, 50),
                ]);
            }
        }
    }
}
