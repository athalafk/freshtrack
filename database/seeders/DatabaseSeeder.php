<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Barang;
use App\Models\BatchBarang;
use App\Models\Transaction;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User dummy
        User::factory()->create([
            'username' => 'admin',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        // Barang dan BatchBarang dummy
        Barang::factory(10)->create()->each(function ($barang) {
            $barang->batchBarang()->createMany(
                BatchBarang::factory(3)->make()->toArray()
            );
        });
    }
}
