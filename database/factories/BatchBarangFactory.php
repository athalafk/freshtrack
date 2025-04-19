<?php

namespace Database\Factories;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BatchBarang>
 */
class BatchBarangFactory extends Factory
{
    public function definition(): array
    {
        return [
            'barang_id' => Barang::factory(),
            'tanggal_kadaluarsa' => fake()->dateTimeBetween('+1 month', '+2 years')->format('Y-m-d'),
            'stok' => fake()->numberBetween(1, 100),
        ];
    }
}
