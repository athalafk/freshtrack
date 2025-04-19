<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Barang>
 */
class BarangFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_barang' => fake()->word(),
            'satuan' => fake()->randomElement(['pcs', 'liter', 'kg']),
        ];
    }
}
