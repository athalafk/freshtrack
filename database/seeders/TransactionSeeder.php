<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        Transaction::create([
            'date' => '2024-01-20',
            'type' => 'masuk',
            'item' => 'Beras',
            'stock' => 20,
            'actor' => 'admin'
        ]);

        Transaction::create([
            'date' => '2024-01-25',
            'type' => 'keluar',
            'item' => 'Minyak Goreng',
            'stock' => 35,
            'actor' => 'helmi'
        ]);

    }
}
?>