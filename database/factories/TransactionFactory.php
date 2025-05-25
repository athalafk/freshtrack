<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Transaction;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;
    public function definition(): array
    {
        $tipeTransaksi = $this->faker->randomElement(['masuk', 'keluar', 'edit', 'tambah']);
        $stok = 0;

        if ($tipeTransaksi === 'masuk' || $tipeTransaksi === 'tambah') {
            $stok = $this->faker->numberBetween(11, 100);
        } elseif ($tipeTransaksi === 'keluar') {
            $stok = $this->faker->numberBetween(1, 10);
        }

        return [
            'type' => $tipeTransaksi,
            'item' => $this->faker->words(2, true),
            'stock' => $stok,
            'actor' => $this->faker->randomElement(['admin', 'staf']),
        ];
    }
}
